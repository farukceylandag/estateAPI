<?php

namespace App\Providers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;

use App\Repositories\Interfaces\ContactRepositoryInterface;
use App\Repositories\ContactRepository;

use App\Repositories\Interfaces\AppointmentRepositoryInterface;
use App\Repositories\AppointmentRepository;


use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            AppointmentRepositoryInterface::class,
            AppointmentRepository::class
        );

        $this->app->bind(
            ContactRepositoryInterface::class,
            ContactRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}