<?php

namespace Runhare\Admin\Middleware;

use Runhare\Admin\Form;
use Runhare\Admin\Grid;
use Illuminate\Http\Request;

class BootstrapMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        Form::registerBuiltinFields();

        if (file_exists($bootstrap = admin_path('bootstrap.php'))) {
            require $bootstrap;
        }

        Form::collectFieldAssets();

        Grid::registerColumnDisplayer();

        return $next($request);
    }
}
