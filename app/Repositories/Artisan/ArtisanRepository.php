<?php
namespace App\Repositories\Artisan;

use App\Models\Artisan\Artisan;
use App\Models\FileInfo\FileInfo;
use App\Repositories\UserRepository;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\FileRepository;
// use App\Mail\WelcomeArtisan;
use App\Repositories\EloquentDBRepository;
use Sentinel;
use Mail;
use DB;

class ArtisanRepository extends EloquentDBRepository
{
    protected $artisan;
    protected $fileinfoRepo;
    protected $artisanstatus;

    public function __construct(Artisan $artisan, FileRepository $fileinfoRepo) {
        $this->model = $artisan;
        // $this->artisan = $artisan;
        $this->fileinfoRepo = $fileinfoRepo;
    }
    public function getInformation($id){
        $artisan = $this->model->with(['user'])->find($id);
        // $artisan = $this->model->find($id);
        return $artisan;
    }
    public function search($search){
        $artisans = (new Artisan)->newQuery();
        $artisans->where(function ($query) use ($search) {
                $query->where('first_name','like','%'.$search.'%')
                    ->orWhere('last_name','like','%'.$search.'%')
                    ->orWhere('phone_number','like','%'.$search.'%')
                    ->orWhere('email','like','%'.$search.'%')
                    ->orWhere(function($query) use ($search) {
                        $query->where(\DB::raw("concat(`first_name`,' ',`last_name`)"),'like', "%".$search."%");
                    });
        });
        return $artisans;
    }
    public function withUser($query){

        $query = DB::table('artisans')->select('id','first_name','last_name','phone_number','country_code','email','banned')->orderBy('updated_at','DESC')->get();
        return $query;

        // return $query->with(['user'=>function($q){
        //     $q->select('id','first_name','last_name','phone_number','country_code','email','banned');
        // }])->orderBy('updated_at','DESC');
    }
    public function createArtisan($attributes, $added_by, $is_approved)
    {
        $attributes['password'] = str_random(8);
        $user = AuthRepository::createUser('artisan', $attributes);
        if ($user != false) {
            $data = [
                'user_id'  => $user->id,
                'category_id'  => $attributes['category_id'],
                'first_name' => $attributes['first_name'],
                'last_name' => $attributes['last_name'],
                'trade_name' => $attributes['trade_name'],
                'gst' => $attributes['gst'],
                'country_code' => $attributes['country_code'],
                'phone_number' => $attributes['phone_number'],
                'email' => $attributes['email'],
                'street1' => $attributes['street1'],
                'street2' => $attributes['street2'],
                'zip' => $attributes['zip'],
                'city' => $attributes['city'],
                'state' => $attributes['state'],
                'country' => $attributes['country'],
                'account_name' => $attributes['account_name'],
                'account_number' => $attributes['account_number'],
                'bank_name' => $attributes['bank_name'],
                'ifsc' => $attributes['ifsc'],
                'awards' => $attributes['awards'],
                'commission' =>$attributes['commission'],
                'added_by' => $added_by,
                'status' => ($is_approved == 1) ? 'approved' : 'pending'
            ];
            $response = $this->model->create($data);
            return $response;
        }
        
        return false;
    }
    public function createArtisanStatus(Artisan $artisan, $status, $is_approved)
    {
        if ($is_approved == 1) {
            $response = $artisan->artisanStatuses()->create([
                'status' => 'pending'
            ]);
            $response = $artisan->artisanStatuses()->create([
                'status' => 'approved'
            ]);
            return $response;
        } else {
            $response = $artisan->artisanStatuses()->create([
                'status' => $status
            ]);
            return $response;
        }
    }
    public function createArtisanFiles(Artisan $artisan, $request){

        if ($request->hasFile('vendor_picture')) {
            $file = $request->file('vendor_picture');
            $dir = 'artisans/profile/' . $artisan->id_token;
            $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
            $artisan->artisanFiles()->create([
                'file_type' => 'profile_photo',
                'file_id'   => $fileinfo->id
            ]);
        }

        if($request->hasFile('artisan_cards')){
            $file = $request->file('artisan_cards');
            $dir = 'artisans/card/' . $artisan->id_token;
            $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
            $artisan->artisanFiles()->create([
                'file_type' => 'artisan_card',
                'file_id'   => $fileinfo->id
            ]);
        }

        if($request->hasFile('id_proof')){
            foreach ($request->file('id_proof') as $file) {
                $dir = 'artisans/id-proof/' . $artisan->id_token;
                $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
                $artisan->artisanFiles()->create([
                    'file_type' => 'id_proof',
                    'file_id'   => $fileinfo->id
                ]);
            }
        }

        if($request->hasFile('passbook_picture')){
            $file = $request->file('passbook_picture');
            $dir = 'artisans/bank-detail/' . $artisan->id_token;
            $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
            $artisan->artisanFiles()->create([
                'file_type' => 'passbook_picture',
                'file_id'   => $fileinfo->id
            ]);
        }

        return true;
    }

    public function createApprovalNote(Artisan $artisan, $attributes)
    {
        $data = [
            'approval_note' => $attributes['approval_note'],
        ];
        $response = $artisan->update($data);
        return $response;
    }

    public function createRejectionNote(Artisan $artisan, $attributes)
    {
        $data = [
            'rejection_note' => $attributes['rejection_note'],
        ];
        $response = $artisan->update($data);
        return $response;
    }
    
    public function updateArtisan(Artisan $artisan, $attributes)
    {
        $user = AuthRepository::updateUser($artisan->user, $attributes);
        if ($user != false) {
            $data = [
                'category_id'  => $attributes['category_id'],
                'first_name' => $attributes['first_name'],
                'last_name' => $attributes['last_name'],
                'trade_name' => $attributes['trade_name'],
                'gst' => $attributes['gst'],
                'country_code' => $attributes['country_code'],
                'phone_number' => $attributes['phone_number'],
                'email' => $attributes['email'],
                'street1' => $attributes['street1'],
                'street2' => $attributes['street2'],
                'zip' => $attributes['zip'],
                'city' => $attributes['city'],
                'state' => $attributes['state'],
                'country' => $attributes['country'],
                'account_name' => $attributes['account_name'],
                'account_number' => $attributes['account_number'],
                'bank_name' => $attributes['bank_name'],
                'ifsc' => $attributes['ifsc'],
                'awards' => $attributes['awards'],
                'commission' => $attributes['commission']
            ];
            $response = $artisan->update($data);
            return $response;
        }
        return false;
    }

    public function updateArtisanFiles(Artisan $artisan, $request) 
    {
        if ($request->hasFile('vendor_picture')) {
            $file = $request->file('vendor_picture');
            $fileInfo = NULL;
            if (!empty($artisan->photo())) {
                $fileInfo = $artisan->photo()->first();
            }
            $dir = 'artisans/profile/' . $artisan->id_token;
            // $this->fileinfoRepo->uploadAndUpdateImageFile($fileInfo, $dir, $file, getStorageDisk(), true);
            if (!is_null($fileInfo)) {
                $this->fileinfoRepo->uploadAndUpdateImageFile($fileInfo, $dir, $file, getStorageDisk(), true);
            } else {
                $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
                $artisan->artisanFiles()->create([
                    'file_type' => 'profile_photo',
                    'file_id'   => $fileinfo->id
                ]);
            }
        }

        if ($request->hasFile('artisan_cards')) {
            $file = $request->file('artisan_cards');
            $fileInfo = NULL;
            if (!empty($artisan->artisanCard())) {
                $fileInfo = $artisan->artisanCard()->first();
            }
            $dir = 'artisans/card/' . $artisan->id_token;
            if (!is_null($fileInfo)) {
                $this->fileinfoRepo->uploadAndUpdateImageFile($fileInfo, $dir, $file, getStorageDisk(), true);
            } else {
                $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
                $artisan->artisanFiles()->create([
                    'file_type' => 'artisan_card',
                    'file_id'   => $fileinfo->id
                ]);
            }
        }

        if ($request->hasFile('id_proof')) {
            $idProofs = $artisan->artisanFiles()->where('file_type', 'like', 'id_proof')->pluck('file_id');
            $fileInfos = FileInfo::find($idProofs->toArray());
            $artisan->artisanFiles()->where('file_type', 'like', 'id_proof')->forceDelete();
            if ($fileInfos->count()) {
                foreach ($fileInfos as $fileInfo) {
                    $this->fileinfoRepo->removeFileWithFileInfo($fileInfo, getStorageDisk());
                }
            }
            foreach ($request->file('id_proof') as $file) {
                $dir = 'artisans/id-proof/' . $artisan->id_token;
                $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
                $artisan->artisanFiles()->create([
                    'file_type' => 'id_proof',
                    'file_id'   => $fileinfo->id
                ]);
            }
        }

        if ($request->hasFile('passbook_picture')) {
            $file = $request->file('passbook_picture');
            $fileInfo = $artisan->bankProof()->first();
            $dir = 'artisans/bank-detail/' . $artisan->id_token;
            $this->fileinfoRepo->uploadAndUpdateImageFile($fileInfo, $dir, $file, getStorageDisk(), true);
        }
        return true;
    }


    public function updateProfile($artisan,$file,$attributes)
    {
        $user = AuthRepository::updateUser($artisan->user, $attributes);
        if($user != false){
            if($file != null){
                $dir = 'artisans/'.$artisan->id_token;
                $name = time().rand();
                $this->fileinfoRepo->setFile($file);
                $this->fileinfoRepo->setIsGenerateThumbnail(true);
                $this->fileinfoRepo->setThumbanilResolution(150,150);
                if($artisan->photo == null){
                    $fileinfo = $this->fileinfoRepo->uploadFile($dir,$name);
                    $data['vendor_picture'] = $fileinfo->id;
                    $data['artisan_cards'] = $fileinfo->id;
                    $data['id_proof'] = $fileinfo->id;
                    $data['passbook_picture'] = $fileinfo->id;
                }
                else{
                    $fileinfo = $this->fileinfoRepo->updateFile($artisan->photo,$dir,$name);
                }
            }
            return true;
        }
        return false;
    }
    public function deleteArtisan($id){
        $artisan = Artisan::find($id);
        if($artisan){
            $artisan->delete();
            return $artisan;
        }
        return false;
    }
    public function restoreArtisan($id){
        $artisan = Artisan::withTrashed()->find($id);
        if($artisan){
            $artisan->restore();
            return $artisan;
        }
        return false;
    }
    public function approveArtisan($id){
        $artisan = Artisan::find($id);
        if($artisan){
            $artisan->status = 'approved';
            $artisan->save();
            return $artisan;
        }
        return false;
    }
    public function rejectArtisan($id){
        $artisan = Artisan::find($id);
        if($artisan){
            $artisan->status = 'rejected';
            $artisan->save();
            return $artisan;
        }
    }

    public function banArtisan(Artisan $artisan)
    {
        //TODO::implement Auth repo ban user
        return $artisan->update(['banned' => 1]);
    }

    public function unbanArtisan(Artisan $artisan)
    {
        //TODO::implement Auth repo ban user
        return $artisan->update(['banned' => 0]);
    }

    //API Functions
    public function updateArtisanPersonalDetails(Artisan $artisan,$attributes)
    {
        $user = AuthRepository::updateUser($artisan->user, $attributes);
        if ($user != false) {
            $data = [
                'first_name' => $attributes['first_name'],
                'last_name' => $attributes['last_name'],
                'trade_name' => $attributes['trade_name'],
                'gst' => $attributes['gst'],
                'phone_number' => $attributes['phone_number'],
                'email' => $attributes['email'],
                'category_id'  => $attributes['category_id']
            ];
            $response = $artisan->update($data);
            return $response;
        }
        return false;
    }
    public function updateArtisanAddressDetails(Artisan $artisan,$attributes)
    {
        $data = [
            'street1' => $attributes['street1'],
            'street2' => $attributes['street2'],
            'zip' => $attributes['zip'],
            'city' => $attributes['city'],
            'state' => $attributes['state'],
            'country'  => $attributes['country']
        ];
        $response = $artisan->update($data);
        return $response;

    }
    public function updateArtisanAwardDetails(Artisan $artisan,$attributes)
    {
        $data = [
            'awards' => $attributes['awards'],
        ];
        $response = $artisan->update($data);
        return $response;
    }
    public function updateArtisanProfileImage(Artisan $artisan,$request)
    {
        if ($request->hasFile('vendor_picture')) {
            $file = $request->file('vendor_picture');
            $fileInfo = NULL;
            if (!empty($artisan->photo())) {
                $fileInfo = $artisan->photo()->first();
            }
            $dir = 'artisans/profile/' . $artisan->id_token;
            if (!is_null($fileInfo)) {
                $this->fileinfoRepo->uploadAndUpdateImageFile($fileInfo, $dir, $file, getStorageDisk(), true);
            } else {
                $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
                $artisan->artisanFiles()->create([
                    'file_type' => 'profile_photo',
                    'file_id'   => $fileinfo->id
                ]);
            }
        }
        return true;
    }
    public function updateArtisanIdProofAndArtisanCard(Artisan $artisan,$request)
    {
        if ($request->hasFile('artisan_cards')) {
            $file = $request->file('artisan_cards');
            $fileInfo = NULL;
            if (!empty($artisan->artisanCard())) {
                $fileInfo = $artisan->artisanCard()->first();
            }
            $dir = 'artisans/card/' . $artisan->id_token;
            if (!is_null($fileInfo)) {
                $this->fileinfoRepo->uploadAndUpdateImageFile($fileInfo, $dir, $file, getStorageDisk(), true);
            } else {
                $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
                $artisan->artisanFiles()->create([
                    'file_type' => 'artisan_card',
                    'file_id'   => $fileinfo->id
                ]);
            }
        }

        if ($request->hasFile('id_proof')) {
            $idProofs = $artisan->artisanFiles()->where('file_type', 'like', 'id_proof')->pluck('file_id');
            $fileInfos = FileInfo::find($idProofs->toArray());
            $artisan->artisanFiles()->where('file_type', 'like', 'id_proof')->forceDelete();
            if ($fileInfos->count()) {
                foreach ($fileInfos as $fileInfo) {
                    $this->fileinfoRepo->removeFileWithFileInfo($fileInfo, getStorageDisk());
                }
            }
            foreach ($request->file('id_proof') as $file) {
                $dir = 'artisans/id-proof/' . $artisan->id_token;
                $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
                $artisan->artisanFiles()->create([
                    'file_type' => 'id_proof',
                    'file_id'   => $fileinfo->id
                ]);
            }
        }
        return true;
    }
}
