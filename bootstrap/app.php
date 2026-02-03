<?php
namespace App\Http\Middleware;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DriverMiddleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->group('localization', [
            LaravelLocalizationRoutes::class,
            LocaleSessionRedirect::class,
            LaravelLocalizationRedirectFilter::class,
            LaravelLocalizationViewPath::class,
        ]);
        $middleware->alias([
            'admin'  => AdminMiddleware::class,
            'driver' => DriverMiddleware::class,
            'role'   => RoleMiddleware::class,

        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
