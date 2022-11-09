<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class FormDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $connection = 'mongodb';
    protected $collection = 'form_details';
    public $timestamps = true;
    protected $primaryKey = '_id';    

    protected $fillable = ["form_unique_id","client_id",
    "form_name","form_version","form_attributes","category_count","rating_attr","rating_attr_name","tb_name","display_name","form_status",
    "effective","pass_rate","form_weightage","feedback_tat","user_type","user_id","custom_meta","custom1","custom2","custom3","custom4"];
}
