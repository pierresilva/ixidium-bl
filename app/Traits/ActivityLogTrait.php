<?php

namespace App\Traits;

use App\LogModel;
use pierresilva\Activitylog\Traits\LogsActivity;

/**
 * Trait ActivityLogTrait
 */
trait ActivityLogTrait
{

  use LogsActivity;

  protected static $logAttributes = ['*'];

  /**
   * Log activity generic description
   *
   * @param string $eventName
   * @return string
   */
  public function getDescriptionForEvent(string $eventName): string
  {
    $event = '';

    switch ($eventName) {

      case 'updated' :
        $event = 'actualizado';
        break;
      case 'deleted' :
        $event = 'eliminado';
        break;
      case 'created':
        $event = 'creado';
        break;
      case 'restored':
        $event = 'restaurado';
        break;
      default:
        $event = 'modificado';
        break;
    }

    return $event;
  }

  public static function boot()
  {
    parent::boot();

    $modelToLog = LogModel::where('fqn', '=', self::$fqn)->first();

    if (!$modelToLog) {
      self::$enableLoggingModelsEvents = false;
    }

    self::creating(function ($model) {
      //
    });
    self::updating(function ($model) {
      //
    });
    self::deleting(function ($model) {
      //
    });
  }

}
