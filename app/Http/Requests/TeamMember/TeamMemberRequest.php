<?php

namespace App\Http\Requests\TeamMember;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Auth\SentinelUser;

class TeamMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'first_name' => 'required|max:191',
            'middle_name' => 'nullable|max:191',
            'last_name' => 'required|max:191',
            'email' => 'required|email|unique:users,email|max:191',
            'country_code' => 'required|min:0|max:4',
            'phone_number' => 'required|min:10|max:12|unique:users,phone_number',
            'profile_photo' => 'sometimes|mimes:jpg,jpeg,png|max:2048',
            'department' => 'required',
            'designation' => 'required',
            'employee_id' => 'required|unique:team_members,employee_id',
            'blood_group' => 'nullable',
            'dob' => 'nullable',
            'doj' => 'nullable',
        ];
        if ($this->method() == 'POST' && !isset($this->member)) {
            $rules['password'] = 'required|min:8|max:150|confirmed';
            return $rules;
        } else {
            $team_member_token = $this->team_member;
            $user = SentinelUser::whereHas('teamMember', function($query) use($team_member_token){
                return $query->where('id', decrypt_id_info($team_member_token));
            })->first();
            $rules['email'] = 'required|email|max:191|unique:users,email,'. $user->id;
            $rules['phone_number'] = 'required|min:10|max:12|unique:users,phone_number,' . $user->id;
            $rules['employee_id'] = 'required|unique:team_members,employee_id,' . decrypt_id_info($team_member_token);
            return $rules;
        }
    }
}
