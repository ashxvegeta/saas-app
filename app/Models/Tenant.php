<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    //
    protected $fillable = [
      'name',
      'owner_id'
    ];
    
    // tenant can have many users and user can belong to many tenants
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role');
    }

    // tenant can have many projects
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
