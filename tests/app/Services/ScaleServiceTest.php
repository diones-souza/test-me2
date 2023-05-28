<?php

namespace Tests\App\Http\Repositories;

use App\Repositories\ScaleRepository;
use App\Services\ScaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

# docker-compose exec app php artisan test --filter=ScaleServiceTest
class ScaleServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * docker-compose exec app php artisan test --filter=ScaleServiceTest::testExtendsClassService
     *
     * Checks if the service class is behaving as expected
     *
     * @return void
     */
    public function testExtendsClassService()
    {
        // New instance Service
        $service = new ScaleService(new ScaleRepository());

        // Assert that the 'checkPermissions' exists in the ScaleService.
        // This test ensures that the desired function is implemented and available for use.
        $this->assertTrue(method_exists($service, 'checkPermissions'));
    }
}
