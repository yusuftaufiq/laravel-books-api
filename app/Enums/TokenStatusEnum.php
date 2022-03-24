<?php

namespace App\Enums;

enum TokenStatusEnum: string
{
    case Active = 'Active';
    case Expired = 'Expired';
    case Revoked = 'Revoked';
}
