<?php

namespace NextApps\VerificationCode\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use NextApps\VerificationCode\VerificationCodeServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use WithFaker;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
        $this->withFactories(__DIR__.'/../database/factories');

        Notification::fake();
    }

    /**
     * Load package service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            VerificationCodeServiceProvider::class,
        ];
    }

    /**
     * Set up the database.
     *
     * @return void
     */
    protected function setUpDatabase()
    {
        include_once __DIR__.'/../database/migrations/create_verification_codes_table.php.stub';

        (new \CreateVerificationCodesTable)->up();
    }
}
