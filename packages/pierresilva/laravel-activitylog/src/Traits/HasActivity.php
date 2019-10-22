<?php

namespace pierresilva\Activitylog\Traits;

use pierresilva\Activitylog\ActivitylogServiceProvider;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasActivity
{
    use LogsActivity;

    public function actions(): MorphMany
    {
        return $this->morphMany(ActivitylogServiceProvider::determineActivityModel(), 'causer');
    }
}
