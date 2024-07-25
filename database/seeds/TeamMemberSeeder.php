<?php

use Illuminate\Database\Seeder;
use App\Models\TeamMember\TeamMember;
use App\Repositories\TeamMember\TeamMemberRepository;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use App\Models\Auth\SentinelUser;

class TeamMemberSeeder extends Seeder
{

    protected $team_member_repo;
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function __construct(TeamMemberRepository $team_member_repo) {
        $this->team_member_repo = $team_member_repo;
    }
    
    public function run()
    {
        try{
            
            $admins = Sentinel::findRoleBySlug('admin')->users()->get();

            foreach($admins as $admin) {
                $member = [
                    'user_id'       => $admin->id,
                    // 'department' => collect(config('constant.department_options'))->random(),
                    // 'designation'   => collect(config('constant.designation_options'))->random()
                ];
                $this->enterDataInTeamMember($member);
            }
            if(config('app.env') == 'local'){
                foreach(config('users.local.team_member') as $user){
                    $attributes = factory(SentinelUser::class)->make($user);
                    $this->prepareAndCreateTeamMember($attributes);   
                }
    
                foreach(range(1,20) as $count){
                    $this->prepareAndCreateTeamMember(factory(SentinelUser::class)->make());
                }
            }
        }
        catch(\Exception $e){
            \Log::error("error in team members seeder => ",[$e->getMessage(), $e->getTraceAsString()]);
        }
    }

    protected function prepareAndCreateTeamMember($attributes){
        $file = UploadedFile::fake()->image('avatar.jpeg', 300, 300)->size(500);
        
        return $this->team_member_repo->createTeamMember($file, $attributes, 1);
    }

    protected function enterDataInTeamMember($data){
        $data['added_by'] = 1;
        return TeamMember::create($data);
    }
}
