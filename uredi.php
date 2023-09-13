<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once 'connect.php';

$id = $_SESSION["id"];
$sql = "SELECT * FROM uporabniki WHERE id = :id;";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$query = $stmt->rowCount();

if ($query > 0) {
    $ime = $_POST['username'];
    $opis = $_POST['opis'];

    $update_sql = "UPDATE uporabniki SET username = :ime, opis = :opis WHERE id = :id;";
    $stmt = $conn->prepare($update_sql);
    $stmt->bindParam(':ime', $ime, PDO::PARAM_STR);
    $stmt->bindParam(':opis', $opis, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        // Handle file upload
        if (isset($_FILES['slika']) && $_FILES['slika']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['slika']['tmp_name'];
            $fileName = $_FILES['slika']['name'];
            $fileSize = $_FILES['slika']['size'];
            $fileType = $_FILES['slika']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = array('jpg', 'gif', 'png');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $uploadFileDir = 'img/';
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    // Insert the image into the database
                    $slika_id = insertSlika($conn, $dest_path);

                    if ($slika_id !== false) {
                        // Update the slika_id in the uporabniki table
                        $update_sql3 = "UPDATE uporabniki SET slika_id = :slika_id WHERE id = :id";
                        $stmt4 = $conn->prepare($update_sql3);
                        $stmt4->bindParam(':slika_id', $slika_id, PDO::PARAM_INT);
                        $stmt4->bindParam(':id', $id, PDO::PARAM_INT);

                        if ($stmt4->execute()) {
                            setcookie('prijava', "Sprememba uspešna.");
                            setcookie('good', 1);
                            $_SESSION["username"] = $ime;
                            header('Location: profile.php');
                            exit();
                        } else {
                            setcookie('prijava', "Error: " . $stmt4->errorInfo()[2]);
                            setcookie('error', 1);
                            header('Location: profile.php');
                            exit();
                        }
                    } else {
                        setcookie('prijava', "Error: Failed to insert slika record.");
                        setcookie('error', 1);
                        header('Location: profile.php');
                        exit();
                    }
                } else {
                    setcookie('prijava', 'There was some error moving the file to the upload directory. Please make sure the upload directory is writable by the web server.');
                    setcookie('error', 1);
                    header('Location: profile.php');
                    exit();
                }
            } else {
                setcookie('prijava', 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions));
                setcookie('error', 1);
                header('Location: profile.php');
                exit();
            }
        } else {
            setcookie('prijava', 'Podatki spremenjeni.');
            setcookie('good', 1);
            header('Location: profile.php');
            exit();
        }
    } else {
        setcookie('prijava', "Error: " . $stmt->errorInfo()[2]);
        setcookie('error', 1);
        header('Location: profile.php');
        exit();
    }
} else {
    setcookie('prijava', "Error: Failed to fetch user data.");
    setcookie('error', 1);
    header('Location: profile.php');
    exit();
}

function insertSlika($conn, $url) {
    $slika_sql = "INSERT INTO slike (url) VALUES (:url)";
    $stmt = $conn->prepare($slika_sql);
    $stmt->bindParam(':url', $url, PDO::PARAM_STR);
    if ($stmt->execute()) {
        return $conn->lastInsertId();
    } else {
        return false;
    }
}
?>
