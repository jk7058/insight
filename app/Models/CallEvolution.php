<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class CallEvolution extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $connection = 'mongodb';
    protected $collection = 'form_data';
    public $timestamps = true;
    protected $primaryKey = '_id';

    protected $fillable = ["form_name","form_version",
    "evaluation_status","channel","affiliation","evaluator","agent","hierarchy",
    "call","qa_Score","preAF_Score","AHT_Time","submit_time","custom_meta",
    "attributes","point_details","feedback","audit","calibration","escalation","re-escalation"
];


    public function setCreatedAtAttribute($value) {
        $this->attributes['created_at'] = date("Y-m-d");
    }

    public function setUpdatedAtAttribute($value) {
        $this->attributes['updated_at'] = date("Y-m-d");
    }
}
