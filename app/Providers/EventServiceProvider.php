<?php
namespace App\Providers;

use App\Events\MessageSent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MessageSent::class => [
            // No listeners are set
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
