<?php

namespace pierresilva\CrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudRequestCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:request
                            {name : The name of the request.}                                                           
                            {--validations= : Validation rules for the fields.}      
                            {--authorize : The request returns auth validation}                                               
                            {--force : Overwrite already existing request.}
                            {--module-name= : Root Module name to place files in there.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new resource create and edit request.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Request';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return config('crudgenerator.custom_template')
            ? config('crudgenerator.path') . '/request.stub'
            : __DIR__ . '/../stubs/request.stub';
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
        return $rootNamespace . '\\Http\\Requests';
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

        $validations = rtrim($this->option('validations'), ';');

        $authorize = 'false';

        $moduleNamespace = $this->option('module-name') ? 'App\\Modules\\' . $this->option('module-name') . '\\' : '';

        $requestNamespace = $this->option('module-name') ? $moduleNamespace . 'Http\\Requests' : 'App\\Http\\Requests';

        if ($this->option('authorize')) {
            $authorize = 'true';
        }

        $validationRules = '';
        if (trim($validations) != '') {
            $validationRules = "[";
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
            $validationRules .= "\n\t\t]";
        }

        $this->replaceRequestNamespace($stub, $requestNamespace)
            ->replaceValidationRules($stub, $validationRules)
            ->replaceAuthorize($stub, $authorize);

        return $this->replaceClass($stub, $name);
    }

    public function replaceRequestNamespace(&$stub, $requestNamespace)
    {
        $stub = str_replace('DummyNamespace', $requestNamespace, $stub);

        return $this;
    }

    public function replaceModuleNamespace(&$stub, $moduleNamespace)
    {
        $stub = str_replace('{{moduleNamespace}}', $moduleNamespace, $stub);

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
     * Replace the authorize value for the given stub.
     *
     * @param $stub
     * @param $authorize
     *
     * @return mixed
     */
    protected function replaceAuthorize(&$stub, $authorize)
    {
        $stub = str_replace('{{authorize}}', $authorize, $stub);

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
            return base_path() .'/app/Modules/' . $this->option('module-name') . '/Http/Requests/' . $this->argument('name') . 'Request.php';
        }
        return base_path() . '/app/Http/Requests/' .  str_replace('\\', '/', $this->argument('name')) . 'Request.php';
    }

}
