<?php

namespace App\Enums;

enum UserRole: string
{
    case Superadmin = 'superadmin';
    case Owner = 'owner';
    case Admin = 'admin';
    case Karyawan = 'karyawan';
}
