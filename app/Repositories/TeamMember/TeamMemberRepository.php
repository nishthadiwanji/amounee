<?php
namespace App\Repositories\TeamMember;

use App\Models\TeamMember\TeamMember;
use App\Models\FileInfo\FileInfo;
use App\Repositories\UserRepository;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\FileRepository;
use App\Mail\WelcomeTeamMember;
use App\Repositories\EloquentDBRepository;
use Sentinel;
use Mail;

class TeamMemberRepository extends EloquentDBRepository
{
    protected $member;
    protected $fileinfoRepo;
    public function __construct(TeamMember $member, FileRepository $fileinfoRepo){
        $this->member = $member;
        $this->fileinfoRepo = $fileinfoRepo;
    }
    public function getInformation($id){
        $member = $this->member->with(['user'])
                ->find($id);
        return $member;
    }
    public function search($search){
        $members = (new TeamMember)->newQuery();
        $members->where(function ($query) use ($search) {
            $query->whereHas('user',function($in_query) use ($search){
                $in_query->where('first_name','like','%'.$search.'%')
                    ->orWhere('last_name','like','%'.$search.'%')
                    ->orWhere('phone_number','like','%'.$search.'%')
                    ->orWhere('email','like','%'.$search.'%')
                    ->orWhere(function($query) use ($search) {
                        $query->where(\DB::raw("concat(`users`.`first_name`,' ',`users`.`last_name`)"),'like', "%".$search."%");
                    });
            });
        });
        return $members;
    }
    public function withUser($query){
        return $query->with(['user'=>function($q){
            $q->select('id','first_name','last_name','phone_number','country_code','email','banned');
        }])->orderBy('updated_at','DESC');
    }
    
    public function createTeamMember($file,$attributes, $added_by){
        $user = AuthRepository::createUser('team_member', $attributes);
        if($user != false){
            $data = [
                'user_id' => $user->id,
                'first_name' => $attributes['first_name'],
                'middle_name' => $attributes['middle_name'],
                'last_name' => $attributes['last_name'],
                'employee_id' => $attributes['employee_id'],
                'department' => $attributes['department'],
                'designation' => $attributes['designation'],
                'blood_group' => $attributes['blood_group'],
                'dob' => $attributes['dob'],
                'doj' => $attributes['doj'],
                'added_by' => $added_by
            ];
            $response = $this->member->create($data);

            if($file != null){
                $dir = 'Members/'.$response->id_token;
                $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
                $response->update(['profile_photo'=>$fileinfo->id]);
            }

            // Only if a user is logged in and a user email is available that is the only case when an email should be sent
            if(!empty($user->email) && !Sentinel::guest()){
                $queue = (new WelcomeTeamMember($user,$response))->onConnection('database')->onQueue('welcomeEmails');
                Mail::to($user->email)->queue($queue);
            }
            return $response;
        }
        return false;
    }
    public function updateTeamMember($member,$file,$attributes){
        $user = AuthRepository::updateUser($member->user, $attributes);
        if($user != false){
            $data = [
                'first_name' => $attributes['first_name'],
                'middle_name' => $attributes['middle_name'],
                'last_name' => $attributes['last_name'],
                'employee_id' => $attributes['employee_id'],
                'department' => $attributes['department'],
                'designation' => $attributes['designation'],
                'blood_group' => $attributes['blood_group'],
                'dob' => $attributes['dob'],
                'doj' => $attributes['doj'],
            ];
            if($file != null){
                $dir = 'Members/'.$member->id_token;
                if($member->profile_photo == null){
                    $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir,$file,getStorageDisk(),true);
                    $data['profile_photo'] = $fileinfo->id;
                }
                else{
                    $temp_file = FileInfo::find($member->profile_photo);
                    $fileinfo = $this->fileinfoRepo->uploadAndUpdateImageFile($temp_file,$dir,$file,getStorageDisk(),true);
                }
            }
            $response = $member->update($data);
            return $response;
        }
        return false;
    }

    public function updateProfile($member,$file,$attributes){
        $user = AuthRepository::updateUser($member->user, $attributes);
        if($user != false){
            if($file != null){
                $dir = 'Members/'.$member->id_token;
                $name = time().rand();
                $this->fileinfoRepo->setFile($file);
                $this->fileinfoRepo->setIsGenerateThumbnail(true);
                $this->fileinfoRepo->setThumbanilResolution(150,150);
                if($member->photo == null){
                    $fileinfo = $this->fileinfoRepo->uploadFile($dir,$name);
                    $data['profile_photo'] = $fileinfo->id;
                }
                else{
                    $fileinfo = $this->fileinfoRepo->updateFile($member->photo,$dir,$name);
                }
            }
            return true;
        }
        return false;
    }
    
    public function deleteMember($id){
        $member = TeamMember::find($id);
        if($member){
            $member->banned = true;
            $member->save();
            return (new UserRepository($member->user))->ban();
        }
        return false;
    }
    public function restoreMember($id){
        $member = TeamMember::find($id);
        if($member){
            $member->banned = false;
            $result=$member->save();
            return (new UserRepository($member->user))->unban();
        }
        return false;
    }
}
