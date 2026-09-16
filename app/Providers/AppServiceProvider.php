<?php

namespace App\Providers;

use App\Events\RequestCreated;
use App\Listeners\NotifyWorkers;
use App\Listeners\ScheduleExpiration;
use App\Service\V1\Payments\Contracts\CustomerGatewayInterface;
use App\Service\V1\Payments\Contracts\PaymentGatewayInterface;
use App\Service\V1\Payments\Gateways\Asaas\AsaasCustomerGateway;
use App\Service\V1\Payments\Gateways\Asaas\AsaasPaymentGateway;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CustomerGatewayInterface::class, AsaasCustomerGateway::class);
        $this->app->bind(PaymentGatewayInterface::class, AsaasPaymentGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
