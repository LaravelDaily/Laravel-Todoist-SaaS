<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\MultiTenantModelTrait;

class Project extends Model
{
    use SoftDeletes, MultiTenantModelTrait;

    public $table = 'projects';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'active',
        'description',
        'created_by_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function projectTasks()
    {
        return $this->hasMany(Task::class, 'project_id', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function collaborators()
    {
        return $this->belongsToMany(User::class)->withPivot('confirmed_at', 'email');
    }
}
