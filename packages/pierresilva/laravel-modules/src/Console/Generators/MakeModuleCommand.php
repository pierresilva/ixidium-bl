<?php

namespace pierresilva\Modules\Console\Generators;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use pierresilva\Modules\Modules;
use Symfony\Component\Console\Helper\ProgressBar;

class MakeModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module
        {slug : The slug of the module}
        {--angular : Create a new angular module to.}
        {--Q|quick : Skip the make:module wizard and use default values}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module and bootstrap it';

    /**
     * The modules instance.
     *
     * @var Modules
     */
    protected $module;

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Array to store the configuration details.
     *
     * @var array
     */
    protected $container;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     * @param Modules    $module
     */
    public function __construct(Filesystem $files, Modules $module)
    {
        parent::__construct();

        $this->files = $files;
        $this->module = $module;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->container['slug'] = str_slug($this->argument('slug'));
        $this->container['name'] = studly_case($this->container['slug']);
        $this->container['version'] = '1.0';
        $this->container['description'] = 'This is the description for the ' . $this->container['name'] . ' module.';

        if ($this->option('quick')) {
            $this->container['basename'] = studly_case($this->container['slug']);
            $this->container['namespace'] = config('modules.namespace') . $this->container['basename'];
            return $this->generate();
        }

        $this->displayHeader('make_module_introduction');

        $this->stepOne();
    }

    /**
     * Step 1: Configure module manifest.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function stepOne()
    {
        $this->displayHeader('make_module_step_1');

        $this->container['name'] = $this->ask('Please enter the name of the module:', $this->container['name']);
        $this->container['slug'] = $this->ask('Please enter the slug for the module:', $this->container['slug']);
        $this->container['version'] = $this->ask('Please enter the module version:', $this->container['version']);
        $this->container['description'] = $this->ask('Please enter the description of the module:', $this->container['description']);
        $this->container['basename'] = studly_case($this->container['slug']);
        $this->container['namespace'] = config('modules.namespace') . $this->container['basename'];

        $this->comment('You have provided the following manifest information:');
        $this->comment('Name:                       ' . $this->container['name']);
        $this->comment('Slug:                       ' . $this->container['slug']);
        $this->comment('Version:                    ' . $this->container['version']);
        $this->comment('Description:                ' . $this->container['description']);
        $this->comment('Basename (auto-generated):  ' . $this->container['basename']);
        $this->comment('Namespace (auto-generated): ' . $this->container['namespace']);

        if ($this->confirm('If the provided information is correct, type "yes" to generate.')) {
            $this->comment('Thanks! That\'s all we need.');
            $this->comment('Now relax while your module is generated.');

            $this->generate();
        } else {
            return $this->stepOne();
        }

        return true;
    }

    /**
     * Generate the module.
     */
    protected function generate()
    {
        $steps = [
            'Generating module...' => 'generateModule',
            'Optimizing module cache...' => 'optimizeModules',
        ];

        $progress = new ProgressBar($this->output, count($steps));
        $progress->start();

        foreach ($steps as $message => $function) {
            $progress->setMessage($message);

            $this->$function();

            $progress->advance();
        }

        $progress->finish();

        event($this->container['slug'] . '.module.made');

        $this->info("\nModule generated successfully.");

        if ($this->option('angular')) {
            if (class_exists('pierresilva\NgGenerators\LaravelServiceProvider')) {
                $this->call('ng:module', [
                    'name' => $this->container['slug'],
                ]);

                $angularRoutingFile = file_get_contents(base_path('resources/angular/app/app-routing.ts'));

                $moduleConstSectionMarkup = 'const ' . $this->container['name'] . ' = ' . $this->container['name'] . ';';



                $moduleRoutingMarkup = "// " . $this->container['name'] . " section
                {
                    path: '" . kebab_case($this->container['name']) . "',
                    loadChildren: './modules/" . kebab_case($this->container['name']) . "/" . kebab_case($this->container['name']) . ".module#" . $this->container['name'] . "Module',
                    data: {
                        icon: 'screen',
                        text: '" . $this->container['name'] . "',
                        section: " . kebab_case($this->container['name']) . ",
                        display: true,
                    },
                },
                // generated " . $this->container['name'] . " module routes here //
                // generated module routes here //";

                $angularRoutingFile = str_replace('// generated module sections here //', $moduleConstSectionMarkup . "\n", $angularRoutingFile);

                $angularRoutingFile = str_replace('// generated module routes here //', $moduleRoutingMarkup, $angularRoutingFile);

                file_put_contents(base_path('resources/angular/app/app-routing.ts'), $angularRoutingFile);

            } else {
                $this->error("\nNG Generatos package is not installed!");
            }

        }
    }

    /**
     * Generate defined module folders.
     */
    protected function generateModule()
    {
        if (!$this->files->isDirectory(module_path())) {
            $this->files->makeDirectory(module_path());
        }

        $pathMap = config('modules.pathMap');
        $directory = module_path(null, $this->container['basename']);
        $source = __DIR__ . '/../../../resources/stubs/module';

        $this->files->makeDirectory($directory);

        $sourceFiles = $this->files->allFiles($source, true);

        if (!empty($pathMap)) {
            $search = array_keys($pathMap);
            $replace = array_values($pathMap);
        }

        foreach ($sourceFiles as $file) {
            $contents = $this->replacePlaceholders($file->getContents());
            $subPath = $file->getRelativePathname();

            if (!empty($pathMap)) {
                $subPath = str_replace($search, $replace, $subPath);
            }

            $filePath = $directory . '/' . $subPath;
            $dir = dirname($filePath);

            if (!$this->files->isDirectory($dir)) {
                $this->files->makeDirectory($dir, 0755, true);
            }

            $this->files->put($filePath, $contents);
        }

        $phpunitModuleTestSuite = '<testsuite name="' . $this->container['name'] . 'Module">
            <directory suffix="Test.php">./app/Modules/' . $this->container['name'] . '/Tests</directory>
        </testsuite>
        <!-- generated modules test suits here -->';

        $phpunitModuleExcludeDirs = '<directory>./app/Modules/' . $this->container['name'] . '/Routes</directory>
                <directory>./app/Modules/' . $this->container['name'] . '/Database</directory>
                <!-- generated modules exclude dirs here -->';

        $phpunitConfigFile = file_get_contents(base_path('phpunit.xml'));

        $phpunitConfigFile = str_replace('<!-- generated modules test suits here -->', $phpunitModuleTestSuite, $phpunitConfigFile);
        $phpunitConfigFile = str_replace('<!-- generated modules exclude dirs here -->', $phpunitModuleExcludeDirs, $phpunitConfigFile);

        file_put_contents(base_path('phpunit.xml'), $phpunitConfigFile);
    }

    /**
     * Reset module cache of enabled and disabled modules.
     */
    protected function optimizeModules()
    {
        return $this->callSilent('module:optimize');
    }

    /**
     * Pull the given stub file contents and display them on screen.
     *
     * @param string $file
     * @param string $level
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function displayHeader($file = '', $level = 'info')
    {
        $stub = $this->files->get(__DIR__ . '/../../../resources/stubs/console/' . $file . '.stub');

        return $this->$level($stub);
    }

    protected function replacePlaceholders($contents)
    {
        $find = [
            'DummyBasename',
            'DummyNamespace',
            'DummyName',
            'DummySlug',
            'DummyVersion',
            'DummyDescription',
        ];

        $replace = [
            $this->container['basename'],
            $this->container['namespace'],
            $this->container['name'],
            $this->container['slug'],
            $this->container['version'],
            $this->container['description'],
        ];

        return str_replace($find, $replace, $contents);
    }
}
