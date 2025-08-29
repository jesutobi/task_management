<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $table = 'task';

    // i used fillable here instead of guarded to define which field can be mass assigned
    // and also this prevents unexpected fields from being written to the database using Sql Injection
    protected $fillable = ['title', 'description', 'priority', 'project_id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
