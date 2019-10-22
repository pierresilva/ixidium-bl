<?php

namespace pierresilva\CrudGenerator\Commands;

use File;
use Illuminate\Console\Command;
use Module;

class CrudAngularCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:api:angular
                            {name : The name of the Crud.}
                            {title : String used for the titles.}
                            {module : The module name to place files there.}
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
                            {--view-path= : The name of the view path.}
                            {--form-helper=angular : Helper for generating the form.}
                            {--localize=yes : Allow to localize? yes|no.}
                            {--locales=es : Locales language type.}
                            {--module-name= : Root Module name to place files in there.}
                            {--soft-deletes=yes : Include soft deletes fields.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate front-end and back-end Crud.';

    /**
     * @var string
     */
    protected $routeName = '';

    /**
     * @var string
     */
    protected $controller = '';

    protected $fileEstructure;

    protected $stubsDir;

    protected $fieldsArray;

    protected $delimiter = ['%%', '%%'];

    /**
     *  Form field types collection.
     *
     * @var array
     */
    protected $typeLookup = [
        'string' => 'text',
        'char' => 'text',
        'varchar' => 'text',
        'text' => 'textarea',
        'mediumtext' => 'textarea',
        'longtext' => 'textarea',
        'json' => 'textarea',
        'jsonb' => 'textarea',
        'binary' => 'textarea',
        'password' => 'password',
        'email' => 'email',
        'number' => 'number',
        'integer' => 'number',
        'bigint' => 'number',
        'mediumint' => 'number',
        'tinyint' => 'number',
        'smallint' => 'number',
        'decimal' => 'number',
        'double' => 'number',
        'float' => 'number',
        'date' => 'date',
        'datetime' => 'datetime-local',
        'timestamp' => 'datetime-local',
        'time' => 'time',
        'boolean' => 'checkbox',
        'enum' => 'radio',
        'select' => 'select',
        'file' => 'file',
    ];

    /**
     * Variables that can be used in stubs
     *
     * @var array
     */
    protected $vars = [
        'formFields',
        'formFieldsHtml',
        'varName',
        'crudName',
        'crudNameCap',
        'crudNameSingular',
        'primaryKey',
        'modelName',
        'modelNameCap',
        'viewName',
        'routePrefix',
        'routePrefixCap',
        'routeGroup',
        'formHeadingHtml',
        'formBodyHtml',
        'viewTemplateDir',
        'formBodyHtmlForShowView',
    ];

    /**
     * Form's fields.
     *
     * @var array
     */
    protected $formFields = [];

    /**
     * Html of Form's fields.
     *
     * @var string
     */
    protected $formFieldsHtml = '';

    /**
     * Number of columns to show from the table. Others are hidden.
     *
     * @var integer
     */
    protected $defaultColumnsToShow = 3;

    protected $jsonDecoded;

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
        if (!$this->option('fields_from_file')) {
            $this->error('You must specify a JSON fields file with "--fields_from_file" command option!');
            return;
        }

        /***************
         * NEEDED VARS *
         ***************/

        $jsonDecoded = File::get($this->option('fields_from_file'));
        $this->jsonDecoded = json_decode($jsonDecoded);

        $fileEstructure = [
            'crud_name' => $this->argument('name'),
            'crud_name_singular' => str_singular($this->argument('name')),
            'crud_name_dash' => kebab_case($this->argument('name')),
            'crud_name_dash_singular' => str_singular(kebab_case($this->argument('name'))),
            'crud_name_camel' => camel_case($this->argument('name')),
            'crud_name_camel_singular' => str_singular(camel_case($this->argument('name'))),
            'crud_title' => $this->argument('title'),
            'crud_title_singular' => str_singular($this->argument('title')),
            'crud_title_lower' => mb_strtolower($this->argument('title')),
            'crud_module' => $this->argument('module'),
            'crud_module_dash' => kebab_case($this->argument('module')),
            'crud_angular_path' => 'resources/angular/app/modules/' . mb_strtolower(kebab_case($this->argument('module'))) . '/' . mb_strtolower(kebab_case($this->argument('name'))),
            'crud_laravel_path' => '/Modules/' . $this->argument('module'),
        ];
        $this->fileEstructure = $fileEstructure;

        if (!Module::exists($this->fileEstructure['crud_module_dash'])) {
            $this->error($this->argument('module') . ' module don\'t exists!');
            return;
        }

        $stubsDir = __DIR__ . '/../stubs';
        if (is_dir(base_path('resources/vendor/laravel-crud-generator/stubs'))) {
            $stubsDir = base_path('resources/vendor/laravel-crud-generator/stubs');
        }
        $this->stubsDir = $stubsDir;

        /***************
         * BACKEND API *
         ***************/

        $name = $this->argument('name');
        $modelName = str_singular($name);
        $migrationName = str_plural(snake_case($name));
        $tableName = $migrationName;
        $moduleName = $this->argument('module');

        $routeGroup = $this->option('route-group');
        $this->routeName = ($routeGroup) ? $routeGroup . '/' . snake_case($this->argument('module'), '-') . '/' . snake_case($name, '-') : '/' . snake_case($this->argument('module'), '-') . '/' . snake_case($name, '-');
        $perPage = intval($this->option('pagination'));

        $controllerNamespace = ($this->option('controller-namespace')) ? $this->option('controller-namespace') . '\\' : '';
        $modelNamespace = ($this->option('model-namespace')) ? trim($this->option('model-namespace')) . '\\' : '';

        $fields = rtrim($this->option('fields'), ';');

        if ($this->option('fields_from_file')) {
            $fields = $this->processJSONFields($this->option('fields_from_file'));
        }

        if ($fields == "") {
            $this->error('You must be provide a fields for CRUD!');
            return;
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
        $this->fieldsArray = $fieldsArray;
        $fillableArray = [];
        $migrationFields = '';

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
        $this->call('crud:api:test', ['name' => $name, 'module' => $moduleName, 'items' => json_encode($this->jsonDecoded)]);

        $routeFile = base_path('app/Modules/' . $moduleName . '/Routes/api.php');

        $this->controller = ($controllerNamespace != '') ? $controllerNamespace . '\\' . $name . 'Controller' : $name . 'Controller';

        $isAdded = File::append($routeFile, "\n" . implode("\n", $this->addRoutes()));

        if ($isAdded) {
            $this->info('Crud Api route added to ' . $routeFile);
        } else {
            $this->info('Unable to add the route to ' . $routeFile);
        }

        /****************
         * FRONTEND API *
         ****************/

        $angularRuteFile = File::get(base_path('resources/angular/app/app-routing.ts'));

        $crud_url = $fileEstructure['crud_module_dash'] . '/' . $fileEstructure['crud_name_dash'];

        $module_routing_string = "{
                path: '" . $crud_url . "',
                loadChildren: './modules/" . $fileEstructure['crud_module_dash'] . "/" . $fileEstructure['crud_name_dash'] . "/" . $fileEstructure['crud_name_dash'] . ".module#" . $fileEstructure['crud_name'] . "Module',
                data: {
                    icon: 'screen', 
                    text: '" . $fileEstructure['crud_title'] . "',
                    section: " . $fileEstructure['crud_module'] . ", 
                    display: true,
                },
            },
            // generated " . $fileEstructure['crud_module'] . " module routes here //";

        $angularRuteFile = str_replace("// generated " . $fileEstructure['crud_module'] . " module routes here //", $module_routing_string, $angularRuteFile);

        File::put(base_path('resources/angular/app/app-routing.ts'), $angularRuteFile);

        // ANGULAR CRUD MODEL
        $this->makeFolder($fileEstructure['crud_angular_path'] . '/models/');

        $this->makeFile($fileEstructure['crud_angular_path'] . '/models/',
            $fileEstructure['crud_name_dash_singular'] . '.ts',
            $stubsDir . '/views/angular/models/model.ts.stub',
            'model');

        // ANGULAR CRUD MODULE COMPONENT SCSS
        $this->makeFile($fileEstructure['crud_angular_path'],
            $fileEstructure['crud_name_dash'] . '.component.scss',
            $stubsDir . '/views/angular/module.component.scss.stub',
            'module_component_scss');

        // ANGULAR CRUD MODULE COMPONENT HTML
        $this->makeFile($fileEstructure['crud_angular_path'],
            $fileEstructure['crud_name_dash'] . '.component.html',
            $stubsDir . '/views/angular/module.component.html.stub',
            'module_component_html');

        // ANGULAR CRUD MODULE COMPONENT TS
        $this->makeFile($fileEstructure['crud_angular_path'],
            $fileEstructure['crud_name_dash'] . '.component.ts',
            $stubsDir . '/views/angular/module.component.ts.stub',
            'module_component_ts');

        // ANGULAR CRUD MODULE
        $this->makeFile($fileEstructure['crud_angular_path'],
            $fileEstructure['crud_name_dash'] . '.module.ts',
            $stubsDir . '/views/angular/module.module.ts.stub',
            'module');

        // ANGULAR CRUD MODULE ROUTING
        $this->makeFile($fileEstructure['crud_angular_path'],
            $fileEstructure['crud_name_dash'] . '-routing.module.ts',
            $stubsDir . '/views/angular/module-routing.module.ts.stub',
            'module_routing');

        // ANGULAR CRUD FORM COMPONENT
        $this->makeFolder($fileEstructure['crud_angular_path'] . '/' . $fileEstructure['crud_name_dash'] . '-form');

        // ANGULAR CRUD FORM COMPONENT HTML
        $this->makeFile($fileEstructure['crud_angular_path'] . '/' . $fileEstructure['crud_name_dash'] . '-form/',
            $fileEstructure['crud_name_dash'] . '-form.component.html',
            $stubsDir . '/views/angular/form/form.component.html.stub',
            'form_component_html');

        // ANGULAR CRUD FORM COMPONENT SCSS
        $this->makeFile($fileEstructure['crud_angular_path'] . '/' . $fileEstructure['crud_name_dash'] . '-form/',
            $fileEstructure['crud_name_dash'] . '-form.component.scss',
            $stubsDir . '/views/angular/form/form.component.scss.stub',
            'form_component_scss');

        // ANGULAR CRUD FORM COMPONENT TS
        $this->makeFile($fileEstructure['crud_angular_path'] . '/' . $fileEstructure['crud_name_dash'] . '-form/',
            $fileEstructure['crud_name_dash'] . '-form.component.ts',
            $stubsDir . '/views/angular/form/form.component.ts.stub',
            'form_component');

        // ANGULAR CRUD LIST COMPONENT
        $this->makeFolder($fileEstructure['crud_angular_path'] . '/' . $fileEstructure['crud_name_dash'] . '-list');

        // ANGULAR CRUD LIST COMPONENT HTML
        $this->makeFile($fileEstructure['crud_angular_path'] . '/' . $fileEstructure['crud_name_dash'] . '-list/',
            $fileEstructure['crud_name_dash'] . '-list.component.html',
            $stubsDir . '/views/angular/list/list.component.html.stub',
            'list_component_html');

        // ANGULAR CRUD LIST COMPONENT SCSS
        $this->makeFile($fileEstructure['crud_angular_path'] . '/' . $fileEstructure['crud_name_dash'] . '-list/',
            $fileEstructure['crud_name_dash'] . '-list.component.scss',
            $stubsDir . '/views/angular/list/list.component.scss.stub',
            'list_component_scss');

        // ANGULAR CRUD LIST COMPONENT TS
        $this->makeFile($fileEstructure['crud_angular_path'] . '/' . $fileEstructure['crud_name_dash'] . '-list/',
            $fileEstructure['crud_name_dash'] . '-list.component.ts',
            $stubsDir . '/views/angular/list/list.component.ts.stub',
            'list_component');

        $this->info('Angular front end created successfully.');

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
                $fieldsString .= $field->name . '#' . $field->type . '#' . $field->label . '#options=' . json_encode($field->options) . ';';
            } else {
                $fieldsString .= $field->name . '#' . $field->type . '#' . $field->label . ';';
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

    /**
     * Make a folder.
     *
     * @param $folderPath string Folder path from root.
     * @return bool
     */
    protected function makeFolder($folderPath)
    {
        try {
            File::makeDirectory($folderPath, 0775, true);
            $done = true;
        } catch (\Exception $e) {
            $done = false;
        }

        return $done;

    }

    /**
     * Make a file.
     *
     * @param $filePath string File name.
     * @param $fileName string Folder path from root.
     * @param $stub string Stub file full path.
     * @param null $type
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function makeFile($filePath, $fileName, $stub, $type = null)
    {

        if (!File::exists(base_path($filePath . '/' . $fileName))) {

            $file = file_get_contents($stub);
            $file = str_replace('crud_name_camel_singular', $this->fileEstructure['crud_name_camel_singular'], $file);
            $file = str_replace('crud_name_camel', $this->fileEstructure['crud_name_camel'], $file);
            $file = str_replace('crud_name_dash_singular', $this->fileEstructure['crud_name_dash_singular'], $file);
            $file = str_replace('crud_name_dash', $this->fileEstructure['crud_name_dash'], $file);
            $file = str_replace('crud_name_singular', $this->fileEstructure['crud_name_singular'], $file);
            $file = str_replace('crud_name', $this->fileEstructure['crud_name'], $file);
            $file = str_replace('crud_title_singular', $this->fileEstructure['crud_title_singular'], $file);
            $file = str_replace('crud_title', $this->fileEstructure['crud_title'], $file);

            switch ($type) {
                case 'model':
                    $file = $this->makeModel($file);
                    break;
                case 'module_component_html':

                    break;
                case 'module_component_scss':

                    break;
                case 'module_component_ts':

                    break;
                case 'module_routing':

                    break;
                case 'module':

                    break;
                case 'form_component_html':
                    $file = $this->makeFormComponentHtmlFile($file);
                    break;
                case 'form_component_scss':
                    $file = $this->makeFormComponentScssFile($file);
                    break;
                case 'form_component':
                    $file = $this->makeFormComponentFile($file);
                    break;
                case 'list_component_html':
                    $file = $this->makeListComponentHtml($file);
                    break;
                case 'list_component_scss':

                    break;
                case 'list_component':
                    $file = $this->makeListComponent($file);
                    break;
                default:
                    return false;
            }

            File::put(base_path($filePath . '/' . $fileName), $file);

        }

    }

    /**
     * Make Angular CRUD model
     *
     * @param $file
     * @return mixed
     */
    private function makeModel($file)
    {
        $fields = $this->fieldsArray;
        $attributes = "id: any;\n\t";
        foreach ($fields as $field) {
            $attrs = explode('#', $field);
            $attributes .= "$attrs[0]: any;\n\t";
        }
        $attributes .= "created_at: any;\n\t";
        $attributes .= "updated_at: any;\n\t";
        $attributes .= "deleted_at: any;\n\t";

        $file = str_replace('[[modelAttributes]]', $attributes, $file);

        return $file;
    }

    /**
     * Make crud form component html file.
     *
     * @param $file
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function makeFormComponentHtmlFile($file)
    {
        //$fields = $this->jsonDecoded['fields'];
        //$validations = $this->jsonDecoded['validations'];

        $fieldsString = $this->processJSONFields($this->option('fields_from_file'));
        $validationsString = $this->processJSONValidations($this->option('fields_from_file'));

        $fieldsArray = explode(';', $fieldsString);

        $this->formFields = [];

        $x = 0;
        foreach ($fieldsArray as $item) {
            $itemArray = explode('#', $item);

            $this->formFields[$x]['name'] = trim($itemArray[0]);
            $this->formFields[$x]['type'] = trim($itemArray[1]);
            $this->formFields[$x]['label'] = trim($itemArray[2]);
            $this->formFields[$x]['required'] = preg_match('/' . $itemArray[0] . '#required/', $validationsString) ? true : false;

            if ($this->formFields[$x]['type'] == 'select' && isset($itemArray[2])) {
                $options = trim($itemArray[3]);
                $options = str_replace('options=', '', $options);

                $this->formFields[$x]['options'] = $options;
            }

            $x++;
        }

        foreach ($this->formFields as $item) {
            $this->formFieldsHtml .= $this->createField($item) . "\n\t";
        }

        return str_replace('[[componentHtmlFormFields]]', $this->formFieldsHtml, $file);

    }

    private function makeFormComponentScssFile($file)
    {
        return $file;

    }

    private function makeFormComponentFile($file)
    {
        $start = $this->delimiter[0];
        $end = $this->delimiter[1];

        $fieldsString = $this->processJSONFields($this->option('fields_from_file'));
        $validationsString = $this->processJSONValidations($this->option('fields_from_file'));

        $fieldsArray = explode(';', $fieldsString);

        $this->formFields = [];

        $x = 0;
        foreach ($fieldsArray as $item) {
            $itemArray = explode('#', $item);

            $this->formFields[$x]['name'] = trim($itemArray[0]);
            $this->formFields[$x]['type'] = trim($itemArray[1]);
            $this->formFields[$x]['label'] = trim($itemArray[2]);
            $this->formFields[$x]['required'] = preg_match('/' . $itemArray[0] . '#required/', $validationsString) ? true : false;

            if ($this->formFields[$x]['type'] == 'select' && isset($itemArray[2])) {
                $options = trim($itemArray[3]);
                $options = str_replace('options=', '', $options);

                $this->formFields[$x]['options'] = $options;
            }

            $x++;
        }

        $errorMessageMarkup = "%%itemName%%: {%%required%%},";
        $formValidationMarkup = "%%itemName%%: [\n\tthis.%%crud_name_camel_singular%%.%%itemName%%,\n\rValidators.compose([%%required%%])],";

        $errorMessagesMarkup = '';
        $formValidationsMarkup = '';

        foreach ($this->formFields as $item) {

            $errorMessagesMarkup .= $errorMessageMarkup;
            $errorMessagesMarkup = str_replace($start . 'itemName' . $end, $item['name'], $errorMessagesMarkup);

            if ($item['required']) {
                $errorMessagesMarkup = str_replace($start . 'required' . $end, "\n\trequired: 'Este campo es requerido.',", $errorMessagesMarkup);
            } else {
                $errorMessagesMarkup = str_replace($start . 'required' . $end, "", $errorMessagesMarkup);
            }

            $formValidationsMarkup .= $formValidationMarkup;

            if ($item['required']) {
                $formValidationsMarkup = str_replace($start . 'required' . $end, "\n\tValidators.required,", $formValidationsMarkup);
            } else {
                $formValidationsMarkup = str_replace($start . 'required' . $end, "", $formValidationsMarkup);
            }

            $formValidationsMarkup = str_replace($start . 'itemName' . $end, $item['name'], $formValidationsMarkup);

        }

        $markup = str_replace('[[errorMessages]]', $errorMessagesMarkup, $file);
        $markup = str_replace('[[formValidations]]', $formValidationsMarkup, $markup);
        $markup = str_replace($start . 'crud_name_camel_singular' . $end, $this->fileEstructure['crud_name_camel_singular'], $markup);
        $markup = str_replace('[[crudUrl]]', $this->fileEstructure['crud_module_dash'] . '/' . $this->fileEstructure['crud_name_dash'], $markup);

        return $markup;

    }

    private function makeListComponentHtml($file)
    {
        $start = $this->delimiter[0];
        $end = $this->delimiter[1];

        $fieldsString = $this->processJSONFields($this->option('fields_from_file'));
        $validationsString = $this->processJSONValidations($this->option('fields_from_file'));

        $fieldsArray = explode(';', $fieldsString);

        $this->formFields = [];

        $x = 0;
        foreach ($fieldsArray as $item) {
            $itemArray = explode('#', $item);

            $this->formFields[$x]['name'] = trim($itemArray[0]);
            $this->formFields[$x]['type'] = trim($itemArray[1]);
            $this->formFields[$x]['label'] = trim($itemArray[2]);
            $this->formFields[$x]['required'] = preg_match('/' . $itemArray[0] . '#required/', $validationsString) ? true : false;

            if ($this->formFields[$x]['type'] == 'select' && isset($itemArray[2])) {
                $options = trim($itemArray[3]);
                $options = str_replace('options=', '', $options);

                $this->formFields[$x]['options'] = $options;
            }

            $x++;
        }

        $thMarkup = "<th>%%itemLabel%%</th>\n\t";
        $tdMarkup = "<td>{{%%crud_name_camel_singular%%.%%itemName%%}}</td>\n\t";

        $thsMarkup = '';
        $tdsMarkup = '';

        foreach ($this->formFields as $item) {
            $thsMarkup .= $thMarkup;
            $thsMarkup = str_replace($start . 'itemLabel' . $end, $item['label'], $thsMarkup);

            $tdsMarkup .= $tdMarkup;
            $tdsMarkup = str_replace($start . 'crud_name_camel_singular' . $end, $this->fileEstructure['crud_name_camel_singular'], $tdsMarkup);
            $tdsMarkup = str_replace($start . 'itemName' . $end, $item['name'], $tdsMarkup);

        }

        $markup = str_replace('[[thItems]]', $thsMarkup, $file);
        $markup = str_replace('[[tdItems]]', $tdsMarkup, $markup);
        $markup = str_replace('[[itemsCount]]', count($fieldsArray) + 1, $markup);

        return $markup;

    }

    private function makeListComponent($file)
    {
        $markup = str_replace('[[crudUrl]]', $this->fileEstructure['crud_module_dash'] . '/' . $this->fileEstructure['crud_name_dash'], $file);

        return $markup;
    }

    /**
     * Form field wrapper.
     *
     * @param  string $item
     * @param  string $field
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function wrapField($item, $field)
    {
        $formGroup = File::get($this->stubsDir . '/views/angular/form-fields/wrap-field.stub');

        $labelText = "'" . ucwords(strtolower(str_replace('_', ' ', $item['name']))) . "'";

        if ($this->option('localize') == 'yes') {
            $labelText = 'trans(\'' . $this->fileEstructure['crud_name_dash'] . '.' . $item['name'] . '\')';
        }

        return sprintf($formGroup, $item['name'], $labelText, $field);
    }

    /**
     * Form field generator.
     *
     * @param  array $item
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function createField($item)
    {
        switch ($this->typeLookup[$item['type']]) {
            case 'password':
                return $this->createPasswordField($item);
            case 'datetime-local':
            case 'time':
                return $this->createInputField($item);
            case 'radio':
                return $this->createRadioField($item);
            case 'textarea':
                return $this->createTextareaField($item);
            case 'select':
            case 'enum':
                return $this->createSelectField($item);
            default: // text
                return $this->createFormField($item);
        }
    }

    /**
     * Create a specific field using the form helper.
     *
     * @param  array $item
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function createFormField($item)
    {
        $start = $this->delimiter[0];
        $end = $this->delimiter[1];

        $required = $item['required'] ? 'required' : '';

        $markup = File::get($this->stubsDir . '/views/angular/form-fields/form-field.stub');
        $markup = str_replace($start . 'required' . $end, $required, $markup);
        $markup = str_replace($start . 'itemType' . $end, $this->typeLookup[$item['type']], $markup);
        $markup = str_replace($start . 'itemName' . $end, $item['name'], $markup);
        $markup = str_replace($start . 'itemLabel' . $end, $item['label'], $markup);
        $markup = str_replace($start . 'crud_name_camel_singular' . $end, $this->fileEstructure['crud_name_camel_singular'], $markup);

        return $markup;
    }

    /**
     * Create a password field using the form helper.
     *
     * @param  array $item
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function createPasswordField($item)
    {
        $start = $this->delimiter[0];
        $end = $this->delimiter[1];

        $required = $item['required'] ? 'required' : '';

        $markup = File::get($this->stubsDir . '/views/angular/form-fields/password-field.stub');
        $markup = str_replace($start . 'required' . $end, $required, $markup);
        $markup = str_replace($start . 'itemName' . $end, $item['name'], $markup);
        $markup = str_replace($start . 'itemLabel' . $end, $item['label'], $markup);
        $markup = str_replace($start . 'crud_name_camel_singular' . $end, $this->fileEstructure['crud_name_camel_singular'], $markup);

        return $markup;
    }

    /**
     * Create a generic input field using the form helper.
     *
     * @param  array $item
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function createInputField($item)
    {
        $start = $this->delimiter[0];
        $end = $this->delimiter[1];

        $required = $item['required'] ? 'required' : '';

        $markup = File::get($this->stubsDir . '/views/angular/form-fields/input-field.stub');
        $markup = str_replace($start . 'required' . $end, $required, $markup);
        $markup = str_replace($start . 'itemType' . $end, $this->typeLookup[$item['type']], $markup);
        $markup = str_replace($start . 'itemName' . $end, $item['name'], $markup);
        $markup = str_replace($start . 'itemLabel' . $end, $item['label'], $markup);
        $markup = str_replace($start . 'crud_name_camel_singular' . $end, $this->fileEstructure['crud_name_camel_singular'], $markup);

        return $markup;
    }

    /**
     * Create a yes/no radio button group using the form helper.
     *
     * @param  array $item
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function createRadioField($item)
    {
        $start = $this->delimiter[0];
        $end = $this->delimiter[1];

        $markup = File::get($this->stubsDir . '/views/angular/form-fields/radio-field.stub');

        $itemOptions = json_decode($item['options']);

        $radioMarkup = "<div class=\"row\">\n\t<b>{$item['label']}</b><br>\n</div>";

        foreach ($itemOptions as $itemOption) {
            $radioMarkup .= $markup;
            $radioMarkup = str_replace($start . 'crud_name_camel_singular' . $end, $this->fileEstructure['crud_name_camel_singular'], $radioMarkup);
            $radioMarkup = str_replace($start . 'itemLabel' . $end, $itemOption, $radioMarkup);
            $radioMarkup = str_replace($start . 'itemName' . $end, $item['name'], $radioMarkup);
            $radioMarkup = str_replace($start . 'itemOption' . $end, kebab_case($itemOption), $radioMarkup);
        }

        return $radioMarkup;
    }

    /**
     * Create a textarea field using the form helper.
     *
     * @param  array $item
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function createTextareaField($item)
    {
        $start = $this->delimiter[0];
        $end = $this->delimiter[1];

        $required = $item['required'] ? 'required' : '';

        $markup = File::get($this->stubsDir . '/views/angular/form-fields/textarea-field.stub');
        $markup = str_replace($start . 'crud_name_camel_singular' . $end, $this->fileEstructure['crud_name_camel_singular'], $markup);
        $markup = str_replace($start . 'required' . $end, $required, $markup);
        $markup = str_replace($start . 'fieldType' . $end, $this->typeLookup[$item['type']], $markup);
        $markup = str_replace($start . 'itemLabel' . $end, $item['label'], $markup);
        $markup = str_replace($start . 'itemName' . $end, $item['name'], $markup);

        return $markup;
    }

    /**
     * Create a select field using the form helper.
     *
     * @param  array $item
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function createSelectField($item)
    {
        $start = $this->delimiter[0];
        $end = $this->delimiter[1];

        $required = $item['required'] ? 'required' : '';

        $markup = File::get($this->stubsDir . '/views/angular/form-fields/select-field.stub');

        $markup = str_replace($start . 'required' . $end, $required, $markup);
        $markup = str_replace($start . 'fieldType' . $end, $this->typeLookup[$item['type']], $markup);
        $markup = str_replace($start . 'itemLabel' . $end, $item['label'], $markup);
        $markup = str_replace($start . 'itemName' . $end, $item['name'], $markup);
        $markup = str_replace($start . 'crud_name_camel_singular' . $end, $this->fileEstructure['crud_name_camel_singular'], $markup);

        $optionMarkup = "<option value=\"%%itemOptionDash%%\">%%itemOption%%</option>";

        $itemOptions = json_decode($item['options'], true);

        $itemOptionsMarkup = '';

        foreach ($itemOptions as $itemOption) {
            $itemOptionsMarkup .= $optionMarkup;
            $itemOptionsMarkup = str_replace($start . 'itemOptionDash' . $end, kebab_case($itemOption['value']), $itemOptionsMarkup);
            $itemOptionsMarkup = str_replace($start . 'itemOption' . $end, $itemOption['label'], $itemOptionsMarkup);
        }

        $markup = str_replace($start . 'selectOptions' . $end, $itemOptionsMarkup, $markup);

        return $markup;
    }
}
