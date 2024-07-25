<?php

return [

    'success_login' => 'Welcome To '.config('app.name').' :full_name',
    'unauthorized_access'=>'Unauthorized Access',
    'success_individual_registration' => "Congratulations ! :full_name. Your account has been successfully created.",
    'individual_registration_failure' => "Individual Account could not be created.",

    // Common API Responses

    'academic_level_listing_success' => 'Academic Level listing is available.',
    'academic_level_listing_failure' => 'Academic Level listing is not available.',
    
    'connecting_circle_listing_success' => 'Connecting Circle listing is available.',
    'connecting_circle_listing_failure' => 'Connecting Circle listing is not available.',

    'funding_list_listing_success' => 'Funding list is available.',
    'funding_list_listing_failure' => 'Funding list is not available.', 

    'industries_listing_success' => 'Industry list is available.',
    'industries_listing_failure' => 'Industry list is not available.', 

    'job_titles_listing_success' => 'Job Title list is available.',
    'job_titles_listing_failure' => 'Job Title list is not available.', 

    'passions_listing_success' => 'Passion listing is available.',
    'passions_listing_failure' => 'Passion listing is not available.', 

    'reasons_listing_success' => 'Reason listing is available.',
    'reasons_listing_failure' => 'Reason listing is not available.', 

    'skills_listing_success' => 'Skills listing is available.',
    'skills_listing_failure' => 'Skills listing is not available.', 

    'tags_listing_success' => 'Tags listing is available.',
    'tags_listing_failure' => 'Tags listing is not available.',

    // Individual API Responses

    'otp_verification_success' => 'OTP verified successfully.',
    'otp_verification_failure' => 'OTP could not be verified.',

    'profile_information_success' => 'Profile is available.',
    'profile_information_failure' => 'Profile is not available.',
    
    'update_image_success' => 'Profile Picture has been updated successfully.',
    'update_image_failure' => 'Profile Picture could not be updated.',

    'update_individual_info_success' => 'Profile Information has been updated successfully.',
    'update_individual_info_failure' => 'Profile Information could not be updated.',

    'update_help_tags_success' => 'Profile Help Tags have been updated successfully.',
    'update_help_tags_failure' => 'Profile Help Tags could not be updated.',

    'update_passions_success' => 'Passions have been updated successfully.',
    'update_passions_failure' => 'Passions could not be updated.',

    'update_individual_history_success' => 'Profile History has been updated successfully.',
    'update_individual_history_failure' => 'Profile History could not be updated.',
    
    'update_individual_mood_success' => 'Profile Mood has been updated successfully.',
    'update_individual_mood_failure' => 'Profile Mood could not be updated.',

    'create_profile_success' => 'Profile created successfully',
    'create_profile_failure' => 'Profile could not be created.',
    
    'create_business_profile_success' => 'Business Profile created successfully.',
    'create_business_profile_failure' => 'Business Profile could not be created.',

    'update_business_image_success' => 'Business Profile Image updated successfully.',
    'update_business_image_failure' => 'Business Profile Image could not be updated.',

    'update_business_tags_success' => 'Business Profile Tags updated successfully.',
    'update_business_tags_failure' => 'Business Profile Tags could not be updated.',

    'update_business_info_success' => 'Business Profile Info updated successfully.',
    'update_business_info_failure' => 'Business Profile Info could not be updated.',

    'update_business_details_success' => 'Business Profile Details updated successfully.',
    'update_business_details_failure' => 'Business Profile Details could not be updated.',

    'follow_success' => 'You are now following this Business Profile.',
    'unfollow_success' => 'You are now not following this Business Profile',
    'follow_failure' => 'Business Profile could not be followed.',

    'list_of_followers' => 'List of Followers of the Business Profile.',
    'list_of_team_members' => 'List of Team Members of the Business Profile.',

    'invite_success' => 'Invitation sent successfully.',
    'invite_failure' => 'Invitation could not be sent.',

    'remove_member_success' => 'Team Member removed from Business Profile.',
    'remove_member_failure' => 'Team Member could not be removed from Business Profile.',
    
    'business_profile_information_success' => 'Business Profile is available.',
    'business_profile_information_failure' => 'Business Profile is not available.',

    'update_community_image_success' => 'Community Image updated successfully.',
    'update_community_image_failure' => 'Community Image could not be updated.',
    
    'update_community_intro_success' => 'Community Intro updated successfully.',
    'update_community_intro_failure' => 'Community Intro could not be updated.',

    'community_invite_success' => 'Members have been invited to community successfully.',
    'community_invite_failure' => 'Members could not be invited to community.',

    'community_remove_member_success' => 'Member has been removed from Community',
    'community_remove_member_failure' => 'Member could not be removed from Community',

    'list_of_community_members' => 'List of Members of a Community.',

    'community_profile_information_success' => 'Community profile is available.',
    'community_profile_information_failure' => 'Community profile is not available.',
    
    'create_community_profile_success' => 'Community Profile created successfully.',
    'create_community_profile_failure' => 'Community Profile could not be created.',

    'list_of_community_requested_members' => 'List of Requested Members of a community.',

    'community_member_approval_rejection_success' => 'Community Member Request has been approved or rejected.',
    'community_member_approval_rejection_failure' => 'Community Member Request could not be approved or rejected.',

    'shared_space_list_success' => 'Shared Space Listing is available.',
    'shared_space_list_failure' => 'Shared Space Listing is not available.',

    'shared_space_information_success' => 'Shared Space Profile is available.',
    'shared_space_information_failure' => 'Shared Space Profile is not available.',
    
    'community_join_success' => 'Member has requested to join a community.',
    'community_join_failure' => 'Member could not join a community.',

    'create_shared_space_success' => 'Shared Space Profile has been created.',
    'create_shared_space_failure' => 'Shared Space Profile could not be created.',

    'update_shared_space_rate_success' => 'Shared Space Profile Rates has been updated.',
    'update_shared_space_rate_failure' => 'Shared Space Profile Rates could not be updated.',

    'update_shared_space_image_success' => 'Shared Space has added new image.',
    'update_shared_space_image_failure' => 'Shared Space image could not be added.',

    'remove_shared_space_image_success' => 'Shared Space Image has been removed successfully.',
    'remove_shared_space_image_failure' => 'Shared Space Image could not be removed.',

    'event_information_success' => 'Event Information is available.',
    'event_information_failure' => 'Event Information is not available.',

    'create_event_success' => 'Event has been created.',
    'create_event_failure' => 'Event has not been created.',

    'update_event_image_success' => 'Event Image has been updated.',
    'update_event_image_failure' => 'Event Image could not be updated.',

    'update_event_intro_success' => 'Event Intro has been updated.',
    'update_event_intro_failure' => 'Event Intro could not be updated.',

    'update_event_details_success' => 'Event Details has been updated.',
    'update_event_details_failure' => 'Event Details could not be updated.',

    'event_sign_up_success' => 'Individual has signed up to the event.',
    'event_sign_up_failure' => 'Individual could not sign up to the event.',

    'event_interested_success' => 'Individual has shown interest to the event.',
    'event_interested_failure' => 'Individual could not be added as interest to the event.',
    
    'list_of_signed_up_members' => 'List of Signed Up Members of an Event.',
    'list_of_interseted_members' => 'List of Interested Members of an Event.',

    'list_of_events_success' => 'List of Events has been loaded.',
    'list_of_events_failure' => 'List of Events could not be loaded.',

    //artisan api
    'success_artisan_registration' => "Congratulations ! :full_name. Your account has been successfully created.",
    'artisan_registration_failure' => "Artisan Account could not be created.",

    'update_artisan_personal_detail_success' => 'Personal Details has been updated successfully.',
    'update_artisan_personal_detail_failure' => 'Personal Details could not be updated.',

    'update_artisan_address_detail_success' => 'Address Details has been updated successfully.',
    'update_artisan_address_detail_failure' => 'Address Details could not be updated.',

    'update_artisan_award_detail_success' => 'Award Details has been updated successfully.',
    'update_artisan_award_detail_failure' => 'Award Details could not be updated.',

    'update_artisan_profile_image_success' => 'Profile Image has been updated successfully.',
    'update_artisan_profile_image_failure' => 'Profile Image could not be updated.',

    'update_artisan_artisan_card_id_proof_success' => 'Artisan card and Id proof has been updated successfully.',
    'update_artisan_artisan_card_id_proof_failure' => 'Artisan card and Id proof could not be updated.',

    'resend_otp_success' => 'OTP has been successfully sent to your mobile number',

    'artisan_not_found' => 'Artisan Not Found',

    //Device
    'device_registered_success' => 'Device Registered successfully',
    
];