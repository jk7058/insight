<?php

namespace App\Models;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $primaryKey = '_id';
    protected $casts = [
        'userCreatedAt' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'userId',
        'name',
        'username',
        'password',
        'passwordExpiredOn',
        'passwordChangedOn',
        'passwordChangeStatus',
        'userEmail',
        'userType',
        'userRole',
        'userCreatedAt',
        'userStatus',
        'userDOJ',
        'userEffectiveDate',
        'LastUpdatedDate',
        'ProxyAccess',
        'ProxyUpdated',
        'LastLoginTime',
        'IsAuthorizer',
        'IsReviewer',
        'usersHierachy',
        'Level-1',
        'Level-2',
        'Level-3',
        'ModuleAccess',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
