<?php

namespace pierresilva\Modules\Console\Generators;

use pierresilva\Modules\Console\GeneratorCommand;

class MakeTestCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module:test
    	{slug : The slug of the module}
    	{name : The name of the test class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module test class';

    /**
     * String to store the command type.
     *
     * @var string
     */
    protected $type = 'Module test';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/test.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     *
     * @return string
     * @throws \pierresilva\Modules\Exceptions\ModuleNotFoundException
     */
    protected function getPath($name)
    {
        $slug = $this->argument('slug');
        $module = \Module::where('slug', $slug);

        // take everything after the module name in the given path (ignoring case)
        $key = array_search(strtolower($module['basename']), explode('\\', strtolower($name)));
        if ($key === false) {
            $newPath = str_replace('\\', '/', $name);
        } else {
            $newPath = implode('/', array_slice(explode('\\', $name), $key + 1));
        }

        return module_path($slug, "{$newPath}Test.php");
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
        return module_class($this->argument('slug'), 'Tests');
    }
}
