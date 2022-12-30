<?php
// password_hash()
// password_verify()
class User
{
    private $name;
    private $email;
    private $username;
    private $password;
    private $photo;
    public function __construct($data)
    {
        if (isset($data)) {
            $this->name = $data->name;
            $this->email = $data->email;
            $this->username = $data->username;
        } else {
            $this->response("there is no data");
            return false;
        }
    }

    public function sign_up()
    {
        if (isset($this->name, $this->email, $this->username, $this->password)) {
            return Schema::sign_up_user(connect(), $this->name, $this->email, $this->username, $this->password);
        }
    }

    public static function sign_in($conn, $user, $password)
    {
        return Schema::sign_in_user($conn, $user, $password);
    }

    public function sign_out()
    {
        return;
    }

    public function update_profile()
    {
        // is user signed in
        // check for auth
        return;
    }

    public static function set_profile_photo($conn, $image_str, $uid)
    {
        return Schema::upload_photo($conn, $image_str, $uid);
    }

    public function user_exist()
    {
        return Schema::user_exist(connect(), $this->email);
    }

    public function match_password($password, $repassword)
    {
        if (($password && $repassword) && $password == $repassword) {
            $this->password = password_hash($password, PASSWORD_DEFAULT, array("cost" => 11));
            return true;
        }
        return false;
    }

    public function __toString()
    {
        $user = null;
        if (isset($this->name, $this->email, $this->username)) {
            $user = array(
                "name" => $this->name,
                "email" => $this->email,
                "username" => $this->username,
                "profile_picture" => $this->photo,
            );
        }
        return $user;
    }

    private function response($msg)
    {
        echo json_encode(array("message" => "$msg"));
    }
}
