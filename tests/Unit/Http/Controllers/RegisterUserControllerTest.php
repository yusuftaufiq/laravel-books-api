<?php

namespace Tests\Unit\Http\Controllers;

use App\Contracts\UserInterface;
use App\Http\Controllers\Api\RegisterUserController;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use Mockery\MockInterface;
use Tests\TestCase;

class RegisterUserControllerTest extends TestCase
{
    public function testRegisterNewUser()
    {
        $this->mock(UserInterface::class, function (MockInterface $mock): void {
            $mock->shouldReceive('create')->once()->with([])->andReturnSelf();
        });
        $this->mock(RegisterUserRequest::class, function (MockInterface $mock): void {
            $mock->shouldReceive('validated')->once()->withNoArgs()->andReturn([]);
        });

        $registerUserController = $this->app->make(abstract: RegisterUserController::class);
        $user = $this->app->call($registerUserController);

        $this->assertInstanceOf(UserResource::class, $user);
    }
}
