# NG Generators

Angular generators for Laravel Artisan.

### Installation

If you're using the starter project, then it's already pre-installed.

    composer require pierresilva/laravel-ng-generators

Then add the provider in ```config/app.php```
    
    pierresilva\NgGenerators\LaravelServiceProvider::class,
    
### Configuration
Publish the config file

    php artisan vendor:publish

### Usage

#### ng:module [NAME]

Create new module in ```resources/angular/app/modules```

__Arguments__:

* ```name``` - Module name. __Required__.

__Example__:

    php artisan ng:module module-name

#### ng:component [NAME] [MODULE]

Create new component in ```resources/angular/app/compoents```.

__Arguments__

* ```name (string)``` - Component name. __Required__.
* ```module (string)``` - Module name. __Optional__.

If module name is passed by argument create new component in ```resources/angular/app/modules/module-name/components```.

__Example__:

App component:

    php artisan ng:component componet-name
    
Module component:

    php artisan ng:component component-name module-name
    
## Tanks
[Jad Joubran](https://github.com/jadjoubran)

## License

This project is licensed under the MIT License - see the [License File](LICENSE) for details
