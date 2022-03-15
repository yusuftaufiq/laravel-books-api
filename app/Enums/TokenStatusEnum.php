<?php

namespace App\Enums;

enum TokenStatusEnum: string
{
    case Active = 'active';
    case Expired = 'expired';
    case Revoked = 'revoked';
}
