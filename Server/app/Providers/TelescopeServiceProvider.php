<?php
declare(strict_types=1);

namespace App\Providers;

use App\Helpers\GeoHelper;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

/**
 * Class TelescopeServiceProvider
 *
 * @category Providers
 * @package  App\Providers

 */
class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Telescope::night();
        $this->hideSensitiveRequestDetails();

        Telescope::filter(
            function (IncomingEntry $entry) {
                if ($this->app->environment('local')) {
                    return true;
                }

                return $entry->isReportableException() ||
                $entry->isFailedRequest() ||
                $entry->isFailedJob() ||
                $entry->isScheduledTask() ||
                $entry->hasMonitoredTag();
            }
        );

    }//end register()


    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders(
            [
                'cookie',
                'x-csrf-token',
                'x-xsrf-token',
            ]
        );

    }//end hideSensitiveRequestDetails()


    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    protected function gate(): void
    {
        Gate::define(
            'viewTelescope',
            function ($user) {
                return in_array(
                    $user->email,
                    []
                );
            }
        );

    }//end gate()


}//end class
