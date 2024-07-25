<?php

namespace App\Exports;

use App\Models\TeamMember\TeamMember;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class TeammembersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $member = DB::table('team_members')->join('users', 'users.id', '=', 'team_members.user_id')->select('users.first_name','users.middle_name','users.last_name','users.country_code','users.phone_number','team_members.department','team_members.designation','team_members.blood_group','team_members.dob','team_members.doj',DB::raw('(case when team_members.banned = 1 then "banned" else "approved" end) as status'),'users.last_login')->get();
        return $member;
    }
    public function headings(): array
    {
        return [
            'first_name',
            'middle_name',
            'last_name',
            'country_code',
            'phone_number',
            'department',
            'designation',
            'blood_group',
            'dob',
            'doj',
            'status',
            'last_login'
        ];
    }
}
