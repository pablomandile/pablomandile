<?php

namespace App\Enums;

enum TechCategory: string
{
    case Backend = 'backend';
    case Frontend = 'frontend';
    case Database = 'database';
    case Tools = 'tools';
}
