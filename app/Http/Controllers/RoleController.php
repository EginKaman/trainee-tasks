<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\RoleCollection;
use App\Models\Role;

class RoleController extends Controller
{
    public function __invoke(): RoleCollection
    {
        return new RoleCollection(Role::all());
    }
}
