<?php

$name = '';
$email = '';
$phone = '';
$uemail = '';
$uphone = '';
$errors = array();
$good = array();


 //connect to data base
require 'config.php';
if (isset($_POST['name'])) {
        $name = mysqli_real_escape_string($con, trim($_POST['name']));
        $email = mysqli_real_escape_string($con,$_POST['email']);
        $pass = mysqli_real_escape_string($con,$_POST['password']);
        $pass2 = mysqli_real_escape_string($con,$_POST['cpassword']);
        $phone = mysqli_real_escape_string($con,$_POST['phone']);

        // form Validation

        // Check DB for existing Username

        $user_check_query = "SELECT * FROM user WHERE email = '$email' OR phone = '$phone'";
        $result = $con -> query($user_check_query);

        $num = $result -> fetch_assoc();

     if($num)
     {
        
        if($num['email'] == $email)
        {   
            $uemail = "already existing email";
        }
        if($num['phone'] == $phone)
        {   
            $uphone = "already existing phone";     
        }
        
        $errors = array("errors" => array("email" => $uemail, "phone" => $uphone));

        $errors = json_encode($errors);

        print_r($errors);
     }

    
    // Register The User if no Errors


    if(empty($errors))
    {            
        $str_pass = password_hash($pass, PASSWORD_BCRYPT);              // This will Encrypt the Password

        $query = "INSERT INTO user(username ,email, password, phone) values ('$name','$email','$str_pass', $phone)";

        if($con -> query($query))
        {
            $last_id = mysqli_insert_id($con);   //the last entry

            $token = bin2hex(random_bytes(64));     //Generates Random Tokens

            $query2 = "INSERT INTO tokens(token, user_id) value ('$token', $last_id)";
            $con -> query($query2);
            $good = array("success" => array("token" => $token, "email" => $email));
            $good = json_encode($good);
            print_r($good);
        }

    }

}

$con -> close();

?>
