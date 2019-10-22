<?php

#use Illuminate\Foundation\Testing\TestCase;
use Orchestra\Testbench\TestCase;

/**
 * Class TestCaseDB
 */
abstract class TestCaseDB extends TestCase
{
    protected $database = 'testbench';

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [Timegridio\Concierge\TimegridioConciergeServiceProvider::class];
    }

    /**
     * Setup the DB before each test.
     */
    public function setUp()
    {
        parent::setUp();

        // This should only do work for Sqlite DBs in memory.
        $artisan = $this->app->make('Illuminate\Contracts\Console\Kernel');

        app('db')->beginTransaction();

        $this->migrate($artisan);
        $this->migrate($artisan, '/../../../../migrations');

        // Set up the User Test Model
        app('config')->set('auth.providers.users.driver', 'eloquent');
        app('config')->set('auth.providers.users.model', Timegridio\Tests\Models\User::class);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Rollback transactions after each test.
     */
    public function tearDown()
    {
        app('db')->rollback();
    }

    /**
     * Get application timezone.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return string|null
     */
    protected function getApplicationTimezone($app)
    {
        return 'UTC';
    }

    /**
     * Migrate the migrations files
     *
     * @param        $artisan
     * @param string $path
     */
    private function migrate($artisan, $path = '/../../../../migrations')
    {
        $artisan->call('migrate', [
            '--database' => $this->database,
            '--path'     => $path,
        ]);
    }
}
