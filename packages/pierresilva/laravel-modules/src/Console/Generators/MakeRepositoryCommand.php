<?php

namespace pierresilva\Modules\Console\Generators;


use pierresilva\Modules\Console\GeneratorCommand;

class MakeRepositoryCommand extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module:repository
    	{slug : The slug of the module}
    	{name : The name of the repository class}
    	{model : The name of the model class in module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module model repository class';

    /**
     * String to store the command type.
     *
     * @var string
     */
    protected $type = 'Module repository';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if (! $this->argument('model')) {
            return 'Model not found';
        }
        $this->model = $this->argument('model');
        $this->slug = $this->argument('slug');
        $this->name = $this->argument('name');
        return __DIR__.'/stubs/repository.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     * @throws \pierresilva\Modules\Exceptions\ModuleNotFoundException
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return module_class($this->argument('slug'), 'Repositories');
    }
}
