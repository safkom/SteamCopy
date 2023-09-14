<?php
require_once 'connect.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['id'];
$ime = $_POST['ime'];
$opis = $_POST['opis'];
$zanr = $_POST['zanr'];

// Insert the game into the 'igre' table
$insertGameSql = "INSERT INTO igre (ime, opis, uporabnik_id, zanr) VALUES (:ime, :opis, :publisher, :zanr);";
$stmt = $conn->prepare($insertGameSql);
$stmt->bindParam(':ime', $ime, PDO::PARAM_STR);
$stmt->bindParam(':opis', $opis, PDO::PARAM_STR);
$stmt->bindParam(':publisher', $user_id, PDO::PARAM_STR);
$stmt->bindParam(':zanr', $zanr, PDO::PARAM_STR);

if ($stmt->execute()) {
    $gameId = $conn->lastInsertId(); // Get the ID of the inserted game

    // Handle file upload for the ZIP file
    if (isset($_FILES['zip']) && $_FILES['zip']['error'] === UPLOAD_ERR_OK) {
        $zipFileTmpPath = $_FILES['zip']['tmp_name'];
        $zipFileName = $_FILES['zip']['name'];
        $zipFileSize = $_FILES['zip']['size'];
        $zipFileType = $_FILES['zip']['type'];
        $zipFileNameCmps = explode(".", $zipFileName);
        $zipFileExtension = strtolower(end($zipFileNameCmps));

        if ($zipFileExtension === 'zip') {
            $uploadZipDir = 'igre/';
            $newZipFileName = $zipFileName;
            $destZipPath = $uploadZipDir . $newZipFileName;

            if (move_uploaded_file($zipFileTmpPath, $destZipPath)) {
                // Update the 'igre' table with the ZIP file URL
                $updateGameSql = "UPDATE igre SET file_url = :file_url WHERE id = :gameId";
                $stmt2 = $conn->prepare($updateGameSql);
                $stmt2->bindParam(':file_url', $destZipPath, PDO::PARAM_STR);
                $stmt2->bindParam(':gameId', $gameId, PDO::PARAM_INT);

                if ($stmt2->execute()) {
                    // Handle multiple image uploads
                    if (!empty($_FILES['slika']['name'][0])) {
                        $imageUploadDir = 'img/';
                        $uploadedImageIds = [];

                        foreach ($_FILES['slika']['tmp_name'] as $key => $imageTmpPath) {
                            $imageName = $_FILES['slika']['name'][$key];
                            $imageSize = $_FILES['slika']['size'][$key];
                            $imageType = $_FILES['slika']['type'][$key];
                            $imageNameCmps = explode(".", $imageName);
                            $imageExtension = strtolower(end($imageNameCmps));
                            $newImageFileName = md5(time() . $imageName) . '.' . $imageExtension;
                            $destImagePath = $imageUploadDir . $newImageFileName;

                            if (move_uploaded_file($imageTmpPath, $destImagePath)) {
                                // Insert each image into the 'slika' table
                                $slika_id = insertSlika($conn, $destImagePath);

                                if ($slika_id !== false) {
                                    // Store the uploaded image IDs
                                    $uploadedImageIds[] = $slika_id;
                                }
                            }
                        }

                        // Update the 'slika' table with game IDs for each image
                        if (!empty($uploadedImageIds)) {
                            $updateImagesSql = "UPDATE slike SET igra_id = :gameId WHERE id IN ("
                                . implode(",", $uploadedImageIds) . ")";
                            $stmt3 = $conn->prepare($updateImagesSql);
                            $stmt3->bindParam(':gameId', $gameId, PDO::PARAM_INT);

                            if ($stmt3->execute()) {
                                setcookie('prijava', "Hvala za objavo!.");
                                setcookie('good', 1);
                                header('Location: index.php');
                                exit();
                            } else {
                                setcookie('prijava', "Error: " . $stmt3->errorInfo()[2]);
                                setcookie('error', 1);
                                header('Location: index.php');
                                exit();
                            }
                        }
                    } else {
                        setcookie('prijava', 'Hvala za objavo!');
                        setcookie('good', 1);
                        header('Location: index.php');
                        exit();
                    }
                } else {
                    setcookie('prijava', "Error: " . $stmt2->errorInfo()[2]);
                    setcookie('error', 1);
                    header('Location: index.php');
                    exit();
                }
            } else {
                setcookie('prijava', 'There was some error moving the ZIP file to the upload directory. Please make sure the upload directory is writable by the web server.');
                setcookie('error', 1);
                header('Location: index.php');
                exit();
            }
        } else {
            setcookie('prijava', 'Upload failed. Allowed file type for game: zip');
            setcookie('error', 1);
            header('Location: index.php');
            exit();
        }
    } else {
        setcookie('prijava', 'Hvala za objavo!');
        setcookie('good', 1);
        header('Location: index.php');
        exit();
    }
} else {
    setcookie('prijava', "Error: " . $stmt->errorInfo()[2]);
    setcookie('error', 1);
    header('Location: index.php');
    exit();
}

function insertSlika($conn, $url) {
    $slikaSql = "INSERT INTO slike (url) VALUES (:url)";
    $stmt = $conn->prepare($slikaSql);
    $stmt->bindParam(':url', $url, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        return $conn->lastInsertId();
    } else {
        return false;
    }
}
?>
