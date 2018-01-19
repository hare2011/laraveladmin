<?php

namespace Runhare\Admin\Facades;

use Illuminate\Support\Facades\Facade;

class Admin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Runhare\Admin\Admin::class;
    }
}
