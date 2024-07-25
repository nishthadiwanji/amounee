<?php

namespace App\Http\Controllers\TeamMember;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\TeamMember\TeamMemberRepository;
use App\Http\Requests\TeamMember\TeamMemberRequest;
use App\Models\TeamMember\TeamMember;
use Carbon\Carbon;
use Exception;
use Sentinel;
use Log;
use DB;
use App\Transformers\TeamMemberTransformer;

class TeamMemberController extends Controller
{
    protected $team_member_repo;
    protected $transformer;

    public function __construct(TeamMemberRepository $team_member_repo, TeamMemberTransformer $transformer)
    {
        $this->team_member_repo = $team_member_repo;
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = $this->getPaginationRecordCount(request());
        $search = empty(request()->search) ?  '' : request()->search;
        $status = empty(request()->status) ? 'active' : request()->status;
        $members = $this->team_member_repo->search($search);
        $members = $this->team_member_repo->withUser($members);
        if($status == 'banned'){ 
            $members = $members->banned();
        }
        else{
            $members = $members->active();
        }
        $members = $members->where('user_id','<>',Sentinel::getUser()->id)->paginate($records);
        return view('modules.team-member.index',compact('members','search','status','records'));
    }

    /**
     * show the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = decrypt_id_info($id);
        $member = $this->team_member_repo->getInformation($id);
        if(!$member){
            abort(404);
        }
        return view('modules.team-member.show', compact('member'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = collect(config('constant.department_options'));
        $designations = collect(config('constant.designation_options'));
        $blood_groups = collect(config('constant.blood_group_options'));

        // if(Sentinel::inRole('admin')){
        //     log_activity_by_user('admin_log',Sentinel::getUser(),__('activity_log_messages.viewed_create_team_member_form'));
        // }
        
        // if(Sentinel::inRole('team_member') && (!Sentinel::inRole('admin'))){
        //     log_activity_by_user('team_member_log',Sentinel::getUser()->teamMember,__('activity_log_messages.viewed_create_team_member_form'));
        // }

        return view('modules.team-member.create', compact('departments','designations','blood_groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamMemberRequest $request)
    {
        try{
            $file = null;
            if($request->hasFile('profile_photo')){

                $file = $request->file('profile_photo');
            }
            DB::beginTransaction();
            $member = $this->team_member_repo->createTeamMember($file,$request->all(),Sentinel::getUser()->id);
            DB::commit();
            return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.add_member')],200)->setCallback(request()->input('callback'));
        }catch(Exception $e){
            DB::rollBack();
            Log::error(__('log.add_member_error'),[$e->getMessage()]);
            return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'create team member'])],200)->setCallback(request()->input('callback'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decrypt_id_info($id);
        $departments = collect(config('constant.department_options'));
        $designations = collect(config('constant.designation_options'));
        $blood_groups = collect(config('constant.blood_group_options'));
        $member = TeamMember::with(['user','photo'])->findOrFail($id);
        return view('modules.team-member.edit',compact('member','departments','designations','blood_groups'));
    }

    /**
     * Update the Team Member Information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TeamMemberRequest $request, $id)
    {
        $id = decrypt_id_info($id);
        $file = null;
        $member = TeamMember::with('user')->find($id);
        if(!$member){
            return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('responses.member_not_found')],200)->setCallback(request()->input('callback'));
        }
        // This validation exists here because if we choose to take it in the TeamMemberRequest
        // It will cause us to redundantly get the User Information, so to reduce the query load
        // We decided to add this validation over here.
        // $this->validate($request,[
        //     'first_name' =>'required|max:191',
        //     'middle_name' =>'nullable|max:1',
        //     'last_name' =>'required|max:191',
        //     'country_code' => 'nullable|min:0|max:4',
        //     'phone_number' => 'nullable|max:12',
        //     'email' => 'required|email|max:191|unique:users,email,'.$member->user->id,
        //     'profile_photo' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        //     'department' => 'required',
        //     'title' => 'sometimes'
        // ]);
        if($request->hasFile('profile_photo')){
            $file = $request->file('profile_photo');
        }
        $response = $this->team_member_repo->updateTeamMember($member,$file,$request->all());
        if($response == false){
            return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'update team member'])],200)->setCallback(request()->input('callback'));
        }
        return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.update_member')],200)->setCallback(request()->input('callback'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = decrypt_id_info($id);
        try{
            DB::beginTransaction();
            $response = $this->team_member_repo->deleteMember($id);
            if($response){
                DB::commit();
                return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.ban_member')],200)->setCallback(request()->input('callback'));
            }
            else{
                DB::rollBack();
                Log::error(__('log.ban_member_error'),[$id]);
                return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'ban member'])],200)->setCallback(request()->input('callback'));
            }
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error(__('log.ban_member_error'),[$e->getMessage()]);
            return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'ban member'])],200)->setCallback(request()->input('callback'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $id = decrypt_id_info($id);
         try{
            DB::beginTransaction();
            $response = $this->team_member_repo->restoreMember($id);
            if($response){
                DB::commit();
                return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.activate_member')],200)->setCallback(request()->input('callback'));
            }
            else{
                DB::rollBack();
                Log::error(__('log.activate_member_error'),[$id]);
                return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'activate member'])],200)->setCallback(request()->input('callback'));
             }
         }catch(Exception $e){
             DB::rollBack();
             Log::error(__('log.activate_member_error'),[$e->getMessage()]);
             return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'activate member'])],200)->setCallback(request()->input('callback'));
         }
    }
}
