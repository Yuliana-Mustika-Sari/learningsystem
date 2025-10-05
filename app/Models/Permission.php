<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'group',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }

    public function scopeByGruop($query, $group)
    {
        return $query->where('group', $group);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

}
