<?php

$errors = array();
require 'config.php';


if (isset($_POST['email'])){

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $pass = mysqli_real_escape_string($con, $_POST['password']);

    // Filtering these variable for Security

    $email = strip_tags(mysqli_real_escape_string($con, trim($email)));
    $pass = strip_tags(mysqli_real_escape_string($con, trim($pass)));
    
    //query
    $sql = "SELECT user.email as email, user.password as password, tokens.token as token from tokens join user on tokens.user_id = user.id where user.id = (SELECT id FROM user WHERE email='$email')";
    $tbl = $con -> query($sql);

    $fdd = $tbl -> fetch_assoc();
    if($fdd)
    {

        $password_hash = $fdd['password'];
        if(!password_verify($pass, $password_hash))
        {
            $errors = array("errors" => array("email" => "wrong username or password"));
            $errors = json_encode($errors);
            return print_r($errors);

        }
        return print_r(json_encode(array(
            "success" =>array(
                "token" => $fdd['token'], "email" => $fdd['email']))
        )
    );

    }
    $errors = array("errors" => array("email" => "wrong email"));
            $errors = json_encode($errors);
            return print_r($errors);
    

}

?>