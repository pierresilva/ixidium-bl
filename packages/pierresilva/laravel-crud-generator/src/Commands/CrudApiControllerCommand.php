<?php

namespace pierresilva\CrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudApiControllerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:api-controller
                            {name : The name of the controler.}
                            {--fields= : Field names for the form & migration.}
                            {--crud-name= : The name of the Crud.}
                            {--model-name= : The name of the Model.}
                            {--model-namespace= : The namespace of the Model.}
                            {--controller-namespace : Namespace of the controller.}
                            {--validations= : Validation rules for the fields.}
                            {--pagination=10 : The amount of models per page for index pages.}
                            {--force : Overwrite already existing controller.}
                            {--module-name= : Root Module name to place files in there.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new api controller.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return config('crudgenerator.custom_template')
        ? config('crudgenerator.path') . '/api-controller.stub'
        : __DIR__ . '/../stubs/api-controller.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        if ($this->option('module-name')) {
            return $rootNamespace . '\\Modules\\' . $this->option('module-name') . '\\Http\Controllers\\' . ($this->option('controller-namespace') ? $this->option('controller-namespace') : '');
        }
        return $rootNamespace . '\\' . ($this->option('controller-namespace') ? $this->option('controller-namespace') : 'Http\Controllers');
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        if ($this->option('force')) {
            return false;
        }
        return parent::alreadyExists($rawName);
    }

    /**
     * Build the model class with the given name.
     *
     * @param  string $name
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $crudName = strtolower($this->option('crud-name'));
        $crudNameSingular = str_singular($crudName);
        $modelName = $this->option('model-name');
        $modelNamespace = $this->option('module-name') ? 'App\Modules\\' . $this->option('module-name') . '\\Models\\' . $this->option('model-namespace') : $this->option('model-namespace');
        $moduleNamespace = $this->option('module-name') ? 'App\\Modules\\' . $this->option('module-name') . '\\' : 'App\\';
        $perPage = intval($this->option('pagination'));
        $validations = rtrim($this->option('validations'), ';');
        $fields = $this->option('fields');
        $snippet = <<<EOD
        if (\$request->hasFile('{{fieldName}}')) {
            foreach(\$request['{{fieldName}}'] as \$file){
                \$uploadPath = public_path('/uploads/{{fieldName}}');

                \$extension = \$file->getClientOriginalExtension();
                \$fileName = rand(11111, 99999) . '.' . \$extension;

                \$file->move(\$uploadPath, \$fileName);
                \$requestData['{{fieldName}}'] = \$fileName;
            }
        }
EOD;
        $fieldsArray = explode(';', $fields);
        $fileSnippet = '';

        $whereSnippet = '';

        if ($fields) {
            $x = 0;
            foreach ($fieldsArray as $index => $item) {
                $itemArray = explode('#', $item);

                if (trim($itemArray[1]) === 'file') {
                    $fileSnippet .= "\n\n" . str_replace('{{fieldName}}', trim($itemArray[0]), $snippet) . "\n";
                }

                $fieldName = trim($itemArray[0]);

                $whereSnippet .= ($index == 0) ? "where('$fieldName', 'LIKE', \"%\$keyword%\")" . "\n                " : "->orWhere('$fieldName', 'LIKE', \"%\$keyword%\")" . "\n                ";
            }

            $whereSnippet .= "->";
        }

        $validationRules = '';
        if (trim($validations) != '') {
            $validationRules = "\$this->validate(\$request, [";

            $rules = explode(';', $validations);
            foreach ($rules as $v) {
                if (trim($v) == '') {
                    continue;
                }

                // extract field name and args
                $parts = explode('#', $v);
                $fieldName = trim($parts[0]);
                $rules = trim($parts[1]);
                $validationRules .= "\n\t\t\t'$fieldName' => '$rules',";
            }

            $validationRules = substr($validationRules, 0, -1); // lose the last comma
            $validationRules .= "\n\t\t]);";
        }

        $ret = $this->replaceNamespace($stub, $name)
            ->replaceCrudName($stub, $crudName)
            ->replaceCrudNameCapitalized($stub, $crudName)
            ->replaceCrudNameSingular($stub, $crudNameSingular)
            ->replaceModelName($stub, $modelName)
            ->replaceModelNamespace($stub, $modelNamespace)
            ->replaceModelNamespaceSegments($stub, $modelNamespace)
            ->replaceValidationRules($stub, $validationRules)
            ->replacePaginationNumber($stub, $perPage)
            ->replaceFileSnippet($stub, $fileSnippet)
            ->replaceWhereSnippet($stub, $whereSnippet)
            ->replaceModuleNamespace($stub, $moduleNamespace);


         return $ret->replaceClass($stub, $name);
    }

    public function replaceModuleNamespace(&$stub, $moduleNamespace)
    {
        $stub = str_replace('{{moduleNamespace}}', $moduleNamespace, $stub);

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);
        $class = str_replace('\\', '', $class);

        return str_replace(
            [
                'DummyClass', 
                '{{module}}', 
                '{{date}}'
            ], 
            [
                $class, 
                $this->option('module-name'), 
                date('Y-m-d')
            ], $stub);
    }

    /**
     * Replace the crudName for the given stub.
     *
     * @param  string  $stub
     * @param  string  $crudName
     *
     * @return $this
     */
    protected function replaceCrudName(&$stub, $crudName)
    {
        $stub = str_replace('{{crudName}}', $crudName, $stub);

        return $this;
    }

    /**
     * Replace the CrudName for the given stub.
     *
     * @param  string  $stub
     * @param  string  $crudName
     *
     * @return $this
     */
    protected function replaceCrudNameCapitalized(&$stub, $crudName)
    {
        $stub = str_replace('{{CrudName}}', ucfirst($crudName), $stub);

        return $this;
    }

    /**
     * Replace the crudNameSingular for the given stub.
     *
     * @param  string  $stub
     * @param  string  $crudNameSingular
     *
     * @return $this
     */
    protected function replaceCrudNameSingular(&$stub, $crudNameSingular)
    {
        $stub = str_replace('{{crudNameSingular}}', $crudNameSingular, $stub);

        return $this;
    }

    /**
     * Replace the modelName for the given stub.
     *
     * @param  string  $stub
     * @param  string  $modelName
     *
     * @return $this
     */
    protected function replaceModelName(&$stub, $modelName)
    {
        $stub = str_replace('{{modelName}}', $modelName, $stub);

        return $this;
    }

    /**
     * Replace the modelNamespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $modelNamespace
     *
     * @return $this
     */
    protected function replaceModelNamespace(&$stub, $modelNamespace)
    {
        $stub = str_replace('{{modelNamespace}}', $modelNamespace, $stub);

        return $this;
    }

    /**
     * Replace the modelNamespace segments for the given stub
     *
     * @param $stub
     * @param $modelNamespace
     *
     * @return $this
     */
    protected function replaceModelNamespaceSegments(&$stub, $modelNamespace)
    {
        $modelSegments = explode('\\', $modelNamespace);
        foreach ($modelSegments as $key => $segment) {
            $stub = str_replace('{{modelNamespace[' . $key . ']}}', $segment, $stub);
        }

        $stub = preg_replace('{{modelNamespace\[\d*\]}}', '', $stub);

        return $this;
    }

    /**
     * Replace the validationRules for the given stub.
     *
     * @param  string  $stub
     * @param  string  $validationRules
     *
     * @return $this
     */
    protected function replaceValidationRules(&$stub, $validationRules)
    {
        $stub = str_replace('{{validationRules}}', $validationRules, $stub);

        return $this;
    }

    /**
     * Replace the pagination placeholder for the given stub
     *
     * @param $stub
     * @param $perPage
     *
     * @return $this
     */
    protected function replacePaginationNumber(&$stub, $perPage)
    {
        $stub = str_replace('{{pagination}}', $perPage, $stub);

        return $this;
    }

    /**
     * Replace the where snippet for the given stub
     *
     * @param $stub
     * @param $whereSnippet
     *
     * @return $this
     */
    protected function replaceWhereSnippet(&$stub, $whereSnippet)
    {
        $stub = str_replace('{{whereSnippet}}', $whereSnippet, $stub);

        return $this;
    }

    /**
     * Replace the file snippet for the given stub
     *
     * @param $stub
     * @param $fileSnippet
     *
     * @return $this
     */
    protected function replaceFileSnippet(&$stub, $fileSnippet)
    {
        $stub = str_replace('{{fileSnippet}}', $fileSnippet, $stub);

        return $this;
    }
}
