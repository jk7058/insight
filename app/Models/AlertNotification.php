<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertNotification extends Model
{
    use HasFactory;

    protected $collection = 'alert_notifications';
}
