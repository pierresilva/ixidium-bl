<?php

namespace pierresilva\NgGenerators\Console\Commands;

use File;
use Illuminate\Console\Command;

/**
 * Class AngularModule
 * @package pierresilva\NgGenerators\Console\Commands
 *
 * @todo AdmiTemplate for menu.
 */
class AngularModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ng:module 
    {name : The Angular module name.}
    {module-path? : The module path for the new module.}
    {--no-spec : Don\'t create a test file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module for angular app';

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
     */
    public function handle()
    {
        $name = $this->argument('name');
        $studly_name = studly_case($name);
        $ng_module = str_replace('_', '-', $name);
        $title = title_case(str_replace('-', ' ', $ng_module));

        $stubs_dir = __DIR__ . '/Stubs';

        if (is_dir(base_path('resources/ng-generator/stubs'))) {
            $stubs_dir = base_path('resources/ng-generator/stubs');
        }

        $folder = base_path(config('ng-generators.source.root')) . '/' . config('ng-generators.source.modules') . '/' . $ng_module;

        if ($this->argument('module-path')) {
            $folder = base_path(config('ng-generators.source.root')) . '/' . config('ng-generators.source.modules') . '/' . $this->argument('module-path') . '/' . $ng_module;
        }

        if (is_dir($folder)) {
            $this->info('Module already exists');
            return false;
        }

        // create folder modules
        File::makeDirectory($folder, 0775, true);
        // create folder modules/components
        // File::makeDirectory($folder.'/components', 0775, true);
        // create folder for home component
        // File::makeDirectory($folder.'/components/'.$ng_module.'-home', 0775, true);

        $module = file_get_contents($stubs_dir . '/AngularModule/module.module.ts.stub');
        $routing = file_get_contents($stubs_dir . '/AngularModule/module-routing.module.ts.stub');

        $home_component = file_get_contents($stubs_dir . '/AngularModule/module.component.ts.stub');
        $home_component = str_replace('{{Component}}', $studly_name, $home_component);
        $home_component = str_replace('{{component}}', $ng_module, $home_component);
        $ngModulePath = '/modules/' . $ng_module . '/' . $ng_module;

        if ($this->argument('module-path')) {
            $ngModulePath = '/modules/' . $this->argument('module-path') . '/' . $ng_module . '/' . $ng_module;
        }

        File::put(base_path(config('ng-generators.source.root')) . $ngModulePath . '.component.ts', $home_component);
        $home_component_view = file_get_contents($stubs_dir . '/AngularModule/module.component.html.stub');
        $home_component_view = str_replace('{{component}}', $ng_module, $home_component_view);
        File::put(base_path(config('ng-generators.source.root')) . $ngModulePath . '.component.html', $home_component_view);
        $home_component_style = file_get_contents($stubs_dir . '/AngularModule/module.component.scss.stub');
        $home_component_style = str_replace('{{Component}}', $studly_name, $home_component_style);
        File::put(base_path(config('ng-generators.source.root')) . $ngModulePath . '.component.scss', $home_component_style);

        if (!$this->option('no-spec')) {
            $home_component_spec = file_get_contents($stubs_dir . '/AngularModule/module.component.spec.ts.stub');
            $home_component_spec = str_replace('{{Component}}', $studly_name, $home_component_spec);
            $home_component_spec = str_replace('{{component}}', $ng_module, $home_component_spec);
            File::put(base_path(config('ng-generators.source.root')) . $ngModulePath . '.component.spec.ts', $home_component_spec);
        }

        $module = str_replace('{{Module}}', $studly_name, $module);
        $module = str_replace('{{module}}', $ng_module, $module);
        $module = str_replace('{{Component}}', $studly_name, $module);
        $module = str_replace('{{component}}', $ng_module, $module);

        //create module (.module.ts)
        File::put(base_path(config('ng-generators.source.root')) . $ngModulePath . config('ng-generators.suffix.module'), $module);

        $routing = str_replace('{{Module}}', $studly_name, $routing);
        $routing = str_replace('{{Component}}', $studly_name, $routing);
        $routing = str_replace('{{component}}', $ng_module, $routing);

        //create module-routing (.component.js)
        File::put(base_path(config('ng-generators.source.root')) . $ngModulePath . '-routing' . config('ng-generators.suffix.module'), $routing);

        //import module
        if (!$this->argument('module-path')) {
            $module_routing_index = base_path(config('ng-generators.source.root')) . '/app-routing.ts';
            $modules_routing = file_get_contents($module_routing_index);
            $module_routing_section = "const " . $studly_name . " = '" . $studly_name . "';
        // generated module section here //";
            $module_routing_string = "// ".$studly_name." section
            {
                path: '" . $ng_module . "',
                loadChildren: './modules/" . $ng_module . "/" . $ng_module . ".module#" . $studly_name . "Module',
                data: {
                    icon: 'screen', 
                    text: '" . $studly_name . "',
                    section: " . $studly_name . ", 
                    display: true,
                },
            },
            // generated " . $studly_name . " module routes here //
            // generated module routes here //";
            $modules_routing_next = str_replace("// generated module section here //", $module_routing_section, $modules_routing);
            $modules_routing_finish = str_replace("// generated module routes here //", $module_routing_string, $modules_routing_next);
            file_put_contents($module_routing_index, $modules_routing_finish);
        }
        // add menu link
        /*
        $app_component_index = base_path(config('ng-generators.source.root')).'/app.component.ts';
        $app_component = file_get_contents($app_component_index);
        $link_string = "<a routerLink=\"/".$ng_module."\" routerLinkActive=\"active\">".$title."</a>\r\n\t\t\t<!-- new generated modules here -->";
        $app_component_new = str_replace("<!-- new generated modules here -->", $link_string, $app_component);
        file_put_contents($app_component_index, $app_component_new);
        */

        $this->info('Angular Module created successfully.');
    }
}
