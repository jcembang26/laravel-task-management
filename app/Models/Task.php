<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'title',
        'contents',
        'status_id',
        'is_publised',
        'image',
        'parent_id',
    ];

    /**
     * Get the status associated with the Task
     */
    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    /**
     * Get all of the subTasks for the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id', 'id');
    }
}
