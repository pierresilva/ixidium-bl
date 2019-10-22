<?php

namespace pierresilva\CrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudSeederCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:seeder
                            {name : The name of the seeder.}
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
    protected $description = 'Create a new seeder.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Seeder';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return config('crudgenerator.custom_template')
            ? config('crudgenerator.path') . '/seeder.stub'
            : __DIR__ . '/../stubs/seeder.stub';
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
        $seederClassName = $this->option('module-name') ? $this->option('module-name') . $this->option('crud-name') . 'Seeder' : $crudName . 'Seeder';

        $ret = $this->replaceNamespace($stub, $name)
            ->replaceCrudName($stub, $crudName)
            ->replaceCrudNameCapitalized($stub, $crudName)
            ->replaceCrudNameSingular($stub, $crudNameSingular)
            ->replaceModelName($stub, $modelName)
            ->replaceModelNamespace($stub, $modelNamespace)
            ->replaceModelNamespaceSegments($stub, $modelNamespace)
            ->replaceModuleNamespace($stub, $moduleNamespace)
            ->replaceSeederClassName($stub, $seederClassName);


        return $ret->replaceClass($stub, $name);

    }

    /**
     * @param $stub
     * @param $seederClassName
     * @return $this
     */
    public function replaceSeederClassName(&$stub, $seederClassName)
    {
        $stub = str_replace('{{seederClassName}}', $seederClassName, $stub);

        return $this;
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
            return base_path() .'/app/Modules/' . $this->option('module-name') . '/Database/Seeds/' . $this->option('crud-name') . 'Seeder.php';
        }
        return base_path() . '/database/seeds/' .  str_replace('\\', '/', $this->argument('name')) . 'Seeder.php';
    }

}
