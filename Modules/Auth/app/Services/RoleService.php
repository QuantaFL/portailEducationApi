<?php

namespace Modules\Auth\Services;

use Exception;
use Modules\Auth\Models\Role;

class RoleService
{
    public function getAllRoles()
    {
        return Role::all();
    }

    public function createRole(array $data)
    {
        return Role::create($data);
    }

    public function getRoleById($id)
    {
        return Role::findOrFail($id);
    }

    public function updateRole($id, array $data)
    {
        $role = Role::findOrFail($id);
        $role->update($data);
        return $role;
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return true;
    }
}
