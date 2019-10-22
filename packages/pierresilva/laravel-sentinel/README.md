Sentinel
===================

Sentinel brings a simple and light-weight role-based permissions system to Laravel's built in Auth system. Sentinel brings support for the following ACL structure:

- Every user can have zero or more roles.
- Every role can have zero or more permissions.

Permissions are then inherited to the user through the user's assigned roles.

The package follows the FIG standards PSR-1, PSR-2, and PSR-4 to ensure a high level of interoperability between shared PHP code. At the moment the package is not unit tested, but is planned to be covered later down the road.

Quick Installation
------------------
Begin by installing the package through Composer. The best way to do this is through your terminal via Composer itself:

```
composer require pierresilva/sentinel
```

Once this operation is complete, simply add the service provider to your project's `config/app.php` file and run the provided migrations against your database.

### Service Provider
```php
pierresilva\Sentinel\SentinelServiceProvider::class
```

### Middleware
Sentinel provides middleware that you may assign to specific routes in your application. To register, simply append the following middleware to your app/Http/Kernel.php file under the $routeMiddleware property.

```php
'has.role' => \pierresilva\Sentinel\Middleware\UserHasRole::class,
'has.permission' => \pierresilva\Sentinel\Middleware\UserHasPermission::class,
```

### Migrations
You'll need to run the provided migrations against your database. Publish the migration files using the `vendor:publish` Artisan command and run `migrate`:

```
php artisan vendor:publish --provider="pierresilva\Sentinel\SentinelServiceProvider"
php artisan migrate
```

## Usage

Sentinel package comes bundled with a ```SentinelTrait``` file to be used within your User's Model file. This trait file provides all the necessary functions to tie your users in with roles and permissions.

__Example User Model__

```php
<?php

namespace App;

use pierresilva\Sentinel\Traits\SentinelTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SentinelTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
```

## Sentinel Trait

#### isRole($roleSlug)
Checks if the user is under the given role.

```
Auth::user()->isRole('administrator');
```

You may also use magic methods:

```
Auth::user()->isAdministrator();
```

#### can($permission)
Checks if the user has the given permission(s). You may pass either a string or an array of permissions to check for. In the case of an array, ALL permissions must be accountable for in order for this to return true.

```
Auth::user()->can('access.admin');
```

or

```
Auth::user()->can(['access.admin', 'view.users']);
```

#### assignRole($roleId)
Assign the given role to the user.
```
Auth::user()->assignRole(1);
```

#### revokeRole($roleId)
Revokes the given role from the user.

```
Auth::user()->revokeRole(1);
```

#### revokeAllRoles()
Revokes all roles from the user.

```
Auth::user()->revokeAllRoles();
```

#### syncRoles([$roleIds])
Syncs the given roles with the user. This will revoke any roles not supplied.

```
Auth::user()->syncRoles([1, 2, 3]);
```

## Role Permissions

#### can($permission)
Checks if the role has the given permission(s). You may pass either a string or an array of permissions to check for. In the case of an array, ALL permissions must be accounted for in order for this to return true.

```
$role = Role::find(1);
return $role->can('access.admin');
```

#### getPermissions()
Retrieves an array of assigned permission slugs for the role.

```
$role = Role::find(1);
return $role->getPermissions();
```

#### assignPermission($permissionId)
Assigns the given permission to the role.

```
$role = Role::find(1);
$role->assignPermission(1);
$role->save();
```

#### revokePermission($permissionId)
Revokes the given permission from the role.

```
$role = Role::find(1);
$role->revokePermission(1);
$role->save();
```

#### revokeAllPermissions()
Revokes all permissions from the role.

```
$role = Role::find(1);
$role->revokeAllPermissions();
$role->save();
```

#### syncPermissions([$permissionIds])
Syncs the given permissions with the role. This will revoke any permissions not supplied.

```
$role = Role::find(1);
$role->syncPermissions([1, 2, 3]);
$role->save();
```

## Facade Reference

#### Sentinel::can($permissions)
Checks if the current user can perform the provided permissions.

__Parameters__

* ```$permissions (array or string)``` - Permission(s) to check for. Required.

__Returns__

```Boolean```

__Example__

```
if (Sentinel::can('create.blog.post')) {
    // Do whatever
}
if (Sentinel::can(['access.admin', 'create.blog.post'])) {
    // Do whatever
}
```

#### Sentinel::canAtLeast($permissions)
Checks if the current user can at least perform one of the provided permissions.

__Parameters__

* ```$permissions (array)``` - Permissions to check for. Required.

__Returns__

```Boolean```

__Example__

```
if (Sentinel::canAtLeast(['edit.blog.post', 'create.blog.post'])) {
    // Can at least do one of the tasks
}
```

#### Sentinel::isRole($role)
Checks if the current user has the given role.

__Parameters__

* ```$role (string)``` - Role (slug) to check for. Required.

__Returns__

```Boolean```

__Example__

```
if (Sentinel::isRole('admin')) {
    // Do whatever
}
```

## Template Helpers

Sentinel comes bundled with custom template methods to easily check for permissions and roles from within your view files.

#### can($permissions)
```
@can('create.blog.post')
    Do whatever
@else
    Do something else
@endcan
```

#### canatleast($permissions)
```
@canatleast(['edit.blog.post', 'create.blog.post'])
    Do whatever
@else
    Do something else
@endcanatleast
```

#### role($role)
```
@role('admin')
    Do whatever
@else
    Do something else
@endrole
```

## Tanks
[Cafffeinated](https://github.com/caffeinated)

## License

This project is licensed under the MIT License - see the [License File](LICENSE) for details
