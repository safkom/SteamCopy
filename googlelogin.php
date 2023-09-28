<?php
session_start();
if(isset($_SESSION['id'])){
    setcookie('prijava', "Opla! Si že prijavljen, ne rabiš biti tukaj.");
    setcookie('warning', 1);
    header('Location: index.php');
    exit();
  }
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

        $ime = $data->givenName; // Use object properties to access data
        $priimek = $data->familyName;
        $mail = $data->email;
        //get id from google account
        $id = $data->id;

        
        $sql = "SELECT * FROM uporabniki WHERE mail = :mail";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() == 0) {
            $_SESSION['ime'] = $ime;
            $_SESSION['priimek'] = $priimek;
            $_SESSION['email'] = $mail;
            header("Location: google_register.php");
        } 
        else {
            if($result['google_id'] == NULL){
                $_SESSION['google_id'] = $id;
                $_SESSION['mail'] = $mail;
                header("Location: google_addmail.php");
            }
            else{
                if($result['banned'] === 1){
                    setcookie('banned', 1);
                    header("Location: odjava.php");
                }
                else{
                    $_SESSION["id"] = $result['id'];
                    $_SESSION["username"] = $result['username'];
                    setcookie('prijava', "Prijava uspešna.");
                    setcookie('good', 1);
                    header("Location: index.php");
                }
            }
        }
    }
} else {
    // Handle the case where the 'code' parameter is not set.
    // You might want to redirect or display an error message.
    echo "Error: Unable to authenticate with Google.";
}
?>
