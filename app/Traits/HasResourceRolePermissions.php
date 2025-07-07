<?php

namespace App\Traits;

use App\Models\ResourcePermission;

trait HasResourceRolePermissions
{
    public static function getPermittedRoles(): array
    {
        return ResourcePermission::where('resource_name', static::$model)
            ->pluck('role_name')
            ->flatMap(fn($roles) => array_map('trim', explode(',', $roles)))
            ->unique()
            ->values()
            ->toArray();
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(self::getPermittedRoles());
    }
}
