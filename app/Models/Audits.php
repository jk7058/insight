<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Audits extends Model
{
    use HasFactory;

    protected $collection = 'form_data';
}