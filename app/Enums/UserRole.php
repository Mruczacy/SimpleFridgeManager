<?php

namespace App\Enums;

class UserRole
{
    const ADMIN = 'Admin';
    const USER = 'User';

    const Types = [
        self::ADMIN => 'Admin',
        self::USER => 'User',
    ];
}

?>
