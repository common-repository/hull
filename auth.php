<?php

add_filter('authenticate', 'hull_auth', 10, 3);

function hull_auth($user, $username, $password) {
  $client = get_hull_client();
  $userId = $client->currentUserId();
  if ($userId) {
    $userObj = new WP_User();
    $user = $userObj->get_data_by('login', $userId);
    if ($user->ID == 0) {
      $hullUser = $client->get($userId);
      $userdata = array(
        'user_email' => $hullUser->email,
        'user_login' => $hullUser->id,
      );
      $new_user_id = wp_insert_user($userdata); // A new user has been created

      // Load the new user info
      $user = new WP_User ($new_user_id);
    } else {
      $user = new WP_User($user->ID);
    }

    remove_action('authenticate', 'wp_authenticate_username_password', 20);
  }
  return $user;
}
