<?php
namespace pierresilva\CrudGenerator\Commands;

use Illuminate\Console\Command;
use File;

class CrudApiTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:api:test
                            {module : The module name.}                         
                            {name : The crud name.}
                            {items : Json string whit fields.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate test case.';

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
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $data = [
            'module_name' => $this->argument('module'),
            'case_name' => $this->argument('name'),
            'fields' => json_decode($this->argument('items'), true),
        ];

        $fieldsString = '';
        foreach ($data['fields']['fields'] as $item) {
            $fieldsString .= "'" . $item['name'] . "' => '',";
        }

        $stubsDir = __DIR__ . '/../stubs';
        if (is_dir(base_path('resources/vendor/laravel-crud-generator/stubs'))) {
            $stubsDir = base_path('resources/vendor/laravel-crud-generator/stubs');
        }

        $markup = File::get($stubsDir . '/test.stub');
        $markup = str_replace('DummyCrudName', $this->argument('name'), $markup);
        $markup = str_replace('dummyItems', $fieldsString, $markup);
        $markup = str_replace('crud_url', kebab_case($this->argument('module')) . '/' . kebab_case($this->argument('name')), $markup);

        File::put(base_path( 'app/Modules/' . $this->argument('module') . '/Tests/' . $this->argument('name') . 'Test.php'), $markup);

    }
}