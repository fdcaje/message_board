<?php
class Schema
{
    public static function sign_up_user($conn, $name, $email, $username, $password)
    {
        $id = "000ALFDJO34";
        $query = "INSERT INTO user_profile (id, fullname, email, username, user_password) VALUES (:id, :n, :e, :u, :p);";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->bindParam(":n", $name, PDO::PARAM_STR);
        $stmt->bindParam(":e", $email, PDO::PARAM_STR);
        $stmt->bindParam(":u", $username, PDO::PARAM_STR);
        $stmt->bindParam(":p", $password, PDO::PARAM_STR);
        // var_dump(Schema::user_exist($conn, $email)) . "\n";
        // if (Schema::user_exist($conn, $email)) {
        //     return array("message" => "user $email already exist.");
        // }

        if ($err = Schema::execute($conn, $stmt)) {
            return $err;
        }

        return array("message" => "new user is registered");
    }

    public static function sign_in_user($conn, $user, $password)
    {
        $query = "SELECT id, user_password as password FROM user_profile WHERE email = :user;";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":user", $user, PDO::PARAM_STR);
        Schema::execute($conn, $stmt);
        if ($hash = $stmt->fetch(PDO::FETCH_OBJ)) {
            if (Schema::verify_password($password, $hash->password)) {
                return Schema::get_user($conn, $hash->id);
            } else {
                return array("message" => "your password is incorrect");
            }
        } else {
            return array("message" => "cannot find user $user.");
        }

    }

    public static function upload_photo($conn, $image_str, $uid)
    {
        // check auth
        $query = "UPDATE user_profile SET user_photo = :img_str WHERE id = :id;";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":img_str", $image_str);
        $stmt->bindParam(":id", $uid);
        if ($err = Schema::execute($conn, $stmt)) {
            return $err;
        }

        return array("message" => "photo successfully uploaded");
    }

    public static function get_user($conn, $id)
    {
        $query = "SELECT id, fullname as name, email, username FROM user_profile WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id);
        Schema::execute($conn, $stmt);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function user_exist($conn, $user)
    {
        $query = "SELECT 1 FROM user_profile WHERE email = :user";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":user", $user);
        Schema::execute($conn, $stmt);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    private static function verify_password($password, $hash)
    {
        return password_verify($password, $hash);
    }

    private static function execute($conn, $stmt)
    {
        try {
            $conn->beginTransaction();
            $stmt->execute();
            $conn->commit();
            $conn = null;
        } catch (PDOException $e) {
            return array("message" => $e->getMessage());
        }
    }
}
