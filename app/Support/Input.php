<?php
namespace App\Support;

class Input {
    public static function get($key = null, $default = null) { return request()->input($key, $default); }
    public static function all()  { return request()->all(); }
    public static function except($keys) { return request()->except($keys); }
    public static function only($keys) { return request()->only($keys); }
    public static function has($keys) { return request()->has($keys); }
    public static function flash() { request()->flash(); }
}

