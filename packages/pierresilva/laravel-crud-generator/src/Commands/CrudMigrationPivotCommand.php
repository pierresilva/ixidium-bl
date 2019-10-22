<?php

namespace pierresilva\CrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudMigrationPivotCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:pivot';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:pivot
                            {tableOne : The name of the first table.}
                            {tableTwo : The name of the second table.}
                            {--foreign : Create foreigns keys.}
                            {--module-name= : Root Module namespace to place files in there.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration pivot class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Migration';

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
    }

    /**
     * Parse the name and format.
     *
     * @param  string $name
     * @return string
     */
    protected function parseName()
    {
        $tables = array_map('str_singular', $this->getSortedTableNames());
        $name = implode('', array_map('ucwords', $tables));

        return "Create{$name}PivotTable";
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return config('crudgenerator.custom_template')
            ? config('crudgenerator.path') . '/pivot.stub'
            : __DIR__ . '/../stubs/pivot.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name = null)
    {
        if ($this->option('module-name')) {
            return base_path() . '/app/Modules/' . $this->option('module-name') . '/Database/Migrations/' . date('Y_m_d_His') .
                '_create_' . $this->getPivotTableName() . '_pivot_table.php';
        }
        return base_path() . '/database/migrations/' . date('Y_m_d_His') .
            '_create_' . $this->getPivotTableName() . '_pivot_table.php';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name = null)
    {

        $foreign = <<<EOD
        \$table->foreign('{{columnOne}}_id')->references('id')->on('{{tableOne}}')->onDelete('cascade');
        \$table->foreign('{{columnTwo}}_id')->references('id')->on('{{tableTwo}}')->onDelete('cascade');        
EOD;

        $stub = $this->files->get($this->getStub());

        return $this->replaceForeign($stub, $foreign)
            ->replacePivotTableName($stub)
            ->replaceSchema($stub)
            ->replaceClass($stub, $this->parseName());
    }

    /**
     * Apply the name of the pivot table to the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replacePivotTableName(&$stub)
    {
        $stub = str_replace('{{pivotTableName}}', $this->getPivotTableName(), $stub);

        return $this;
    }

    protected function replaceForeign(&$stub, $foreign)
    {
        if($this->option('foreign')) {
            $stub = str_replace('{{foreign}}', $foreign, $stub);
        } else {
            $stub = str_replace('{{foreign}}', "", $stub);
        }

        return $this;
    }

    /**
     * Apply the correct schema to the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceSchema(&$stub)
    {
        $tables = $this->getSortedTableNames();

        $stub = str_replace(
            ['{{columnOne}}', '{{columnTwo}}', '{{tableOne}}', '{{tableTwo}}'],
            array_merge(array_map('str_singular', $tables), $tables),
            $stub
        );

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = str_replace('DummyClass', $name, $stub);

        return $stub;
    }

    /**
     * Get the name of the pivot table.
     *
     * @return string
     */
    protected function getPivotTableName()
    {
        return implode('_', array_map('str_singular', $this->getSortedTableNames()));
    }

    /**
     * Sort the two tables in alphabetical order.
     *
     * @return array
     */
    protected function getSortedTableNames()
    {
        $tables = [
            strtolower($this->argument('tableOne')),
            strtolower($this->argument('tableTwo'))
        ];

        sort($tables);

        return $tables;
    }
}
