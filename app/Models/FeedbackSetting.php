<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeedbackSetting extends Model
{
    use HasFactory;
    protected $collection = 'feedback_settings';
    
    protected $fillable = [
        'feedback_by', 
        'feedback_tat',
        'acknowledged_by'
    ];
}
