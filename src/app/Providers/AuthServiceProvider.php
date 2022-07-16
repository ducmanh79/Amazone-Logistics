<?php

namespace App\Providers;

use App\Models\CarRental;
use App\Models\CostOfCar;
use App\Models\User;
use App\Policies\CarPolicy;
use App\Policies\CarRentalPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\GoodsPolicy;
use App\Policies\OrderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => EmployeePolicy::class,
        CarRental::class => CarRentalPolicy::class,
        Car::class => CarPolicy::class,
        Order::class => OrderPolicy::class,
        Goods::class => GoodsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
