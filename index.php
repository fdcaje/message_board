<?php
include __DIR__ . '/includes.php';

$form_data = json_decode(file_get_contents("php://input"));
$data = null;

if (isset($form_data)) {
    // $data = lookFor($form_data, "name", "email", "username", "password", "confirm_password");
    // $data = lookFor($form_data, "username", "password");
    $data = lookFor($form_data, "user_photo", "user_id");
}

function lookFor($data, ...$args)
{
    foreach ($args as &$val) {
        if (!property_exists($data, $val)) {
            echo json_encode(array("message" => "invalid data"));
            return false;
        }
    }
    return $data;
}

// sign up
// if ($data) {
//     $user = new User($data);

//     if ($user->user_exist()) {
//         echo json_encode(array("message" => "user $data->email already exists."));
//         return;
//     }

//     if ($user->match_password($data->password, $data->confirm_password)) {
//         echo json_encode($user->sign_up());
//         return;
//     }

//     echo json_encode(array("message" => "password don't match"));
// }

// sign in
// if ($data) {
//     $user = User::sign_in(connect(), $data->username, $data->password);
//     echo json_encode($user);
// }

// upload photo
if ($data) {
    $path = pathinfo($data->user_photo, PATHINFO_DIRNAME);
    $file_name = pathinfo($data->user_photo, PATHINFO_FILENAME);
    $file_ext = pathinfo($data->user_photo, PATHINFO_EXTENSION);
    $file = $path . "/" . $file_name . "." . $file_ext;
    $fp = fopen($file, 'rb');
    $file_content = file_get_contents($file);
    ini_set("display_errors", "On");
    error_reporting(E_ALL);
    echo $file_content;
    return;
    $user = User::set_profile_photo(connect(), $a, $data->user_id);
    // if (is_uploaded_file($data->user_photo)) {
    // }

    // return;
    echo json_encode($user);
}
