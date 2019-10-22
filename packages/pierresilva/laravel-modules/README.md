# Laravel Modules

Modules is a simple package to allow the means to separate your Laravel 5.6 application out into modules. Each module is completely self-contained allowing the ability to simply drop a module in for use.

The package follows the FIG standards PSR-1, PSR-2, and PSR-4 to ensure a high level of interoperability between shared PHP code.

## Quick Installation
Begin by installing the package through Composer.

```
composer require pierresilva/laravel-modules
```

Once this operation is complete, simply add both the service provider and facade classes to your project's `config/app.php` file:

#### Service Provider

```php
pierresilva\Modules\ModulesServiceProvider::class,
```

#### Facade

```php
'Module' => pierresilva\Modules\Facades\Module::class,
```

And that's it! With your coffee in reach, start building out some awesome modules!

### Module Structure

The package is built with Laravel 5 in mind. Modules follow the same app structure adopted with the latest version of Laravel, ensuring that modules feel like a natural part of your application.

```
laravel-project/
	app/
	|-- Modules/
		|-- Blog/
			|-- Console/
			|-- Database/
				|-- Migrations/
				|-- Seeds/
			|-- Http/
				|-- Controllers/
				|-- Middleware/
				|-- Requests/
				|-- routes.php
			|-- Providers/
				|-- BlogServiceProvider.php
				|-- RouteServiceProvider.php
			|-- Resources/
				|-- Lang/
				|-- Views/
			|-- module.json
```

### Manifest File

Along with the structure, every module has a module.json manifest file. This manifest file is used to outline information such as the description, version, author(s), and anything else you'd like to store pertaining to the module at hand.

```
{
	"name": "Blog",
	"slug": "blog",
	"version": "1.0",
	"author": "Author Name",
	"license": "MIT",
	"description": "Only the best blog module in the world!",
	"order": 100
}
```
   * name - A human-friendly name of the module. Not required.
   * slug - The slug of the module. This is used for identification purposes.
   * version - The module's version. Not required.
   * description - A description of the module. Not required.
   * author - The module's author name. Not required
   * license - The module's license. Not required
   * order - The order of which modules are loaded. This is optional, but if you have a requirement to load a module later this is the option you are looking for. Not required

## Configuration

Modules comes bundled with a config file that you may publish and customize.
Publishing The Config File

To publish the bundled config file, simply run Laravel's vendor:publish Artisan command:

```php artisan vendor:publish```

This will copy the bundled config file to ```config/modules.php```.

You can specify the group tag when publishing the config file using this command:

```php artisan vendor:publish --tag=config```

### Configuration Options
#### Path to Modules

You may define the path where you'd like to store your modules.

```'path' => app_path('Modules'),```

#### Module's Base Namespace

Define the base namespace for your modules.

```
'namespace' => 'App\Modules\\'
```
__Note__: Be sure to update this path if you move your modules to another directory.

#### To move modules folder

* Change configuration above.
* Add auto load namespace match folder in ```composer.json``` file. For example: ```"Modules\\": "modules/"```. This will be move modules folder to Laravel base path.
* Run command ```composer dump-autoload``` in where ```composer.json``` installed.

#### Restructure your modules

You can alter the internal structure of your modules without affecting the scaffolding and migration commands. For example, if you want to put your php code in a src directory and leave out the resources files you could do something like this:

```
'pathMap' => [
    'Database'          => 'src/Database',
    'Http'              => 'src/Http',
    'Providers'         => 'src/Providers',
    'Models'            => 'src/Models',
    'Policies'          => 'src/Policies',
    'Resources/Views'   => 'resources/views',
    'Resources/Lang'    => 'resources/lang',
    'Routes'            => 'routes',
],
```

## Resources

### Views

Module views are referenced using a double-colon module::view syntax. So you may load the admin view from the blog module like so:

```
Route::get('admin', function() {
    return view('blog::admin');
});
```

### Overriding Module Views

Modules registers two locations for your views for each module: one in the application's ```resources/views/vendor``` directory and one in your module's ```resources``` directory. So, using our blog example: when requesting a module view, Laravel will first check if a custom version of the view has been provided in ```resources/views/vendor/blog```. Then, if the view has not been customized, Laravel will search the module's ```view``` directory. This makes it easy for end-users to ```customize/override``` your module's views.

### Translations

Modules registers the ```Resources/Lang``` location for your translation files within each of your modules. Module translations are referenced using a double-colon ```module::file.line``` syntax. So, you may load the blog module's welcome line from the messages file like so:

```
echo trans('blog::messages.welcome');
```

## Public Assets

Just like packages for Laravel, your modules may have assets such as JavaScript, CSS, and images. To publish these assets to the application's ```public``` directory, use the service provider's ```publishes``` method. You may do this within your module's primary service provider, or create a service provider specifically for assets.

In this example, we'll be storing our assets in an Assets directory at the root of our module. We will also add a modules asset group tag, which may be used to publish groups of related assets:

```
/**
 * Preforms post-registration booting of services.
 *
 * @return void
 */
public function boot()
{
    $this->publishes([
        __DIR__.'/../Assets' => public_path('assets/modules/example'),
    ], 'modules');
}
```

Now, when you execute the vendor:publish command, your module's assets will be copied to the specified location. Since you typically will need to overwrite the assets every time the module is updated, you may use the --force flag:

```
php artisan vendor:publish --tag=modules --force
```

If you would like to make sure your public assets are always up-to-date, you can add this command to the ```post-update-cmd``` list in your ```composer.json``` file.

## Provided Middleware

Modules comes bundled with middleware that you may use within your application. Below you will find a description of each one with examples of their uses.

### Identify Module

The Identify Module middleware provides the means to pull and store module manifest information within the session on each page load. This provides the means to identify routes from specific modules.
Register

Simply register as a route middleware with a short-hand key in your ```app/Http/Kernel.php``` file.

```
protected $routeMiddleware = [
    ...
    'module' => \pierresilva\Modules\Middleware\IdentifyModule::class,
];
```

### Usage

Now, you may simply use the ```middleware``` key in the route options array. The ```IdentifyModule``` middleware expects the slug of the module to be passed along in order to locate and load the relevant manifest information.

```
Route::group(['prefix' => 'blog', 'middleware' => ['module:blog']], function() {
    Route::get('/', function() {
        dd(
            'This is the Blog module index page.',
            session()->all()
        );
    });
});
```

### Results

If you ```dd()``` your session, you'll see that you have a new ```module``` array key with your module's manifest information available.

```
"This is the Blog module index page."
array:2 [▼
  "_token" => "..."
  "module" => array:6 [▼
    "name" => "Blog"
    "slug" => "blog"
    "version" => "1.0"
    "description" => "This is the description for the Blog module."
    "enabled" => true
    "order" => 9001
  ]
]
```

## Composer Support

Bringing in Composer support for individual modules is simple through the use of [Wikimedia's Composer Merge Plugin](https://github.com/wikimedia/composer-merge-plugin).

### Installation

To get started, simply require the plugin through Composer for your application:

```
composer require wikimedia/composer-merge-plugin
```

### Usage

Simply add the required merge plugin's extra configuration to your application's composer.json file and point it to your module's directory. You only need to do this once:

```
"extra": {
    "merge-plugin": {
        "include": [
            "app/Modules/*/composer.json"
        ]
    }
}
```

Now, for every module that requires their own composer dependencies to be installed with your application, simply create a composer.json file at the root of your module:

```
{
    "name": "yourapplication/users",
    "description": "Yourapplication Users module.",
    "keywords": ["yourapplication", "module", "users"],
    "require": {
        "pierresilva/laravel-string": "~2.0"
    },
    "config": {
        "preferred-install": "dist"
    }
}
```

Then simply run ```composer update``` per normal! Wikimedia's composer merge plugin will automatically parse all of your modules ```composer.json``` files and merge them with your main ```composer.json``` file dynamically.

## Facade Reference

#### Module::all()

Get all modules.

__Returns__

```Collection```

Example

```$modules = Module::all();```

#### Module::slugs()

Get all module slugs.

__Returns__

```Collection```

__Example__

```$modules = Module::slugs();```

#### Module::where($key, $value)

Get modules based on where clause.

__Parameters__

* ```$key (string)``` Module property key. Required.
* ```value (mixed)``` Value to match. Required.

__Returns__

```Collection```

__Example__

```$blogModule = Module::where('slug', 'blog');```

#### Module::sortBy($key)

Sort modules by the given key in ascending order.

__Parameters__

* ```$key (string)``` Module property key. Required.

__Returns__

```Collection```

__Example__

```$orderedModules = Module::sortBy('order');```

#### Module::sortByDesc($key)

Sort modules by the given key in descending order.}

__Parameters__

* ```$key (string)``` Module property key. Required.

__Returns__

```Collection```

__Example__

```$orderedModules = Module::sortByDesc('order');```

#### Module::exists($slug)

Check if given module exists.

__Parameters__

* ```$slug (string)``` Module slug. Required.

__Returns__

```bool```

__Example__

```
if (Module::exists('blog')) {
    return 'Module "blog" exists!';
}
```

#### Module::count()

Returns a count of all modules.

__Returns__

```int```

__Example__
```
$moduleCount = Module::count();
```

#### Module::getManifest($slug)

Returns the modules defined properties.

__Parameters__

* ```$slug (string)``` Module slug. Required.

__Returns__

```Collection```

__Example__

```$moduleProperties = Module::getManifest('blog');```

#### Module::get($property, $default)

Returns the given module manifest property.

__Parameters__

* ```$property (string)``` Module property slug in the following format: moduleSlug::propertyKey. Required.
* ```$default (mixed)``` The default value if the defined property does not exist.

__Returns__

```mixed```

__Example__

```$moduleName = Module::get('blog::name', 'Blog');```

#### Module::set($property, $value)

Set the given module manifest property value.

__Parameters__

* ```$propertySlug (string)``` Module property slug in the following format: ```moduleSlug::propertyKey```. Required.
* ```$value (mixed)``` The new property value to be saved. Required

__Returns__

```bool```

__Example__

```Module::set('blog::description', 'This is a new description for the blog module.');```

#### Module::enabled()

Gets all enabled modules.

__Returns__

```Collection```

__Example__

```$enabledModules = Module::enabled();```

#### Module::disabled()

Gets all disabled modules.

__Returns__

```Collection```

__Example__

```$disabledModules = Module::disabled();```

#### Module::isEnabled($slug)

Checks if specified module is enabled.

__Parameters__

* ```$slug (string)``` Module slug. Required.

__Returns__

```bool```

__Example__

```
if (Module::isEnabled('blog')) {
	return 'Blog module is enabled!';
}
```

#### Module::isDisabled($slug)

Checks if specified module is disabled.

__Parameters__

* ```$slug (string)``` Module slug. Required.

__Returns__

```bool```

__Example__

```
if (Module::isDisabled('blog')) {
	return 'Blog module is disabled.';
}
```

#### Module::enable($slug)

Enable the specified module.

__Parameters__

* ```$slug (string)``` Module slug. Required.

__Returns__

```bool```

__Example__

```Module::enable('blog');```

#### Module::disable($slug)

Disable the specified module.

__Parameters__

* ```$slug (string)``` Module slug. Required

__Returns__

```bool```

__Example__

```Module::disable('blog');```

## Artisan Commands

Modules package comes with a handful of Artisan commands to make generating and managing modules easy.

### Generators

#### make:module [slug]

Generate a new module. This will generate all the necessary folders and files needed to bootstrap your new module. The new module will be automatically enabled and work out of the box.

__Arguments__

* ```[SLUG] - Module slug```
* `--angular` - Create angular module to. __Optional__

__Example__

```php artisan make:module my-module --angular```

#### make:module:controller [slug] [name]

Create a new module controller class.

__Arguments__

* ```[SLUG]``` - Module slug
* ```[NAME]``` - The name of the class

__Example__

```php artisan make:module:controller blog PostController```

#### make:module:model [slug] [name]

Create a new module model class.

__Arguments__

* ```[SLUG]``` - Module slug.
* ```[NAME]``` - The name of the class.

__Example__

```php artisan make:module:model blog Post```

#### make:module:migration [slug] [table]

Create a new module migration file.

__Arguments__

* ```[SLUG]``` - Module slug
* ```[TABLE]``` - Table to be created by migration file

__Example__

```php artisan make:module:migration blog create_posts_table```

#### make:module:request [slug] [name]

Create a new module form request class.

__Arguments__

* ```[SLUG]``` - Module slug
* ```[NAME]``` - The name of the class

__Example__

```php artisan make:module:request blog CreatePostRequest```

#### make:module:test [slug] [name]

Create a new module test class.

__Arguments__

* ```[SLUG]``` - Module slug
* ```[NAME]``` - The name of the test class

__Example__

```php artisan make:module:test blog CommentsTest```

__Run tests__

To make the phpunit command run tests inside modules, it is needed to update ```/phpunit.xml```:

```
<testsuites>
        <testsuite name="Application Test Suite">
            <directory suffix="Test.php">./tests</directory>
            <directory suffix="Test.php">./app/Modules</directory>
        </testsuite>
</testsuites>
```

### Module Specific

#### module:disable [MODULE]

Disable a module. Disabling a module ensures it is not loaded during the boot process of your application.

__Arguments__

* ```[MODULE]``` - Module slug

__Example__

```php artisan module:disable blog```

#### module:enable [MODULE]

Enable a module.

__Arguments__

* ```[MODULE]``` - Module slug

__Example__

```php artisan module:enable blog```

#### module:list

List all application modules.

__Example__

```php artisan module:list```

#### module:migrate [MODULE]

Migrate the migrations from the specified module or from all modules.

__Arguments__

* ```[MODULE]``` - Module slug (optional) If not provided all module migrations will be ran.

__Parameters__

* ```--database``` - The database connection to use.
* ```--pretend``` - Dump the SQL queries that would be run.
* ```--seed``` - Indicates if the seed task should be re-run.

__Example__

```php artisan module:migrate```

```php artisan module:migrate blog --pretend```

#### module:migrate:refresh [MODULE]

Reset and re-run all migrations for a specific or all modules.

__Arguments__

* ```[MODULE]``` - Module slug (optional) If not provided all modules will be refreshed.

__Parameters__

* ```--database``` - The database connection to use.
* ```--seed``` - Indicates if the seed task should be re-run.

__Example__

```php artisan module:migrate:refresh```

```php artisan module:migrate:refresh blog --seed```

#### module:migrate:reset [MODULE]

Rollback all database migrations for a specific or all modules.

__Arguments__

* ```[MODULE]``` - Module slug (optional) If not provided all module migrations will be reset.

__Parameters__

* ```--database``` - The database connection to use.
* ```--force``` - Force the operation to run while in production.
* ```--pretend``` - Dump the SQL queries that would be run.

__Example__

```php artisan module:migrate:reset```

```php artisan module:migrate:reset blog```

#### module:migrate:rollback [MODULE]

Rollback the last database migrations for a specific or all modules.

__Arguments__

* ```[MODULE]``` - Module slug (optional) If not provided all module migrations will be rolled back.

__Parameters__

* ```--database``` - The database connection to use.
* ```--force``` - Force the operation to run while in production.
* ```--pretend``` - Dump the SQL queries that would be run.

__Example__

```php artisan module:migrate:rollback```

```php artisan module:migrate:rollback blog```

#### module:seed [MODULE]

Seed the database with records for a specific or all modules.

__Arguments__

* ```[MODULE]``` - Module slug (optional) If not provided all module seeds will be ran.

__Parameters__

* ```--class``` - The class name of the module's root seeder.
* ```--database``` - The database connection to seed.

__Example__

```php artisan module:seed```

```php artisan module:seed blog```

## Tanks
[Cafffeinated](https://github.com/caffeinated)

## License

This project is licensed under the MIT License - see the [License File](LICENSE) for details
