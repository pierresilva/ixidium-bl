<?php

namespace App;

use App\Notifications\ResetPasswordNotification;
use pierresilva\Activitylog\Traits\CausesActivity;
use pierresilva\Sentinel\Traits\SentinelTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $status
 * @property string|null $remember_token
 * @property string $expire_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\pierresilva\Sentinel\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\pierresilva\Sentinel\Models\Role[] $roles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereExpireAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
  use Notifiable, SentinelTrait, CausesActivity;

  // protected $connection = 'sqlsrv';

  protected $table = 'users';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'remember_token',
    'status',
    'expire_at',
    'profiles',
    'actions',
    'ts_id',
    'first_name',
    'last_name',
    'identification'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [

    'password', 'remember_token', 'permissions', 'roles'
  ];

  protected $attributes = [
    //'user_roles',
    //'user_permissions',

  ];

  /**
   * Get the identifier that will be stored in the subject claim of the JWT
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [
      // 'permissions' => $this->getPermissions(),
      // 'roles' => $this->getRoles(),
      // 'profiles' => $this->profiles,
      // 'actions' => $this->actions,
      'first_name' => $this->first_name,
      'last_name' => $this->last_name,
      'name' => $this->name,
      'identification' => $this->identification,
      'id' => $this->ts_id,
      'user' => $this->name
    ];
  }

  /**
   * Send the password reset notification.
   *
   * @param  string  $token
   * @return void
   */
  public function sendPasswordResetNotification($token)
  {
    $this->notify(new ResetPasswordNotification($token));
  }

  function scopeHasRole($query, $roleSlug)
  {
    return $query->whereHas('roles', function ($query) use ($roleSlug) {
      $query->where('slug', 'LIKE', '%' . $roleSlug . '%');
    });
  }
}
