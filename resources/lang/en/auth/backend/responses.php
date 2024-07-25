<?php

return [
    'invalid_credentials' => 'Invalid :user_name Email Address or Password. Incase of Multiple Attempts Please Contact System Admin.',
    'success_login' => 'Welcome To '.config('app.name').' </br> :full_name',
    'expire_reset_pswd_request' => 'Your reset password request could not found or expire, Please try again by sending a new request.',
    'invalid_email' => 'This email address is not registered in the system.',
    'failed_reset_pswd' => 'Your Reset Password Is Under Process You May Have Received an Email Please Check Spam Folders.',
    'success_reset_pswd_mail' => 'The Reset Password process Has Been Initiated And You Will Recieve An Email Shortly For The Same.',
    'failed_password_reminder' => 'An Error Has Occured On the Server Please Try Again Later.',
    'success_reset_pswd' => 'Your Password Reset Successfully.',
    //Application Responses
    'error_invalid_cred' => 'Invalid credentials.',
    'success_logout' => 'Logout successfully.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'failed_valid_cred' => 'Please enter credentials.',
    'unauthorized_access' => 'Unauthorized to access.',
    'provide_token' => 'Please provide token.',
    'changed_pswd' => 'Your password has been changed successfully.',
    'incorrect_current_pswd' => 'Current password is incorrect ! Please try again with correct password for change password.',
    'generate_pswd' => 'Password has been generated successfully.',
    'success_profile_update' => 'Profile has been updated successfully.',
];
