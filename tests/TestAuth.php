<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;

    protected function setUp(): void
    {
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->authService = new AuthService($this->authRepository);
    }

    public function testLoginSuccess()
    {
        // Mock successful login
        $this->authRepository->expects($this->once())
            ->method('login')
            ->with('username', 'password')
            ->willReturn(new User('username', 'password'));

        // Call login method
        $user = $this->authService->login('username', 'password');

        // Assert login success
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('username', $user->getUsername());
        $this->assertEquals('password', $user->getPassword());
    }

    public function testLoginFailure()
    {
        // Mock failed login
        $this->authRepository->expects($this->once())
            ->method('login')
            ->with('username', 'password')
            ->willReturn(null);

        // Call login method
        $user = $this->authService->login('username', 'password');

        // Assert login failure
        $this->assertNull($user);
    }

    public function testRegisterSuccess()
    {
        // Mock successful registration
        $this->authRepository->expects($this->once())
            ->method('register')
            ->with('username', 'password')
            ->willReturn(new User('username', 'password'));

        // Call register method
        $user = $this->authService->register('username', 'password');

        // Assert registration success
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('username', $user->getUsername());
        $this->assertEquals('password', $user->getPassword());
    }

    public function testRegisterFailure()
    {
        // Mock failed registration
        $this->authRepository->expects($this->once())
            ->method('register')
            ->with('username', 'password')
            ->willReturn(null);

        // Call register method
        $user = $this->authService->register('username', 'password');

        // Assert registration failure
        $this->assertNull($user);
    }

    public function testIsLoggedIn()
    {
        // Mock logged in user
        $this->authRepository->expects($this->once())
            ->method('isLoggedIn')
            ->willReturn(true);

        // Call isLoggedIn method
        $isLoggedIn = $this->authService->isLoggedIn();

        // Assert logged in
        $this->assertTrue($isLoggedIn);
    }

    public function testIsLoggedInFailure()
    {
        // Mock not logged in user
        $this->authRepository->expects($this->once())
            ->method('isLoggedIn')
            ->willReturn(false);

        // Call isLoggedIn method
        $isLoggedIn = $this->authService->isLoggedIn();

        // Assert not logged in
        $this->assertFalse($isLoggedIn);
    }
}


This test file covers the following scenarios:

1. Successful login
2. Failed login
3. Successful registration
4. Failed registration
5. Logged in user
6. Not logged in user

Each test method uses PHPUnit's mocking feature to simulate the behavior of the `AuthRepository` class, which is used by the `AuthService` class. The `AuthService` class is then tested with different inputs to ensure it behaves as expected.