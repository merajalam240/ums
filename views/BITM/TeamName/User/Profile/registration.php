<?php

$gmailAddress = "Your@gmail.com";
$gmailPassword = "YourGmailPassword";



include_once('../../../../../vendor/autoload.php');
use App\User\User;
use App\User\Auth;
use App\Message\Message;
use App\Utility\Utility;

$auth= new Auth();
$status= $auth->setData($_POST)->is_exist();
if($status){
    Message::setMessage("<div class='alert alert-danger'>
    <strong>Taken!</strong> Email has already been taken. </div>");
    return Utility::redirect($_SERVER['HTTP_REFERER']);
}else{
    $_POST['email_token'] = md5(uniqid(rand()));
    $obj= new User();
    $obj->setData($_POST)->store();
   ######################################################## 
    require '../../../../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->SMTPDebug  = 0;
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = "ssl";
    $mail->Host       = "smtp.gmail.com";
    $mail->Port       = 465;
    $mail->addAddress($_POST['email']);
    $mail->Username=$gmailAddress;
    $mail->Password=$gmailPassword;
    $mail->setFrom($gmailAddress,'User Management');
    $mail->addReplyTo($gmailAddress,"User Management");
    $mail->Subject    = "Your Account Activation Link";
    $message =  "
       Please click this link to verify your account:
       http://localhost/UMS/views/BITM/TeamName/User/Profile/emailverification.php?email=".$_POST['email']."&email_token=".$_POST['email_token'];
    $mail->msgHTML($message);
    $mail->send();
}
