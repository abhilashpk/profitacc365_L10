<?php
namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Input extends Facade {
    protected static function getFacadeAccessor() { return 'app.support.input'; }
}

