<?php
namespace pierresilva\Modules\Console\Generators;
use pierresilva\Modules\Console\GeneratorCommand;


class MakeOperationCommand extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module:operation
    	{slug : The module slug}
    	{name : The name of the operation class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module operation class';

    /**
     * String to store the command type.
     *
     * @var string
     */
    protected $type = 'Module operation';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/operation.stub';
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
        return module_class($this->argument('slug'), 'Operations');
    }

}