<?php
namespace App\Transformers;

use App\Transformers\BaseTransformer;

class TeamMemberTransformer extends BaseTransformer{

    public function __construct(){
        parent::__construct();
    }
    public function transform($result){

        $parsed_result = [
            'Member Name' => $result->user->name(),
            'Contact Number' => $result->user->phone_number,
            'Email' => $result->user->email,
            'Department' => $result->department,
            'Title' => $result->title,
            'Blood Group' => $result->blood_group,
            'Date of Birth' => $result->date_of_birth,
            'Date of Joining' => $result->date_of_join
        ];

        return $parsed_result;
    }
}
