<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $connection = 'mongodb';
    protected $collection = 'roles';
    const UPDATED_AT = null;
    const CREATED_AT = 'created_at';
    protected $primaryKey = '_id';
    protected $fillable =["role_id","user_type","user_role","created_by","status"];


    public function setCreatedAtAttribute($value) {
        $this->attributes['created_at'] = date("Y-m-d h:i");
    }

    public function getCreatedAtAttribute($value) {
        $this->attributes['created_at'] = date("Y-m-d h:i");
    }
}
