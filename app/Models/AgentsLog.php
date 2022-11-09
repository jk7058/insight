<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentsLog extends Model
{
    use HasFactory;
    protected $collection = 'agents_logs';
    
    protected $guarded = [];
}
