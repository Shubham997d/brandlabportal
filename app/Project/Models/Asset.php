<?php

namespace App\Project\Models;

use App\Base\Models\User;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'created_by', 'notes', 'asset_type', 'asset_cost', 'notes', 'project_id', 'status_id'
    ];

    /**
     * Return the user this task belongs to.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    /**
     * Return the user who created this task.
     *
     * @return mixed
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

     
      
}
