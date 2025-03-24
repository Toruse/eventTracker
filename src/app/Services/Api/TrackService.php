<?php

namespace App\Services\Api;

class TrackService
{
    public $validationOnly = ['ht', 'hf', 'vi', 'tp', 'tm', 'xp', 'ti', 'ms', 'br', 'dt'];

    public $validationRules = [
        'ht' => 'nullable|string|max:256',
        'hf' => 'nullable|url',
        'vi' => 'nullable|string|max:256',
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
}
