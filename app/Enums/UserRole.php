<?php

namespace App\Enums;

class UserRole
{
    public const ADMIN = 'Admin';
    public const USER = 'User';

    public const TYPES = [
        self::ADMIN => 'Admin',
        self::USER => 'User',
    ];
}
