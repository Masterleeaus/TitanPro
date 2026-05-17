<?php

namespace App\Providers;

use App\Console\Commands\TitanTokensExportCommand;
use App\Events\JobCreated;
use App\Events\JobStatusChanged;
use App\Listeners\AlertOnFailedMailJob;
use App\Listeners\SendJobConfirmationEmail;
use App\Listeners\SendJobConfirmationSms;
use App\Listeners\SendJobStatusMessages;
use App\Models\User;
use App\Policies\LayoutPolicy;
use App\Services\GeocodingService;
use App\Services\MessageDispatcher;
use App\Services\SmsService;
use App\Services\TemplateRenderer;
use App\Services\TwilioSmsService;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use LaraZeus\DynamicDashboard\Models\Layout;
use TomatoPHP\FilamentCms\Facades\FilamentCMS;
use TomatoPHP\FilamentCms\Services\Contracts\CmsType;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SmsService::class, TwilioSmsService::class);

        $this->app->singleton(GeocodingService::class, fn () => new GeocodingService(config('services.google.maps_api_key', ''))
        );

        $this->app->singleton(MessageDispatcher::class, fn ($app) => new MessageDispatcher($app->make(SmsService::class))
        );

        $this->app->singleton(TemplateRenderer::class);
    }

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        if ($this->app->runningInConsole()) {
            $this->commands([
                TitanTokensExportCommand::class,
            ]);
        }

        // Bind the DynamicDashboard Layout model to our LayoutPolicy so that
        // FilamentShield permission checks work for non-super_admin roles.
        if (class_exists(Layout::class)) {
            Gate::policy(
                Layout::class,
                LayoutPolicy::class,
            );
        }

        // titan.admin gate — grants access to module administration routes.
        // Only the platform super_admin role may manage modules from the admin UI.
        Gate::define('titan.admin', function (User $user): bool {
            return $user->hasRole('super_admin');
        });

        Event::listen(JobCreated::class, SendJobConfirmationEmail::class);
        Event::listen(JobCreated::class, SendJobConfirmationSms::class);
        Event::listen(JobStatusChanged::class, SendJobStatusMessages::class);
        Event::listen(JobFailed::class, AlertOnFailedMailJob::class);

        if (class_exists(FilamentCMS::class)
            && class_exists(CmsType::class)) {
            FilamentCMS::types()->register([
                CmsType::make('page')
                    ->label('Pages')
                    ->icon('heroicon-o-document-text')
                    ->color('primary'),
                CmsType::make('landing')
                    ->label('Landing Pages')
                    ->icon('heroicon-o-rocket-launch')
                    ->color('success'),
                CmsType::make('block')
                    ->label('Blocks')
                    ->icon('heroicon-o-square-3-stack-3d')
                    ->color('warning'),
            ]);
        }
    }
}
