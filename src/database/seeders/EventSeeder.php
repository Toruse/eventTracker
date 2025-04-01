<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            ['name' => 'blur'],
            ['name' => 'click'],
            ['name' => 'contextmenu'],
            ['name' => 'change'],
            ['name' => 'copy'],
            ['name' => 'cut'],
            ['name' => 'dblclick'],
            ['name' => 'focus'],
            ['name' => 'keyup'],
            ['name' => 'mouseenter'],
            ['name' => 'mouseleave'],
            ['name' => 'mousemove'],
            ['name' => 'paste'],
            ['name' => 'wheel'],
            ['name' => 'dragenter'],
            ['name' => 'dragleave'],
            ['name' => 'dragstart'],
            ['name' => 'dragend'],
            ['name' => 'drop']
        ]);
    }
}
