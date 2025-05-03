<?php

namespace App\Services\Api;

use hisorange\BrowserDetect\Parser as Browser;

class TrackService
{
    public $validationOnly = ['id', 'ht', 'hf', 'vi', 'si', 'tp', 'tm', 'xp', 'ti', 'ms', 'br', 'dt'];

    public $validationRules = [
        'id' => 'nullable|string|max:128',
        'ht' => 'nullable|string|max:256',
        'hf' => 'nullable|url',
        'vi' => 'nullable|string|max:256',
        'si' => 'nullable|string|max:128',
        'tp' => 'nullable|string|in:blur,click,contextmenu,change,copy,cut,dblclick,focus,keyup,mouseenter,mouseleave,mousemove,paste,wheel,dragenter,dragleave,dragstart,dragend,drop',
        'tm' => 'nullable|integer',
        'xp' => 'nullable|string',
        'ti' => 'nullable|string|max:256',
        'ms' => 'nullable|array:x,y',
        'ms.x' => 'nullable|numeric',
        'ms.y' => 'nullable|numeric',
        'br' => 'nullable|array:h,w',
        'br.h' => 'nullable|integer',
        'br.w' => 'nullable|integer',
        'dt' => 'nullable|array:pg,ms,c,in,kc,sl',
        'dt.pg' => 'nullable|array:h,w',
        'dt.pg.h' => 'nullable|integer',
        'dt.pg.w' => 'nullable|integer',
        'dt.ms' => 'nullable|array:ox,oy,sx,sy',
        'dt.ms.ox' => 'nullable|integer',
        'dt.ms.oy' => 'nullable|integer',
        'dt.ms.sx' => 'nullable|integer',
        'dt.ms.sy' => 'nullable|integer',
        'dt.c' => 'nullable|string',
        'dt.in' => 'nullable|array:v',
        'dt.in.v' => 'nullable|string',
        'dt.kc' => 'nullable|array:k,c',
        'dt.kc.k' => 'nullable|string|max:32',
        'dt.kc.c' => 'nullable|string|max:32',
        'dt.sl' => 'nullable|array:st,sl',
        'dt.sl.st' => 'nullable|integer',
        'dt.sl.sl' => 'nullable|integer',
    ];

    public function getVisitorInfo() {
        $geoIPData = geoip(request()->ip());
        return [
            'locale' => \Locale::acceptFromHttp(request()->server('HTTP_ACCEPT_LANGUAGE')),
            'ip_address' => request()->ip(),
            'browser_type' => Browser::deviceType(),
            'browser_family' => Browser::browserFamily(),
            'browser_version' => Browser::browserVersion(),
            'browser_engine' => Browser::browserEngine(),
            'platform_family' => Browser::platformFamily(),
            'platform_version' => Browser::platformVersion(),
            'device_family' => Browser::deviceFamily(),
            'device_model' => Browser::deviceModel(),
            'city' => $geoIPData->city,
            'continent' => $geoIPData->continent,
            'country' => $geoIPData->country,
            'currency' => $geoIPData->currency,
            'iso_code' => $geoIPData->iso_code,
            'postal_code' => $geoIPData->postal_code,
            'timezone' => $geoIPData->timezone
        ];
    }
}
