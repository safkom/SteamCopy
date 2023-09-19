<?php
session_start();
include_once 'libraries/vendor/autoload.php';
require_once 'connect.php';

$google_client = new Google_Client();
$google_client->setClientId('512131787454-n3nrrf6flttgsle6l2903od7mp1v58so.apps.googleusercontent.com');
$google_client->setClientSecret('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');
$google_client->setRedirectUri('https://safko.eu/steamcopy/googlelogin.php');
$google_client->addScope('email');
$google_client->addScope('profile');

if (isset($_GET["code"])) {
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

    if (!isset($token["error"])) {
        $google_client->setAccessToken($token['access_token']);
        $_SESSION['access_token'] = $token['access_token'];

        $google_service = new Google_Service_Oauth2($google_client);

        $data = $google_service->userinfo->get();

        $username = $data->givenName; // Use object properties to access data
        $name = $data->familyName;
        $mail = $data->email;

        
        $sql = "SELECT * FROM uporabniki WHERE mail = :mail";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $_SESSION['ime'] = $name;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $mail;
            header("Location: google_register.php");
        } else {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['id'] = $row['id'];
            header("Location: index.php");
        }
    }
} else {
    // Handle the case where the 'code' parameter is not set.
    // You might want to redirect or display an error message.
    echo "Error: Unable to authenticate with Google.";
}
?>
