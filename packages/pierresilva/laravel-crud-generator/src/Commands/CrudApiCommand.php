<?php

namespace pierresilva\CrudGenerator\Commands;

use File;
use Illuminate\Console\Command;

class CrudApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:api
                            {name : The name of the Crud.}
                            {--fields= : Field names for the form & migration.}
                            {--fields_from_file= : Fields from a json file.}
                            {--validations= : Validation rules for the fields.}
                            {--controller-namespace= : Namespace of the controller.}
                            {--model-namespace= : Namespace of the model inside "app" dir.}
                            {--pk=id : The name of the primary key.}
                            {--pagination=10 : The amount of models per page for index pages.}
                            {--indexes= : The fields to add an index to.}
                            {--foreign-keys= : The foreign keys for the table.}
                            {--relationships= : The relationships for the model.}
                            {--route=yes : Include Crud route to routes.php? yes|no.}
                            {--route-group= : Prefix of the route group.}
                            {--soft-deletes=no : Include soft deletes fields.}
                            {--module-name= : Root Module name to place files in there.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate api crud including controller, model & migrations.';

    /** @var string */
    protected $routeName = '';

    /** @var string */
    protected $controller = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $name = $this->argument('name');
        $modelName = str_singular($name);
        $migrationName = str_plural(snake_case($name));
        $tableName = $migrationName;
        $migrationFields = '';
        $moduleName = $this->option('module-name') ? $this->option('module-name') : '';

        $routeGroup = $this->option('route-group');
        $this->routeName = ($routeGroup) ? $routeGroup . '/' . snake_case($name, '-') : snake_case($name, '-');
        $perPage = intval($this->option('pagination'));

        $controllerNamespace = ($this->option('controller-namespace')) ? $this->option('controller-namespace') . '\\' : '';
        $modelNamespace = ($this->option('model-namespace')) ? trim($this->option('model-namespace')) . '\\' : 'App';

        $fields = rtrim($this->option('fields'), ';');

        if ($this->option('fields_from_file')) {
            $fields = $this->processJSONFields($this->option('fields_from_file'));
        }

        $primaryKey = $this->option('pk');

        $foreignKeys = $this->option('foreign-keys');

        if ($this->option('fields_from_file')) {
            $foreignKeys = $this->processJSONForeignKeys($this->option('fields_from_file'));
        }

        $validations = trim($this->option('validations'));
        if ($this->option('fields_from_file')) {
            $validations = $this->processJSONValidations($this->option('fields_from_file'));
        }

        $fieldsArray = explode(';', $fields);
        $fillableArray = [];

        foreach ($fieldsArray as $item) {
            $spareParts = explode('#', trim($item));
            $fillableArray[] = $spareParts[0];
            $modifier = !empty($spareParts[2]) ? $spareParts[2] : 'nullable';

            // Process migration fields
            $migrationFields .= $spareParts[0] . '#' . $spareParts[1];
            $migrationFields .= '#' . $modifier;
            $migrationFields .= ';';
        }

        $commaSeparetedString = implode("', '", $fillableArray);
        $fillable = "['" . $commaSeparetedString . "']";

        $indexes = $this->option('indexes');
        $relationships = $this->option('relationships');
        if ($this->option('fields_from_file')) {
            $relationships = $this->processJSONRelationships($this->option('fields_from_file'));
        }

        $softDeletes = $this->option('soft-deletes');

        $this->call('crud:api-controller', ['name' => $name . 'Controller', '--module-name' => $moduleName, '--controller-namespace' => $controllerNamespace, '--fields' => $fields, '--crud-name' => $name, '--model-name' => $modelName, '--model-namespace' => $modelNamespace, '--pagination' => $perPage, '--validations' => $validations]);
        $this->call('crud:model', ['name' => $modelName, '--module-name' => $moduleName, '--model-namespace' => $modelNamespace, '--fillable' => $fillable, '--table' => $tableName, '--pk' => $primaryKey, '--relationships' => $relationships, '--soft-deletes' => $softDeletes]);
        $this->call('crud:migration', ['name' => $migrationName, '--module-name' => $moduleName, '--schema' => $migrationFields, '--pk' => $primaryKey, '--indexes' => $indexes, '--foreign-keys' => $foreignKeys, '--soft-deletes' => $softDeletes]);
        $this->call('crud:request', ['name' => $name . 'Create', '--module-name' => $moduleName, '--validations' => $validations, '--authorize' => true]);
        $this->call('crud:request', ['name' => $name . 'Edit', '--module-name' => $moduleName, '--validations' => $validations, '--authorize' => true]);
        $this->call('crud:factory', ['name' => $name, '--module-name' => $moduleName, '--fields' => $fields, '--crud-name' => $name, '--model-name' => $modelName, '--model-namespace' => $modelNamespace]);
        $this->call('crud:seeder', ['name' => $name, '--module-name' => $moduleName, '--crud-name' => $name, '--model-name' => $modelName, '--model-namespace' => $modelNamespace]);

        // For optimizing the class loader
        // $this->callSilent('optimize');

        // Updating the Http/routes.php file
        $routeFile = app_path('Http/routes.php');

        if (\App::VERSION() >= '5.3') {
            $routeFile = base_path('routes/api.php');
        }

        if ($this->option('module-name')) {
            $routeFile = base_path('app/Modules/' . $moduleName . '/Routes/api.php');
        }

        if (file_exists($routeFile) && (strtolower($this->option('route')) === 'yes')) {
            $this->controller = ($controllerNamespace != '') ? $controllerNamespace . '\\' . $name . 'Controller' : $name . 'Controller';

            $isAdded = File::append($routeFile, "\n" . implode("\n", $this->addRoutes()));

            if ($isAdded) {
                $this->info('Crud Api route added to ' . $routeFile);
            } else {
                $this->info('Unable to add the route to ' . $routeFile);
            }
        }

    }

    /**
     * Add routes.
     *
     * @return  array
     */
    protected function addRoutes()
    {
        return ["Route::resource('" . $this->routeName . "', '" . $this->controller . "');"];
    }

    /**
     * Process the JSON Fields.
     *
     * @param  string $file
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function processJSONFields($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        $fieldsString = '';
        foreach ($fields->fields as $field) {
            if ($field->type == 'select') {
                $fieldsString .= $field->name . '#' . $field->type . '#options=' . json_encode($field->options) . ';';
            } else {
                $fieldsString .= $field->name . '#' . $field->type . ';';
            }
        }

        $fieldsString = rtrim($fieldsString, ';');

        return $fieldsString;
    }

    /**
     * Process the JSON Foreign keys.
     *
     * @param  string $file
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function processJSONForeignKeys($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        if (!property_exists($fields, 'foreign_keys')) {
            return '';
        }

        $foreignKeysString = '';
        foreach ($fields->foreign_keys as $foreign_key) {
            $foreignKeysString .= $foreign_key->column . '#' . $foreign_key->references . '#' . $foreign_key->on;

            if (property_exists($foreign_key, 'onDelete')) {
                $foreignKeysString .= '#' . $foreign_key->onDelete;
            }

            if (property_exists($foreign_key, 'onUpdate')) {
                $foreignKeysString .= '#' . $foreign_key->onUpdate;
            }

            $foreignKeysString .= ',';
        }

        $foreignKeysString = rtrim($foreignKeysString, ',');

        return $foreignKeysString;
    }

    /**
     * Process the JSON Relationships.
     *
     * @param  string $file
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function processJSONRelationships($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        if (!property_exists($fields, 'relationships')) {
            return '';
        }

        $relationsString = '';
        foreach ($fields->relationships as $relation) {
            $relationsString .= $relation->name . '#' . $relation->type . '#' . $relation->class . ';';
        }

        $relationsString = rtrim($relationsString, ';');

        return $relationsString;
    }

    /**
     * Process the JSON Validations.
     *
     * @param  string $file
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function processJSONValidations($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        if (!property_exists($fields, 'validations')) {
            return '';
        }

        $validationsString = '';
        foreach ($fields->validations as $validation) {
            $validationsString .= $validation->field . '#' . $validation->rules . ';';
        }

        $validationsString = rtrim($validationsString, ';');

        return $validationsString;
    }
}
