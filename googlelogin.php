<?php

session_start();

include_once 'libraries/vendor/autoload.php';

$google_client = new Google_Client();

$google_client->setClientId('512131787454-n3nrrf6flttgsle6l2903od7mp1v58so.apps.googleusercontent.com');

$google_client->setClientSecret('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');

$google_client->SetRedirectUri('http://localhost/steamcopy/googlelogin.php');

$google_client->addScope('email');

$google_client->addScope('profile');

if(isset($_GET["code"])){
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

    if(!isset($token["error"])){
        $google_client->setAccessToken($token['access_token']);
        $_SESSION['access_token'] = $token['access_token'];

        $google_service = new Google_Service_Oauth2($google_client);

        $data = $google_service->userinfo->get();
        $current_datetime = date('Y-m-d H:i:s');

        $username = $data['given_name'];
        $name = $data['family_name'];
        $mail = $data['email'];
    }
}
$sql = "SELECT * FROM uporabniki WHERE email = '" . $_SESSION['email'] . "'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    $_SESSION['ime'] = $name;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $mail;
    header("Location: google_register.php");
}
else {
    $sql = "SELECT * FROM uporabniki WHERE email = '" . $_SESSION['email'] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $_SESSION['id'] = $row['id'];
    header("Location: index.php");
}




?>