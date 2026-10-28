<?php

namespace App\Enums;

enum UserRole: string
{
    case Farmer = 'farmer';
    case ExtensionOfficer = 'extension_officer';
    case Agronomist = 'agronomist';
    case Admin = 'admin';
}
