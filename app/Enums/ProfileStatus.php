<?php

namespace App\Enums;

enum ProfileStatus: string
{
    use StringEnumTrait;

    case INACTIVE = 'inactive';
    case PENDING = 'pending';
    case ACTIVE = 'active';
}
