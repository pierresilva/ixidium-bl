<?php

namespace pierresilva\NgGenerators\Console\Commands;

use File;
use Illuminate\Console\Command;

/**
 * Class AngularComponent
 * @package pierresilva\NgGenerators\Console\Commands
 *
 * @todo Refactor file paths with ng-generators config file
 * @todo Add feature to add external Angular app
 */
class AngularComponent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ng:component    
    {name : The name of the component}  
    {module? : The module slug for the component}  
    {--no-spec : Don\'t create a test file}
    {--no-import : Don\'t auto import in index.components}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new angular component';

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

        $stubs_dir = __DIR__.'/Stubs';

        if (is_dir(base_path('resources/ng-generator/stubs')))
        {
            $stubs_dir = base_path('resources/ng-generator/stubs');
        }
        $name = $this->argument('name');
        $module = null;

        $studly_name = studly_case($name);
        $ng_component = str_replace('_', '-', $name);
        $title = title_case(str_replace('-', ' ', $ng_component));

        $html = file_get_contents($stubs_dir.'/AngularComponent/component.html.stub');
        $ts = file_get_contents($stubs_dir.'/AngularComponent/component.ts.stub');
        $style = file_get_contents($stubs_dir.'/AngularComponent/component.style.stub');
        $spec = file_get_contents($stubs_dir.'/AngularComponent/component.spec.ts.stub');

        if ($this->argument('module')) {

            $module = $this->argument('module');
            $ng_module = str_replace('_', '-', $module);
            $ng_module_sc = studly_case($module);

            if (!is_dir(base_path(config('ng-generators.source.root')).'/modules/'.$ng_module)) {
                $this->info('Module don\'t exists');

                return false;
            }

            if (is_dir(base_path(config('ng-generators.source.root')).'/modules/'.$ng_module.'/components/'.$module.'-'.$ng_component)) {
                $this->info('Component already exists');

                return false;
            }

            $ts = str_replace('{{Component}}', $ng_module_sc . $studly_name, $ts);
            $ts = str_replace('{{component}}', $ng_module .'-'. $ng_component, $ts);
            $spec = str_replace('{{Component}}', $ng_module_sc . $studly_name, $spec);
            $spec = str_replace('{{component}}', $ng_module .'-'. $ng_component, $spec);
            $html = str_replace('{{Component}}', $ng_module_sc . $studly_name, $html);
            $html = str_replace('{{component}}', $ng_module .'-'. $ng_component, $html);
            $style = str_replace('{{Component}}', $ng_module_sc . $studly_name, $style);
            $style = str_replace('{{component}}', $ng_module .'-'. $ng_component, $style);

            File::makeDirectory(base_path(config('ng-generators.source.root')).'/modules/'.$ng_module.'/components/'.$ng_module.'-'.$ng_component, 0775, true);
            File::put(base_path(config('ng-generators.source.root')).'/modules/'.$ng_module.'/components/'.$ng_module.'-'.$ng_component.'/'.$ng_module.'-'.$ng_component.'.component.ts', $ts);
            File::put(base_path(config('ng-generators.source.root')).'/modules/'.$ng_module.'/components/'.$ng_module.'-'.$ng_component.'/'.$ng_module.'-'.$ng_component.'.component.spec.ts', $spec);
            File::put(base_path(config('ng-generators.source.root')).'/modules/'.$ng_module.'/components/'.$ng_module.'-'.$ng_component.'/'.$ng_module.'-'.$ng_component.'.component.html', $html);
            File::put(base_path(config('ng-generators.source.root')).'/modules/'.$ng_module.'/components/'.$ng_module.'-'.$ng_component.'/'.$ng_module.'-'.$ng_component.'.component.scss', $style);

            //import component
            $module_index = base_path(config('ng-generators.source.root')).'/modules/'.$ng_module.'/'.$ng_module.'.module.ts';
            $modules_index = file_get_contents($module_index);
            $module_import = "import { ".$ng_module_sc . $studly_name."Component } from './components/".$ng_module.'-'.$ng_component."/".$ng_module.'-'.$ng_component.".component';\r\n// generated components imports here //";
            $module_declaration = $ng_module_sc . $studly_name."Component,\r\n\t\t// generated components declarations here //";
            $module_index_new = str_replace("// generated components imports here //", $module_import, $modules_index);
            $module_index_new = str_replace("// generated components declarations here //", $module_declaration, $module_index_new);
            file_put_contents($module_index, $module_index_new);
            $module_routing_index = base_path(config('ng-generators.source.root')).'/modules/'.$ng_module.'/'.$ng_module.'-routing.module.ts';
            $modules_routing = file_get_contents($module_routing_index);
            $module_import_string = "import { ".$ng_module_sc . $studly_name."Component } from './components/".$ng_module.'-'.$ng_component."/".$ng_module.'-'.$ng_component.".component';\r\n// generated components imports here //";
            $module_routing_string = "{ path: '".$ng_component."', component: ".$ng_module_sc . $studly_name."Component, },\n\r\t// generated components routes here //";
            $modules_routing_new = str_replace("// generated components imports here //", $module_import_string, $modules_routing);
            $modules_routing_new = str_replace("// generated components routes here //", $module_routing_string, $modules_routing_new);
            file_put_contents($module_routing_index, $modules_routing_new);

            // add menu link
            $app_component_index = base_path(config('ng-generators.source.root')).'/app.component.ts';
            $app_component = file_get_contents($app_component_index);
            $link_string = "<a routerLink=\"/".$ng_module."/".$ng_component."\" routerLinkActive=\"active\">".$title."</a>\r\n\t\t\t<!-- new generated modules here -->";
            $app_component_new = str_replace("<!-- new generated modules here -->", $link_string, $app_component);
            file_put_contents($app_component_index, $app_component_new);

            $this->info('Component created successfully.');
        } else {

            if (is_dir(base_path(config('ng-generators.source.root')).'/components/'.$ng_component)) {
                $this->info('Component already exists');

                return false;
            }
            $ts = str_replace('{{Component}}', $studly_name, $ts);
            $ts = str_replace('{{component}}', $ng_component, $ts);
            $spec = str_replace('{{Component}}', $studly_name, $spec);
            $spec = str_replace('{{component}}', $ng_component, $spec);
            $html = str_replace('{{Component}}', $studly_name, $html);
            $html = str_replace('{{component}}', $ng_component, $html);
            $style = str_replace('{{Component}}', $studly_name, $style);
            $style = str_replace('{{component}}', $ng_component, $style);

            File::makeDirectory(base_path(config('ng-generators.source.root')).'/components/'.$ng_component, 0775, true);
            File::put(base_path(config('ng-generators.source.root')).'/components/'.$ng_component.'/'.$ng_component.'.component.ts', $ts);
            File::put(base_path(config('ng-generators.source.root')).'/components/'.$ng_component.'/'.$ng_component.'.component.spec.ts', $spec);
            File::put(base_path(config('ng-generators.source.root')).'/components/'.$ng_component.'/'.$ng_component.'.component.html', $html);
            File::put(base_path(config('ng-generators.source.root')).'/components/'.$ng_component.'/'.$ng_component.'.component.css', $style);

            //import component
            $module_index = base_path(config('ng-generators.source.root')).'/app.module.ts';
            $modules_index = file_get_contents($module_index);
            $module_import = "import { ".$studly_name."Component } from './components/".$ng_component."/".$ng_component.".component';\r\n// generated components imports here //";
            $module_declaration = $studly_name."Component,\r\n\t// generated components declarations here //";
            $module_index_new = str_replace("// generated components imports here //", $module_import, $modules_index);
            $module_index_new = str_replace("// generated components declarations here //", $module_declaration, $module_index_new);
            file_put_contents($module_index, $module_index_new);
            $module_routing_index = base_path(config('ng-generators.source.root')).'/app-routing.module.ts';
            $modules_routing = file_get_contents($module_routing_index);
            $module_import_string = "import { ".$studly_name."Component } from './components/".$ng_component."/".$ng_component.".component';\r\n// generated components imports here //";
            $module_routing_string = "{ path: '".$ng_component."', component: ".$studly_name."Component, },\n\r\t// generated components routes here //";
            $modules_routing_new = str_replace("// generated components imports here //", $module_import_string, $modules_routing);
            $modules_routing_new = str_replace("// generated components routes here //", $module_routing_string, $modules_routing_new);
            file_put_contents($module_routing_index, $modules_routing_new);

            // add menu link
            $app_component_index = base_path(config('ng-generators.source.root')).'/app.component.ts';
            $app_component = file_get_contents($app_component_index);
            $link_string = "<a routerLink=\"/".$ng_component."\" routerLinkActive=\"active\">".$title."</a>\r\n\t\t\t<!-- new generated modules here -->";
            $app_component_new = str_replace("<!-- new generated modules here -->", $link_string, $app_component);
            file_put_contents($app_component_index, $app_component_new);

            $this->info('Component created successfully.');
        }

    }
}
