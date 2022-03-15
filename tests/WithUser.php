<?php

namespace Tests;

use App\Models\User;

trait WithUser
{
    private User $user;

    private function setUpUser(): void
    {
        $this->user = User::factory()->create();
    }
}
