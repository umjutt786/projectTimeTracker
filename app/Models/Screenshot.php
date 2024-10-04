<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screenshot extends Model
{
    use HasFactory;

    protected $fillable = ['work_log_id', 'screenshot_path', 'captured_at'];

    public function workLog()
    {
        return $this->belongsTo(WorkLog::class);
    }
}
