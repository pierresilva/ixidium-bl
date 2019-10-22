<?php

namespace pierresilva\CrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudFactoryCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:factory
                            {name : The name of the factory.}
                            {--fields= : Field names for the form & migration.}
                            {--crud-name= : The name of the Crud.}
                            {--model-name= : The name of the Model.}
                            {--model-namespace= : The namespace of the Model.}
                            {--force : Overwrite already existing controller.}
                            {--module-name= : Root Module name to place files in there.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new factory.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Factory';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return config('crudgenerator.custom_template')
            ? config('crudgenerator.path') . '/factory.stub'
            : __DIR__ . '/../stubs/factory.stub';
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
        $fields = $this->option('fields');
        $fieldsArray = explode(';', $fields);

        $fields = $fieldsArray;

        $data = array();

        if ($fields) {
            $x = 0;
            foreach ($fields as $field) {
                $fieldArray = explode('#', $field);
                $data[$x]['name'] = trim($fieldArray[0]);
                $data[$x]['type'] = trim($fieldArray[1]);
                $x++;
            }
        }

        $fieldsString = '';

        if (count($data) > 0) {
            foreach ($data as $field) {
                if ($field['type'] === 'number' || $field['type'] === 'integer') {
                  $fieldsString .= "'".$field['name']."' => 1,\n";
                } else if ($field['type'] === 'text' || $field['type'] === 'string') {
                    $fieldsString .= "'".$field['name']."' => 'some text',\n";
                } else if ($field['type'] === 'date' || $field['type'] === 'datetime' || $field['type'] === 'timestamp') {
                    $fieldsString .= "'".$field['name']."' => '1990-01-01 00:00:01',\n";
                } else {
                    $fieldsString .= "'".$field['name']."' => null,\n";
                }
            }
        }

        $ret = $this->replaceNamespace($stub, $name)
            ->replaceCrudName($stub, $crudName)
            ->replaceCrudNameCapitalized($stub, $crudName)
            ->replaceCrudNameSingular($stub, $crudNameSingular)
            ->replaceModelName($stub, $modelName)
            ->replaceModelNamespace($stub, $modelNamespace)
            ->replaceModelNamespaceSegments($stub, $modelNamespace)
            ->replaceFactoryItems($stub, $fieldsString);

        if ($this->option('module-name')) {
            $ret->replaceModuleNamespace($stub, $moduleNamespace);
        }

        return $ret->replaceClass($stub, $name);

    }

    /**
     * @param $stub
     * @param $moduleNamespace
     * @return $this
     */
    public function replaceModuleNamespace(&$stub, $moduleNamespace)
    {
        $stub = str_replace('{{moduleNamespace}}', $moduleNamespace, $stub);

        return $this;
    }

    /**
     * @param $stub
     * @param $items
     * @return $this
     */
    public function replaceFactoryItems(&$stub, $items)
    {
        $stub = str_replace('{{factoryFields}}', $items, $stub);

        return $this;
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
     * Get the destination class path.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        if ($this->option('module-name')) {
            return base_path() .'/app/Modules/' . $this->option('module-name') . '/Database/Factories/' . $this->argument('name') . 'Factory.php';
        }
        return base_path() . '/database/factories/' .  str_replace('\\', '/', $this->argument('name')) . 'Factory.php';
    }

}
