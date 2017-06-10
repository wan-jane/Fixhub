<?php

/*
 * This file is part of Fixhub.
 *
 * Copyright (C) 2016 Fixhub.org
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fixhub\Http;

use Fixhub\Bootstrap\ConfigureLogging;
use Fixhub\Bootstrap\ConfigureLogging as HttpLogging;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Kernel class.
 */
class Kernel extends HttpKernel
{
    /**
     * The custom bootstrappers like Logging or Environment detector.
     * @var array
     */
    protected $customBooters = [
        \Illuminate\Foundation\Bootstrap\ConfigureLogging::class => HttpLogging::class,
    ];

    /**
     * Disable bootstrapper list.
     * @var array
     */
    protected $disabledBooters = [

    ];

    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Fideloper\Proxy\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Fixhub\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Fixhub\Http\Middleware\VerifyCsrfToken::class,
            \Fixhub\Http\Middleware\Localize::class,
        ],
        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin'      => \Fixhub\Http\Middleware\Admin::class,
        'auth'       => \Fixhub\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest'      => \Fixhub\Http\Middleware\RedirectIfAuthenticated::class,
        'jwt'        => \Fixhub\Http\Middleware\RefreshJsonWebToken::class,
        'throttle'   => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'localize'   => \Fixhub\Http\Middleware\Localize::class,
    ];

    /**
     * Get the bootstrap classes for the application.
     *
     * @return array
     */
    protected function bootstrappers()
    {
        foreach ($this->bootstrappers as &$bootstrapper) {
            foreach ($this->customBooters as $sourceBooter => $newBooter) {
                if ($bootstrapper === $sourceBooter) {
                    $bootstrapper = $newBooter;
                    unset($this->customBooters[$sourceBooter]);
                }
            }
        }

        return array_merge(
            array_diff(
                $this->bootstrappers,
                $this->disabledBooters
            ),
            $this->customBooters
        );
    }
}
