import FingerprintJS from '@fingerprintjs/fingerprintjs'

const fpPromise = FingerprintJS.load()
var visitorId = null

fpPromise
    .then(fp => fp.get())
    .then(result => visitorId = result.visitorId)

class Queue {
    constructor(name = "queue") {
        this.name = name
        this.elements = []
    }
    enqueue(element) {
        this.elements.push(element)
    }
    dequeue(plength = 1) {
        const items = this.elements.splice(0, plength)
        return items
    }
    peek(plength = 1) {
        return this.elements.slice(0, plength)
    }

    clear() {
        this.elements = []
    }

    get length() {
        return this.elements.length
    }
    get isEmpty() {
        return this.elements.length === 0
    }
}

class TrackEvents {

    queue = null
    thread = null
    options = {
        id: '',
        packageLength: 17,
        rangeTimeQueue: 1000,
        deltaTime: 500,
        apiUrl: import.meta.env.VITE_APP_URL_API,
        el: document.querySelector("body"),
        sessionId: '',
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
    storageSessionName = 'eventtrackersession'

    constructor() {
        this.init()
        const el = this.options.el
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
        this.options.sessionId = this.getSessionId()

        if (typeof trackEventConfig !== "undefined") {
            this.options = {...this.options, ...trackEventConfig}
        }

        window.addEventListener("beforeunload", this.removeSessionId.bind(this), false);

        this.initQueue()

        this.thread = setInterval(this.send.bind(this), this.options.rangeTimeQueue)
    }

    initQueue() {
        this.queue = new Queue("track_event")
    }

    send() {
        if (!this.queue.isEmpty) {
            const pLength = this.options.packageLength
            const packet = this.queue.peek(pLength)
            fetch(this.options.apiUrl, {
                method: "POST",
                body: JSON.stringify(packet),
                headers: {
                    "Content-Type": "application/json",
                },
            })
                .then((response) => {
                    if (response.ok) {
                        this.queue.dequeue(pLength)
                    }
                })
                .catch((err) => {
                })
        }
        localStorage.setItem(this.storageSessionName, this.options.sessionId)
    }

    handleEvent(event) {
        event.stopPropagation()
        if (this.isApplyTimeEvent(event) && !this.isTimeUp()) return
        this.queue.enqueue(this.formData(event))
    }

    formData(event) {
        return {
            id: this.options.id,
            ht: location.hostname,
            hf: location.href,
            vi: visitorId,
            si: this.options.sessionId,
            tp: event.type,
            tm: Date.now(),
            xp: this.getXPath(event.target),
            ti: event.target.id,
            ms: this.getRect(event),
            br: {
                w: document.documentElement.clientWidth,
                h:document.documentElement.clientHeight
            },
            dt: this.getData(event)
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
            pg: {
                w: document.documentElement.scrollWidth,
                h: document.documentElement.scrollHeight
            },
            ms: {
                ox: event.offsetX || 0,
                oy: event.offsetY || 0,
                sx: event.screenX || 0,
                sy: event.screenY || 0
            }
        }
        switch (event.type) {
            case "paste":
                data.c = (event.clipboardData || window.clipboardData).getData("text")
                break
            case "change":
                data.in = {
                    v: event.target.value
                }
                break
            case "keydown":
            case "keypress":
            case "keyup":
                data.kc = {
                    k: event.key,
                    c: event.code
                }
                break
            case "scroll":
                data.sl = {
                    st: event.target.scrollTop,
                    sl: event.target.scrollLeft
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

    getSessionId() {
        let sessionId = localStorage.getItem(this.storageSessionName)
        if (!sessionId) {
            sessionId = Date.now().toString(36)
                + Math.random().toString(36).substr(2)
                + Math.random().toString(36).substr(2)
            localStorage.setItem(this.storageSessionName, sessionId)
        }
        return sessionId
    }

    removeSessionId(event) {
        localStorage.removeItem(this.storageSessionName)
    }
}

new TrackEvents()
