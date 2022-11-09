<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Escalation extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'escalation';

    protected $primaryKey = '_id';
    protected $casts = [
        'escalation_created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'escalation_by',
        'authorizer_level',
        'authorize_level_1',
        'authorize_level_2',
        'authorize_1_tat',
        'authorize_2_tat',
        'resolver_by',
        'resolution_tat',
        'reescalation_required',
        'reescalation_by',
        'reescalation_level',
        'reescalation_authorized',
        'reescalation_authorize_tat',
        'reescalation_resolver',
        'reescalation_resolution_tat',
        'escalation_created_at',
        'escalation_status',
        'updated_at'
    ];
}
