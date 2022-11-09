<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Hierarchy extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $connection = 'mongodb';
    protected $collection = 'hierarchy';
    const UPDATED_AT = null;
    const CREATED_AT = 'created_at';
    protected $primaryKey = '_id';
    protected $fillable =["c1","c2","c3","c4","status"];


    public function setCreatedAtAttribute($value) {
        $this->attributes['created_at'] = date("Y-m-d");
    }
}
