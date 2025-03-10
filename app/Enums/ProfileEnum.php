<?php

namespace App\Enums;

enum ProfileEnum: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case MEMBER = 'member';
}
