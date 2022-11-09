<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Alert extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $connection = 'mongodb';
    protected $collection = 'alerts';
    public $timestamps = true;
    protected $primaryKey = '_id';    
    protected $fillable = ["alert_name","evaluator_affiliation",
    "alert_status","alert_type","alert_by","switchAndOr","alert_frequency","form_name",
    "form_attributes","measure_type","measureOprtor","measure_value","measure_equals_y_n","message_temp",
    "custom1","custom2","custom3","custom4","alert_reciever_list","other_alert_reciever_list","empid",
    "created_by","created_by_type","include_me", "alert_send_to", "notify_all"];

    
    public function setCreatedAtAttribute($value) { 
        $this->attributes['created_at'] = date("Y-m-d"); 
    }

    public function setUpdatedAtAttribute($value) { 
        $this->attributes['updated_at'] = date("Y-m-d"); 
    }
}
