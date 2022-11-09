<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class FormConditionalLogic extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $connection = 'mongodb';
    protected $collection = 'form_conditional_logic';
    public $timestamps = true;
    protected $primaryKey = '_id';

    protected $fillable = ["Form_Name","Form_Id","Form_Version","Category",
    "Category_ID","AttributeID","Attribute_Name" ,"Attribute_Type","Action_Value","Logic_Type","Attributes_Impacted","Attributes_Values","Attributes_Disabled",
    "Options_Values","Impactatt_type" , "show_enable_flag", "Definition"];
}
