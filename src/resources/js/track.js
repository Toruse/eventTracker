import FingerprintJS from '@fingerprintjs/fingerprintjs'

const fpPromise = FingerprintJS.load()
var visitorId = null

;(async () => {
    const fp = await fpPromise
    visitorId = await fp.get()
})()

class Queue {
    constructor(name = "queue") {
        this.name = name
        this.elements = []
        this.load()
    }
    enqueue(element) {
        this.elements.push(element)
        this.save()
    }
    dequeue(plength = 1) {
        const items = this.elements.splice(0, plength)
        this.save()
        return items
    }
    peek(plength = 1) {
        return this.elements.slice(0, plength)
    }

    save() {
        localStorage.setItem(this.name, JSON.stringify(this.elements))
    }

    load() {
        let elements = localStorage.getItem(this.name)
        if (elements) {
            this.elements = JSON.parse(elements)
        } else {
            this.elements = []
        }
    }

    clear() {
        this.elements = []
        this.save()
    }

    get length() {
        return this.elements.length
    }
    get isEmpty() {
        return this.length === 0
    }
}

class TrackEvents {

    queue = {}
    thread = {}
    options = {
        packageLength: 1000,
        rangeTimeQueue: 1000,
        deltaTime: 50,
        apiUrl: "https://stat.vloc/api/send",
        events: [
            "blur",
            "click",
            "contextmenu",
            "change",
            "copy",
            "cut",
            "dblclick",
            "focus",
            "keyup",
            "mouseenter",
            "mouseleave",
            "mousemove",
            "paste",
            "wheel",
            "dragenter",
            "dragleave",
            "dragstart",
            "dragend",
            "drop"
        ]
    }
    lastTimestamp = Date.now()

    constructor(el) {
        this.init()
        this.addEvent(el)
        for (const node of el.querySelectorAll('*')) {
            this.addEvent(node)
        }
    }

    addEvent(el) {
        for (const event of this.options.events) {
            el.addEventListener(event, this, false);
        }
    }
    init() {
        if (typeof trackEventConfig !== "undefined") {
            this.options = {...this.options, ...trackEventConfig}
        }

        this.initQueue()

        this.thread.first = setInterval(this.threadFirst.bind(this), this.options.rangeTimeQueue)
        setTimeout(() => this.thread.second = setInterval(this.threadSecond.bind(this), this.options.rangeTimeQueue), Math.round(this.options.rangeTimeQueue / 2))
    }

    initQueue() {
        this.queue.first = new Queue("track_event_first")
        this.queue.second = new Queue("track_event_second")

        while (!this.queue.first.isEmpty) {
            this.queue.second.enqueue(this.queue.first.dequeue())
        }
        this.queue.first.clear()
    }

    threadFirst() {
        if (this.queue.first.isEmpty) {
            this.queue.first.clear()
        } else {
            const pLength = this.options.packageLength
            const packet = this.queue.first.peek(pLength)
            fetch(this.options.apiUrl, {
                method: "POST",
                body: JSON.stringify(packet),
                headers: {
                    "Content-Type": "application/json",
                },
            })
                .then((response) => {
                    if (response.ok) {
                        this.queue.first.dequeue(pLength)
                    }
                })
                .catch((err) => {
                })
        }
    }

    threadSecond() {
        if (this.queue.second.isEmpty) {
            this.queue.second.clear()
        } else {
            const pLength = this.options.packageLength
            const packet = this.queue.second.peek(pLength)
            fetch(this.options.apiUrl, {
                method: "POST",
                body: JSON.stringify(packet),
                headers: {
                    "Content-Type": "application/json",
                },
            })
                .then((response) => {
                    if (response.ok) {
                        this.queue.second.dequeue(pLength)
                    }
                })
                .catch((err) => {
                })
        }
    }

    handleEvent(event) {
        event.stopPropagation()
        if (this.isApplyTimeEvent(event) && !this.isTimeUp()) return
        this.queue.first.enqueue(this.formData(event))
    }

    formData(event) {
        return {
            host: location.hostname,
            href: location.href,
            visitorId,
            type: event.type,
            time: Date.now(),
            xpath: this.getXPath(event.target),
            targetId: event.target.id,
            mouse: this.getRect(event),
            browser: {
                width: document.documentElement.clientWidth,
                height:document.documentElement.clientHeight
            },
            data: this.getData(event)
        }
    }

    isApplyTimeEvent(event) {
        return  event.type === "mousemove"
            || event.type === "scroll"
            || event.type === "drag"
    }

    isTimeUp() {
        const now = Date.now()
        const dt = now - this.lastTimestamp;
        if (dt >= this.options.deltaTime) {
            this.lastTimestamp = now
            return true
        }
        return false
    }

    getRect(event) {
        const rect = event.target.getBoundingClientRect();
        return {
            x: event.pageX || (rect.x + window.scrollX),
            y: event.pageY || (rect.y + window.scrollY),
        }
    }

    getData(event) {
        let data = {
            page: {
                width: document.documentElement.scrollWidth,
                height: document.documentElement.scrollHeight
            },
            mouse: {
                offsetX: event.offsetX || 0,
                offsetY: event.offsetY || 0,
                screenX: event.screenX || 0,
                screenY: event.screenY || 0
            }
        }
        switch (event.type) {
            case "paste":
                data.content = (event.clipboardData || window.clipboardData).getData("text")
                break
            case "change":
                data.input.value = event.target.value
                break
            case "keydown":
            case "keypress":
            case "keyup":
                data.keycode = {
                    key: event.key,
                    code: event.code
                }
                break
            case "scroll":
                data.scroll = {
                    scrollTop: event.target.scrollTop,
                    scrollLeft: event.target.scrollLeft
                }
                break
            case "wheel":
                break
        }
        return data
    }

    getXPath(el) {
        let nodeElem = el;
        if ( nodeElem && nodeElem.id) {
            return "//*[@id=\"" + nodeElem.id + "\"]";
        }
        let parts = [];
        while ( nodeElem && Node.ELEMENT_NODE === nodeElem.nodeType ) {
            let nbOfPreviousSiblings = 0;
            let hasNextSiblings = false;
            let sibling = nodeElem.previousSibling;
            while ( sibling ) {
                if ( sibling.nodeType !== Node.DOCUMENT_TYPE_NODE &&
                    sibling.nodeName === nodeElem.nodeName
                ) {
                    nbOfPreviousSiblings++;
                }
                sibling = sibling.previousSibling;
            }
            sibling = nodeElem.nextSibling;
            while ( sibling ) {
                if ( sibling.nodeName === nodeElem.nodeName ) {
                    hasNextSiblings = true;
                    break;
                }
                sibling = sibling.nextSibling;
            }
            let prefix = nodeElem.prefix ? nodeElem.prefix + ":" : "";
            let nth = nbOfPreviousSiblings || hasNextSiblings
                ? "[" + ( nbOfPreviousSiblings + 1 ) + "]"
                : "";
            parts.push( prefix + nodeElem.localName + nth );
            nodeElem = nodeElem.parentNode;
        }
        return parts.length ? "/" + parts.reverse().join( "/" ) : "";
    }
}

new TrackEvents(document.querySelector("body"))
