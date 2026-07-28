<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use Mockery;
use Mockery\MockInterface;

class TestAuth extends TestCase
{
    private $userRepository;
    private $authService;

    protected function setUp(): void
    {
        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->authService = new AuthService($this->userRepository);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->userRepository->shouldReceive('getUserByUsername')->andReturn(new User($username, $password));

        $result = $this->authService->login($username, $password);

        $this->assertEquals(true, $result);
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'wrongpassword';

        $this->userRepository->shouldReceive('getUserByUsername')->andReturn(null);

        $result = $this->authService->login($username, $password);

        $this->assertEquals(false, $result);
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->userRepository->shouldReceive('getUserByUsername')->andReturn(null);
        $this->userRepository->shouldReceive('saveUser')->andReturn(new User($username, $password));

        $result = $this->authService->register($username, $password);

        $this->assertEquals(true, $result);
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->userRepository->shouldReceive('getUserByUsername')->andReturn(new User($username, $password));

        $result = $this->authService->register($username, $password);

        $this->assertEquals(false, $result);
    }

    public function tearDown(): void
    {
        Mockery::close();
    }
}


This test file covers the following scenarios:

1. Successful login with correct credentials
2. Failed login with incorrect credentials
3. Successful registration with new credentials
4. Failed registration with existing credentials

The `setUp` method creates a mock instance of the `UserRepository` class and injects it into the `AuthService` instance. The `tearDown` method closes the mock instance to prevent memory leaks.

Each test method uses the `shouldReceive` method to define the expected behavior of the `UserRepository` instance. The `login` and `register` methods of the `AuthService` instance are then called with the test data, and the results are asserted using the `assertEquals` method.