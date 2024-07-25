<?php

namespace App\Imports;

use App\TeamMember;
use Maatwebsite\Excel\Concerns\ToModel;

class TeammembersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new TeamMember([
            'first_name' => $row['first_name'],
            'middle_name' => $row['middle_name'],
            'last_name' => $row['last_name'],
            'country_code' => $row['country_code'],
            'phone_number' => $row['phone_number'],
            'department' => $row['phone_number'],
            'designation' => $row['designation'],
            'blood_group' => $row['blood_group'],
            'dob' => $row['dob'],
            'doj' => $row['doj'],
            'last_login' => $row['last_login']
        ]);
    }
}
