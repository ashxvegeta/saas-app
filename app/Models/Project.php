<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //

    protected $fillable = [
        'tenant_id',
        'name'
    ];

    public function tenant()
    {
    return $this->belongsTo(Tenant::class);
    }


    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
