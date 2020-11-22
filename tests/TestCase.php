<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function generate_api()
    {
        $user = User::factory()->create();
        $api_key = base64_encode(random_bytes(40));
        $user->api_token = $api_key;
        $user->save();
        return $api_key;
    }
}
