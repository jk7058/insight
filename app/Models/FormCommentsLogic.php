<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class FormCommentsLogic extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $connection = 'mongodb';
    protected $collection = 'form_comments_logic';
    public $timestamps = true;
    protected $primaryKey = '_id';

    protected $fillable = ["Form_Name","Form_Version","Category",
    "Category_ID","AttributeID","Attribute_Type","Action_Value","Attributes_Impacted","Comments_Required"];
}
