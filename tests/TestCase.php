<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected $admin;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh');  
        $this->seed();
        $this->initialize_tests();
    }

    public function initialize_tests(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->firstUser = User::factory()->create();
        $this->firstUser->assignRole('user');

        $this->secondUser = User::factory()->create();
        $this->secondUser->assignRole('user');
    }
}
