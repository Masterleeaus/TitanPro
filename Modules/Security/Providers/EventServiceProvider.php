<?php

namespace Modules\Security\Providers;

use App\Events\NewCompanyCreatedEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Security\Entities\Security;
use Modules\Security\Entities\TrInOutPermit;
use Modules\Security\Entities\WorkPermits;
use Modules\Security\Entities\WorkPermitsFile;
use Modules\Security\Entities\TrAccessCard;
use Modules\Security\Entities\CardItems;
use Modules\Security\Listeners\CompanyCreatedListener;
use Modules\Security\Listeners\TrAccessCardCompanyCreatedListener;
use Modules\Security\Listeners\TrInOutPermitCompanyCreatedListener;
use Modules\Security\Listeners\TrWorkPermitsCompanyCreatedListener;
use Modules\Security\Observers\SecurityObserver;
use Modules\Security\Observers\TrInOutPermitObserver;
use Modules\Security\Observers\WorkPermitsObserver;
use Modules\Security\Observers\WorkPermitsFileObserver;
use Modules\Security\Observers\CardObserver;
use Modules\Security\Observers\CardItemsObserver;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NewCompanyCreatedEvent::class => [
            CompanyCreatedListener::class,
            TrInOutPermitCompanyCreatedListener::class,
            TrWorkPermitsCompanyCreatedListener::class,
            TrAccessCardCompanyCreatedListener::class,
        ],
    ];

    protected $observers = [
        Security::class => [SecurityObserver::class],
        TrInOutPermit::class => [TrInOutPermitObserver::class],
        WorkPermits::class => [WorkPermitsObserver::class],
        WorkPermitsFile::class => [WorkPermitsFileObserver::class],
        TrAccessCard::class => [CardObserver::class],
        CardItems::class => [CardItemsObserver::class],
    ];

    public function boot()
    {
        parent::boot();

        foreach ($this->observers as $model => $observers) {
            foreach ((array) $observers as $observer) {
                if (class_exists($model) && class_exists($observer) && method_exists($model, 'observe')) {
                    $model::observe($observer);
                }
            }
        }
    }

}
