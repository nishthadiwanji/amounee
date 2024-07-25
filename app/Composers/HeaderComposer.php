<?php

namespace App\Composers;

use Illuminate\View\View;

class HeaderComposer
{
    public function compose(View $view)
    {
        $user = request()->user;
        $user_full_name = $user->first_name.' '.$user->last_name;
        $user_first_name = $user->first_name;
        $user_email = $user->email;
        if($user->inRole('team_member')){
            if($user->inRole('admin'))
            {
                $user_designation='Admin';
                $user_img=asset('/img/no_image.jpg');
            }
            else
            {
                $user_designation = $user->teamMember->designation;
                $user_img = $user->teamMember->photo->display_thumbnail_url ?? asset('/img/no_image.jpg');
            }

        }
        $view->with(compact('user_full_name','user_first_name','user_email', 'user_designation','user_img'));
    }
}
