<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class FormStructure extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
   //use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'form_structure';
    public $timestamps = true;
    protected $primaryKey = '_id';
    protected $fillable = ["client_id","lobs",
    "form_unique_id","form_name","form_version","form_created_date","custom_meta_fields","category","header"];

}
