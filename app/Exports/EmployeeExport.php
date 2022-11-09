<?php

namespace App\Exports;

use App\Models\Employee;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeExport implements FromQuery, WithHeadings, WithMapping
{
    // /**
    //  * @return \Illuminate\Support\Collection
    //  */
    // public function collection()
    // {
    //     return User::all();
    // }

    use Exportable;


    public function headings(): array
    {
        return [
            'userId',
            'name',
            'username',
            'userEmail',
            'userType',
            'userRole',
            'userCreatedAt',
            'userStatus',
            'userDOJ',
            'userEffectiveDate',
            'ProxyAccess',
            'ProxyUpdated',
            'IsAuthorizer',
            'IsReviewer',
            'usersHierachy.c1',
            'usersHierachy.c2',
            'usersHierachy.c3',
            'usersHierachy.c4',
            'Level_1.userId',
            'Level_1.name',
            'Level_1.email',
            'Level_1.userType',
            'Level_1.userRole',
            'Level_1.userStatus',
            'Level_1.userId',
            'Level_2.name',
            'Level_2.email',
            'Level_2.userType',
            'Level_2.userRole',
            'Level_2.userStatus',
            'Level_2.userId',
            'Level_3.name',
            'Level_3.email',
            'Level_3.userType',
            'Level_3.userRole',
            'Level_3.userStatus',
            'ModuleAccess.ModuleName',
            'ModuleAccess.AccessType',
            'ModuleAccess.AccessLevel',
        ];
    }


    public function __construct(string $name = NULL)
    {
        $this->name = $name;
    }

    public function query()
    {
        $userquery = Employee::query();
        if ($this->name) {
            $userquery->where('username', $this->name);
        }
        return $userquery;
    }

    // public function prepareRows($rows)
    // {
    //     return $rows->transform(function ($user) {
    //         \Log::info($user);
    //         return $user;
    //     });
    // }

    public function map($user): array
    {
        $userHeic1 = ($user->usersHierachy && !empty($user->usersHierachy['c1'])) ? implode(";", $user->usersHierachy['c1']) : '';
        $userHeic2 = ($user->usersHierachy && !empty($user->usersHierachy['c2'])) ? implode(";", $user->usersHierachy['c2']) : '';
        $userHeic3 = ($user->usersHierachy && !empty($user->usersHierachy['c3'])) ? implode(";", $user->usersHierachy['c3']) : '';
        $userHeic4 = ($user->usersHierachy && !empty($user->usersHierachy['c4'])) ? implode(";", $user->usersHierachy['c4']) : '';
        $proxyaccess = (!empty($user->ProxyAccess)) ? implode(";", $user->ProxyAccess) : "";
        $level1 =  $user['Level-1'];
        $level2 =  $user['Level-2'];
        $level3 =  $user['Level-3'];
        $usermodule = $user->ModuleAccess;
        $mname = $mtype = $mlevel = [];
        if (!empty($usermodule)) {
            foreach ($usermodule as $mod) {
                $mname[] = $mod['ModuleName'];
                $mtype[] = $mod['AccessType'];
                $mlevel[] = $mod['AccessLevel'];
            }
        }

        return [
            $user->userId,
            $user->name,
            $user->username,
            $user->userEmail,
            $user->userType,
            $user->userRole,
            $user->userCreatedAt,
            $user->userStatus,
            $user->userDOJ,
            $user->userEffectiveDate,
            $proxyaccess,
            $user->ProxyUpdated,
            $user->IsAuthorizer,
            $user->IsReviewer,
            $userHeic1,
            $userHeic2,
            $userHeic3,
            $userHeic4,
            (!empty($level1['userId'])) ? $level1['userId'] : '',
            (!empty($level1['name'])) ? $level1['name'] : '',
            (!empty($level1['email'])) ? $level1['email'] : '',
            (!empty($level1['userType'])) ? $level1['userType'] : '',
            (!empty($level1['userRole'])) ? $level1['userRole'] : '',
            (!empty($level1['userStatus'])) ? $level1['userStatus'] : '',
            (!empty($level2['userId'])) ? $level2['userId'] : '',
            (!empty($level2['name'])) ? $level2['name'] : '',
            (!empty($level2['email'])) ? $level2['email'] : '',
            (!empty($level2['userType'])) ? $level2['userType'] : '',
            (!empty($level2['userRole'])) ? $level2['userRole'] : '',
            (!empty($level2['userStatus'])) ? $level2['userStatus'] : '',
            (!empty($level3['userId'])) ? $level3['userId'] : '',
            (!empty($level3['name'])) ? $level3['name'] : '',
            (!empty($level3['email'])) ? $level3['email'] : '',
            (!empty($level3['userType'])) ? $level3['userType'] : '',
            (!empty($level3['userRole'])) ? $level3['userRole'] : '',
            (!empty($level3['userStatus'])) ? $level3['userStatus'] : '',
            implode(";", $mname),
            implode(";", $mtype),
            implode(";", $mlevel),
        ];
    }
}