<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'created_by',
        'assigned_to',
    ];

    
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Define relationship with User (creator)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Define relationship with User (assigned user)
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
