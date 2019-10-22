# Laravel CRUD Generator

This Generator package provides various generators like CRUD, API, Controller, Model, Migration, View for your painless development of your applications.

## Requirements
    Laravel >=5.1
    PHP >= 5.5.9

## Installation

To get started, you should add the `pierresilva/laravel-crud-generator` Composer dependency to your project:
```
composer require pierresilva/laravel-crud-generator --dev
```
Once the package is installed, you should register the `pierresilva\CrudGenerator\CrudGeneratorServiceProvider` service provider. Normally, Laravel 5.5+ will register the service provider automatically.

After that, publish its assets using the `vendor:publish` Artisan command:
```
php artisan vendor:publish --provider="pierresilva\CrudGenerator\CrudGeneratorServiceProvider"
```

### Laravel older 5.5

If you're using an older verson of Laravel (<5.5) then just manually add the provider to `app/Providers/AppServiceProvider.php` file.

```php
public function register()
{
    if ($this->app->environment() == 'local') {
        $this->app->register('pierresilva\CrudGenerator\CrudGeneratorServiceProvider');
    }
}
```

And since, we're using `laravelcollective/html` as dependency you should add its service provider in the `config/app.php` file. Check the [docs](https://laravelcollective.com/docs/master/html) for details.

```php
'providers' => [
    //...

    Collective\Html\HtmlServiceProvider::class,
],

'aliases' => [
    //...

    'Form' => Collective\Html\FormFacade::class,
    'HTML' => Collective\Html\HtmlFacade::class,
],
```

## Usage

### CRUD Command

```
php artisan crud:generate Posts --fields='title#string; content#text; category#select#options={"technology": "Technology", "tips": "Tips", "health": "Health"}' --view-path=admin --controller-namespace=Admin --route-group=admin --form-helper=html
```

#### CRUD fields from a JSON file:

```json
{
    "fields": [
        {
            "name": "title",
            "type": "string"
        },
        {
            "name": "content",
            "type": "text"
        },
        {
            "name": "category",
            "type": "select",
            "options": {
                "technology": "Technology",
                "tips": "Tips",
                "health": "Health"
            }
        },
        {
            "name": "user_id",
            "type": "integer#unsigned"
        }
    ],
    "foreign_keys": [
        {
            "column": "user_id",
            "references": "id",
            "on": "users",
            "onDelete": "cascade"
        }
    ],
    "relationships": [
        {
            "name": "user",
            "type": "belongsTo",
            "class": "App\\User"
        }
    ],
    "validations": [
        {
            "field": "title",
            "rules": "required|max:10"
        }
    ]
}
```

```
php artisan crud:generate Posts --fields_from_file="/path/to/fields.json" --view-path=admin --controller-namespace=Admin --route-group=admin --form-helper=html
```

### Other Commands

For controller:

```
php artisan crud:controller PostsController --crud-name=posts --model-name=Post --view-path="directory" --route-group=admin
```

For model:

```
php artisan crud:model Post --fillable="['title', 'body']"
```

For migration:

```
php artisan crud:migration posts --schema="title#string; body#text"
```

For view:

```
php artisan crud:view posts --fields="title#string; body#text" --view-path="directory" --route-group=admin --form-helper=html
```

By default, the generator will attempt to append the crud route to your ```Route``` file. If you don't want the route added, you can use this option ```--route=no```.

After creating all resources, run migrate command. *If necessary, include the route for your crud as well.*

```
php artisan migrate
```

If you chose not to add the crud route in automatically (see above), you will need to include the route manually.
```php
Route::resource('posts', 'PostsController');
```

### API Commands

For api crud:

```
php artisan crud:api Posts --fields='title#string; content#text' --controller-namespace=Api
```

For api controller:

```
php artisan crud:api-controller Api\\PostsController --crud-name=posts --model-name=Post
```
## Options

### CRUD Options:

| Option    | Description |
| ---       | ---     |
| `--fields` | The field names for the form. e.g. ```--fields='title#string; content#text; category#select#options={"technology": "Technology", "tips": "Tips", "health": "Health"}; user_id#integer#unsigned'``` |
| `--fields_from_file` | Fields from a JSON file. e.g. `--fields_from_file="/path/to/fields.json"` |
| `--validations` | Validation rules for the fields "col_name#rules_set" e.g. ` "title#min:10|max:30|required" ` - See https://laravel.com/docs/master/validation#available-validation-rules |
| `--controller-namespace` | The namespace of the controller - sub directories will be created |
| `--model-namespace` | The namespace that the model will be placed in - directories will be created |
| `--pk` | The name of the primary key |
| `--pagination` | The amount of models per page for index pages |
| `--indexes` | The fields to add an index to. append "#unique" to a field name to add a unique index. Create composite fields by separating fieldnames with a pipe (` --indexes="title,field1|field2#unique" ` will create normal index on title, and unique composite on fld1 and fld2) |
| `--foreign-keys` | Any foreign keys for the table. e.g. `--foreign-keys="user_id#id#users#cascade"` where user_id is the column name, id is the name of the field on the foreign table, users is the name of the foreign table, and cascade is the operation 'ON DELETE' together with 'ON UPDATE' |
| `--relationships` | The relationships for the model. e.g. `--relationships="comments#hasMany#App\Comment"` in the format |
| `--route` | Include Crud route to routes.php? yes or no |
| `--route-group` | Prefix of the route group |
| `--view-path` | The name of the view path |
| `--form-helper` | Helper for the form. eg. `--form-helper=html`, `--form-helper=laravelcollective` |
| `--localize` | Allow to localize. e.g. localize=yes  |
| `--locales`  | Locales language type. e.g. locals=en |
| `--soft-deletes` | Include soft deletes fields. eg. `--soft-deletes=yes` |


### Controller Options:

| Option    | Description |
| ---       | ---     |
| `--crud-name` | The name of the crud. e.g. ```--crud-name="post"``` |
| `--model-name` | The name of the model. e.g. ```--model-name="Post"``` |
| `--model-namespace` | The namespace of the model. e.g. ```--model-namespace="Custom\Namespace\Post"``` |
| `--controller-namespace` | The namespace of the controller. e.g. ```--controller-namespace="Http\Controllers\Client"``` |
| `--view-path` | The name of the view path |
| `--fields` | The field names for the form. e.g. ```--fields='title#string; content#text; category#select#options={"technology": "Technology", "tips": "Tips", "health": "Health"}; user_id#integer#unsigned'``` |
| `--validations` | Validation rules for the fields "col_name#rules_set" e.g. ``` "title#min:10|max:30|required" ``` - See https://laravel.com/docs/master/validation#available-validation-rules |
| `--route-group` | Prefix of the route group |
| `--pagination` | The amount of models per page for index pages |
| `--force` | Overwrite already existing controller. |

### View Options:

| Option    | Description |
| ---       | ---     |
| `--fields` | The field names for the form. e.g. ```--fields='title#string; content#text; category#select#options={"technology": "Technology", "tips": "Tips", "health": "Health"}; user_id#integer#unsigned'``` |
| `--view-path` | The name of the view path |
| `--route-group` | Prefix of the route group |
| `--pk` | The name of the primary key |
| `--validations` | Validation rules for the form "col_name#rules_set" e.g. ``` "title#min:10|max:30|required" ``` - See https://laravel.com/docs/master/validation#available-validation-rules |
| `--form-helper` | Helper for the form. eg. `--form-helper=html`, `--form-helper=laravelcollective` |
| `--custom-data` | Some additional values to use in the crud. |
| `--localize` | Allow to localize. e.g. localize=yes  |

### Model Options:

| Option    | Description |
| ---       | ---     |
| `--table` | The name of the table |
| `--fillable` | The name of the view path |
| `--relationships` | The relationships for the model. e.g. `--relationships="comments#hasMany#App\Comment"` in the format |
| `--pk` | The name of the primary key |
| `--soft-deletes` | Include soft deletes fields. eg. `--soft-deletes=yes` |

### Migration Options:

| Option    | Description |
| ---       | ---     |
| `--schema` | The name of the schema |
| `--indexes` | The fields to add an index to. append "#unique" to a field name to add a unique index. Create composite fields by separating fieldnames with a pipe (` --indexes="title,field1|field2#unique" ` will create normal index on title, and unique composite on fld1 and fld2) |
| `--foreign-keys` | Any foreign keys for the table. e.g. `--foreign-keys="user_id#id#users#cascade"` where user_id is the column name, id is the name of the field on the foreign table, users is the name of the foreign table, and cascade is the operation 'ON DELETE' together with 'ON UPDATE' |
| `--pk` | The name of the primary key |
| `--soft-deletes` | Include soft deletes fields. eg. `--soft-deletes=yes` |

### Lang Options:

| Option    | Description |
| ---       | ---     |
| `--fields` | The field names for the form. e.g. ```--fields='title#string; content#text``` |
| `--locales`  | Locales language type. e.g. locals=en |

### API CRUD Options:

| Option    | Description |
| ---       | ---     |
| `--fields` | The field names for the form. e.g. ```--fields='title#string; content#text; category#select#options={"technology": "Technology", "tips": "Tips", "health": "Health"}; user_id#integer#unsigned'``` |
| `--fields_from_file` | Fields from a JSON file. e.g. `--fields_from_file="/path/to/fields.json"` |
| `--validations` | Validation rules for the fields "col_name#rules_set" e.g. ` "title#min:10|max:30|required" ` - See https://laravel.com/docs/master/validation#available-validation-rules |
| `--controller-namespace` | The namespace of the controller - sub directories will be created |
| `--model-namespace` | The namespace that the model will be placed in - directories will be created |
| `--pk` | The name of the primary key |
| `--pagination` | The amount of models per page for index pages |
| `--indexes` | The fields to add an index to. append "#unique" to a field name to add a unique index. Create composite fields by separating fieldnames with a pipe (` --indexes="title,field1|field2#unique" ` will create normal index on title, and unique composite on fld1 and fld2) |
| `--foreign-keys` | Any foreign keys for the table. e.g. `--foreign-keys="user_id#id#users#cascade"` where user_id is the column name, id is the name of the field on the foreign table, users is the name of the foreign table, and cascade is the operation 'ON DELETE' together with 'ON UPDATE' |
| `--relationships` | The relationships for the model. e.g. `--relationships="comments#hasMany#App\Comment"` in the format |
| `--route` | Include Crud route to routes.php? yes or no |
| `--route-group` | Prefix of the route group |
| `--soft-deletes` | Include soft deletes fields. eg. `--soft-deletes=yes` |

### API Controller Options:

| Option    | Description |
| ---       | ---     |
| `--crud-name` | The name of the crud. e.g. ```--crud-name="post"``` |
| `--model-name` | The name of the model. e.g. ```--model-name="Post"``` |
| `--model-namespace` | The namespace of the model. e.g. ```--model-namespace="Custom\Namespace\Post"``` |
| `--controller-namespace` | The namespace of the controller. e.g. ```--controller-namespace="Http\Controllers\Client"``` |
| `--validations` | Validation rules for the fields "col_name#rules_set" e.g. ``` "title#min:10|max:30|required" ``` - See https://laravel.com/docs/master/validation#available-validation-rules |
| `--pagination` | The amount of models per page for index pages |
| `--force` | Overwrite already existing controller. |

## Supported Fields

You can use any of the fields from the list.

### Form Field Types:

* text
* textarea
* password
* email
* number
* date
* datetime
* time
* radio
* select
* file

### Migration Field Types:

* string
* char
* varchar
* date
* datetime
* time
* timestamp
* text
* mediumtext
* longtext
* json
* jsonb
* binary
* integer
* bigint
* mediumint
* tinyint
* smallint
* boolean
* decimal
* double
* float
* enum

## Custom Templates

The package allows user to extensively customize or use own templates.

### All Templates

To customize or change the template, you need to follow these steps:

1. Just make sure you've published all assets of this package. If you didn't just run this command.
    ```php
    php artisan vendor:publish --provider="pierresilva\CrudGenerator\CrudGeneratorServiceProvider"
    ```
2. To override the default template with yours, turn on `custom_template` option in the `config/crudgenerator.php` file.
    ```php
    'custom_template' => true,
    ```

3. Now you can customize everything from this `resources/crud-generator/` directory.

4. Even if you need to use any custom variable just add those in the `config/crudgenerator.php` file.

### Form Helper

You can use any form helper package for your forms. In order to do that, you just need to mention the helper package name while generating the main CRUD or views with this option `--form-helper`. This generator use plain `html` as default helper.
Also, the `laravelcollective/html` helper is included in the core, So it will do everything for you when you just run command with the option `--form-helper=laravelcollective`.

To use the any other form helper, you need to follow these steps:

1. Make sure you've installed & configured the desire helper package.

2. For use custom helper template, you should turn on `custom_template` option in the `config/crudgenerator.php` file.

3. Now put your files into `resources/crud-generator/views/` directory. Suppose your helper is `myformhelper` then you should create a directory as `resources/crud-generator/views/myformhelper`. You can also copy the template files from other helper directory, then modify as yours.

4. You're ready to generate the CRUD with your helper.
    ```
    php artisan crud:generate Posts --fields='title#string; content#text; category#select#options={"technology": "Technology", "tips": "Tips", "health": "Health"}' --view-path=admin --controller-namespace=Admin --route-group=admin --form-helper=myformhelper
    ```

## Configuration

You will find a configuration file located at `config/crudgenerator.php`

### Custom Template

When you want to use your own custom template files, then you should turn it on and it will use the files from `resources/crud-generator/`

```php
'custom_template' => false,
```

### Path

You can change your template path easily, the default path is `resources/crud-generator/`.

```php
'path' => base_path('resources/crud-generator/'),
```

### View Columns

When generating CRUD or the views, the generator will assume the column number to show for the CRUD grid or detail automatically from the config. You can change it.

```php
'view_columns_number' => 3,
```

### Custom Delimiter

Set your delimiter which you use for your template vars. The default delimiter is `%%` in everywhere.

```php
'custom_delimiter' => ['%%', '%%'],
```
Note: You should use the delimiter same as yours template files.

### View Template Vars

This configuration will help you to use any custom template vars in the views `index`, `form`, `create`, `edit`, `show`

```php
'dynamic_view_template' => [],
```

## Tanks

[AppzCoder](https://github.com/appzcoder)

## License

This project is licensed under the MIT License - see the [License File](LICENSE) for details
