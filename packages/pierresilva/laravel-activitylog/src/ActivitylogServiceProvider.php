<?php

namespace pierresilva\Activitylog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use pierresilva\Activitylog\Models\Activity;
use pierresilva\Activitylog\Exceptions\InvalidConfiguration;

class ActivitylogServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/activitylog.php' => config_path('activitylog.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../config/activitylog.php', 'activitylog');

        if (!class_exists('CreateActivityLogTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__ . '/../migrations/create_activity_log_table.php.stub' => database_path("/migrations/{$timestamp}_create_activity_log_table.php"),
            ], 'migrations');
        }

        if (!file_exists(app_path('Traits/ActivityLogTrait'))) {
            $this->publishes([
                __DIR__ . '/../traits/ActivityLogTrait.stub' => app_path('/Traits/ActivityLogTrait.php'),
            ]);
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->commands(
            'pierresilva\Activitylog\Commands\ActivitylogCleanCommand'
        );
    }

    /**
     * @return string
     * @throws InvalidConfiguration
     */
    public static function determineActivityModel(): string
    {
        $activityModel = config('activitylog.activity_model') ?? Activity::class;

        if (!is_a($activityModel, Activity::class, true)) {
            throw InvalidConfiguration::modelIsNotValid($activityModel);
        }

        return $activityModel;
    }

    /**
     * @return Model
     * @throws InvalidConfiguration
     */
    public static function getActivityModelInstance(): Model
    {
        $activityModelClassName = self::determineActivityModel();

        return new $activityModelClassName();
    }
}
