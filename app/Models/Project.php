<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Project extends Model
{
    use HasFactory;

    const OPEN_STATUS = 0;
    const CLOSED_STATUS = 1;

    protected $fillable = [
        'title',
        'description',
        'deadline',
        'status',
        'client_id',
        'user_id',
    ];

    protected static function booted()
    {
        static::created(function ($project) {
            AssignmentAlert::create([
                'is_noted' => false,
                'project_id' => $project->id,
                'user_id' => $project->user_id,
            ]);
        });
    }

    public function getFormattedDeadlineAttribute()
    {
        return (new \DateTime($this->deadline))->format('m/d/Y');
    }

    public function getFormattedStatusAttribute()
    {
        return $this->status === self::OPEN_STATUS ? 'Open' : 'Closed';
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function alerts()
    {
        return $this->hasMany(AssignmentAlert::class);
    }
}
