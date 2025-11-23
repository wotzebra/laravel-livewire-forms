<?php

namespace Tests;

use Codedor\LivewireForms\LivewireFormsServiceProvider;
use Illuminate\Http\UploadedFile;
use Livewire\LivewireServiceProvider;
use PeterColes\Countries\CountriesServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:Inq+Xktf8hUV2iyfvKnYOrOFU7CQ7IuOHI2n7AnxisI=');

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        UploadedFile::macro('save', function () {
            return 'test';
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            LivewireFormsServiceProvider::class,
            CountriesServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Fixtures/Database/migrations/create_attachments_table.php');
    }
}
