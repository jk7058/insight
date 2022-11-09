<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends Model
{
    use HasFactory;
    protected $collection = 'agents';
    
    protected $fillable = [
        'agent_id', 
        'agent_name',
        'agent_email',
        'hierarchy_id',
        'Level_1',
        'Level_2',
        'Level_3',
        'AddedOn',
        'doj',
        'EffectiveDate',
        'InactiveDate',
        'Status'
    ];

    public function logs()
    {
        return $this->hasOne(AgentsLog::class,  'id', 'agent_id');
    }

    public function hierarchy()
    {
        return $this->belongsTo(Hierarchy::class, 'hierarchy_id');
    }
}
