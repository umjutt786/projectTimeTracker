<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'project_id', 'start_time', 'end_time', 'hours_logged', 'keyboard_activity', 'mouse_activity'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function screenshots()
    {
        return $this->hasMany(Screenshot::class);
    }
}
