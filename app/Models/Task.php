<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        "description",
        "group_id",
        "finished",
    ];

    protected $casts = [
        "finished" => "boolean",
    ];

    public function group()
    {
        return $this->hasOne(
            Group::class,
            "id",
            "group_id"
        );
    }
}
