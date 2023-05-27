<?php

namespace Tests\App\Http\Controllers;

use App\Models\Role;
use App\Models\Scale;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

# docker-compose exec app php artisan test --filter=ScaleControllerTest
class ScaleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * docker-compose exec app php artisan test --filter=ScaleControllerTest::testGetItemsNoPermissions
     *
     * Test the behavior of the get scales route when credentials no permissions are provided.
     * Verifies when credentials no permissions are provided.
     *
     * @return void
     */
    public function testGetItemsNoPermissions()
    {
        // Get Headers no permissions Administrator
        $headers = $this->getHeadersNoPermissionsAdministrator();

        // Send a GET request to try to get the items without permission
        $response = $this->withHeaders($headers)->get('api/scales');

        // Check the HTTP status of the response
        $response->assertStatus(403);
    }

    /**
     * docker-compose exec app php artisan test --filter=ScaleControllerTest::testGetItemsWithPermissions
     *
     * Test the behavior of the get scales route when credentials with permissions are provided.
     * Verifies when credentials with permissions are provided.
     *
     * @return void
     */
    public function testGetItemsWithPermissions()
    {
        // Get Headers with permissions Administrator
        $headers = $this->getHeadersWithPermissionsAdministrator();

        // Send a GET request to try to get the items without permission
        $response = $this->withHeaders($headers)->get('api/scales');

        // Check the HTTP status of the response
        $response->assertStatus(200);
    }

    /**
     * docker-compose exec app php artisan test --filter=ScaleControllerTest::testCreateNoPermissions
     *
     * Test the behavior of the create scales route when credentials no permissions are provided.
     * Verifies when credentials no permissions are provided.
     *
     * @return void
     */
    public function testCreateNoPermissions()
    {
        // Get Headers no permissions Administrator
        $headers = $this->getHeadersNoPermissionsAdministrator();

        // Send a POST request to try to create the item without permission
        $response = $this->withHeaders($headers)->post('api/scales', [
            "name" => "testing"
        ]);

        // Check the HTTP status of the response
        $response->assertStatus(403);
    }

    /**
     * docker-compose exec app php artisan test --filter=ScaleControllerTest::testCreateWithPermissions
     *
     * Test the behavior of the create scales route when credentials with permissions are provided.
     * Verifies when credentials with permissions are provided.
     *
     * @return void
     */
    public function  testCreateWithPermissions()
    {
        // Get Headers with permissions Administrator
        $headers = $this->getHeadersWithPermissionsAdministrator();

        // Send a POST request to try to create the item with permission
        $response = $this->withHeaders($headers)->post('api/scales', [
            "name" => "testing"
        ]);

        // Check the HTTP status of the response
        $response->assertStatus(201);
    }

    /**
     * docker-compose exec app php artisan test --filter=ScaleControllerTest::testUpdateNoPermissions
     *
     * Test the behavior of the update scales route when credentials no permissions are provided.
     * Verifies when credentials no permissions are provided.
     *
     * @return void
     */
    public function testUpdateNoPermissions()
    {
        // Create Scale
        $scale = Scale::factory()->create();

        // Get Headers no permissions Administrator
        $headers = $this->getHeadersNoPermissionsAdministrator();

        // Send a PUT request to try to update the item without permission
        $response = $this->withHeaders($headers)->put('api/scales/' . $scale->id, [
            "name" => "testing"
        ]);

        // Check the HTTP status of the response
        $response->assertStatus(403);
    }

    /**
     * docker-compose exec app php artisan test --filter=ScaleControllerTest::testUpdateWithPermissions
     *
     * Test the behavior of the update scales route when credentials with permissions are provided.
     * Verifies when credentials with permissions are provided.
     *
     * @return void
     */
    public function  testUpdateWithPermissions()
    {
        // Create Scale
        $scale = Scale::factory()->create();

        // Get Headers with permissions Administrator
        $headers = $this->getHeadersWithPermissionsAdministrator();

        // Send a PUT request to try to update the item with permission
        $response = $this->withHeaders($headers)->put('api/scales/' . $scale->id, [
            "name" => "testing"
        ]);

        // Check the HTTP status of the response
        $response->assertStatus(200);
    }

    /**
     * docker-compose exec app php artisan test --filter=ScaleControllerTest::testDeleteNoPermissions
     *
     * Test the behavior of the delete scales route when credentials no permissions are provided.
     * Verifies when credentials no permissions are provided.
     *
     * @return void
     */
    public function testDeleteNoPermissions()
    {
        // Create Scale
        $scale = Scale::factory()->create();

        // Get Headers no permissions Administrator
        $headers = $this->getHeadersNoPermissionsAdministrator();

        // Send a PUT request to try to delete the item without permission
        $response = $this->withHeaders($headers)->delete('api/scales/' . $scale->id);

        // Check the HTTP status of the response
        $response->assertStatus(403);
    }

    /**
     * docker-compose exec app php artisan test --filter=ScaleControllerTest::testDeleteWithPermissions
     *
     * Test the behavior of the delete scales route when credentials with permissions are provided.
     * Verifies when credentials with permissions are provided.
     *
     * @return void
     */
    public function  testDeleteWithPermissions()
    {
        // Create Scale
        $scale = Scale::factory()->create();

        // Get Headers with permissions Administrator
        $headers = $this->getHeadersWithPermissionsAdministrator();

        // Send a PUT request to try to delete the item with permission
        $response = $this->withHeaders($headers)->delete('api/scales/' . $scale->id);

        // Check the HTTP status of the response
        $response->assertStatus(200);
    }

    /**
     * Generate a JWT token for the user no permissions Administrator
     *
     * @return array
     */
    private function getHeadersNoPermissionsAdministrator()
    {
        // Create a Role and a Scale
        $role = Role::factory()->create();
        $scale = Scale::factory()->create();

        // Create a User
        $user = User::factory()->create([
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'scale_id' => $scale->id
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => 'password',
        ];

        // Generate a JWT token for the user
        $token = JWTAuth::attempt($credentials);

        // Set the JWT token in the request
        return ['Authorization' => 'Bearer ' . $token];
    }

    /**
     * Generate a JWT token for the user with permissions Administrator
     *
     * @return array
     */
    private function getHeadersWithPermissionsAdministrator()
    {
        // Create a Role and a Scale
        $role = Role::factory()->create(['name' => 'Administrator']);
        $scale = Scale::factory()->create();

        // Create a User
        $user = User::factory()->create([
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'scale_id' => $scale->id
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => 'password',
        ];

        // Generate a JWT token for the user
        $token = JWTAuth::attempt($credentials);

        // Set the JWT token in the request
        return ['Authorization' => 'Bearer ' . $token];
    }
}
