<?php
session_start();
include "db_connexion.php";
global $conn;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $targetDir = "profile_picture/"; // Directory where uploaded files will be stored

    // Generate a unique file name to prevent overwriting existing files
    $targetFile = $targetDir . uniqid() . '_' . basename($_FILES["profile_picture"]["name"]);

    // Check if the file meets the required conditions (e.g., file type, size)
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedExtensions = array("jpg", "jpeg", "png", "gif");

    if (!in_array($fileType, $allowedExtensions)) {
        echo json_encode(array("success" => false, "message" => "Only JPG, JPEG, PNG, and GIF files are allowed."));
        exit;
    }

    // Check file size (limit it to, for example, 5MB)
    $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
    if ($_FILES["profile_picture"]["size"] > $maxFileSize) {
        echo json_encode(array("success" => false, "message" => "File size exceeds the limit of 5MB."));
        exit;
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
        // Fetch the user's current profile picture path
        $userId = $_SESSION['user_id'];
        $selectQuery = "SELECT profile_picture FROM users WHERE user_id = :user_id";
        $statement = $conn->prepare($selectQuery);
        $statement->bindParam(":user_id", $userId);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $oldProfilePicture = $result['profile_picture'];

        // Delete the old profile picture file if it exists
        if ($oldProfilePicture && file_exists($oldProfilePicture)) {
            if (unlink($oldProfilePicture)) {
                // Log deletion success
                error_log("Old profile picture deleted: " . $oldProfilePicture);
            } else {
                // Log deletion failure
                error_log("Failed to delete old profile picture: " . $oldProfilePicture);
            }
        }

        // Update the user's profile picture path in the database
        $updateQuery = "UPDATE users SET profile_picture = :profile_picture WHERE user_id = :user_id";
        $statement = $conn->prepare($updateQuery);
        $statement->bindParam(":profile_picture", $targetFile);
        $statement->bindParam(":user_id", $userId);

        if ($statement->execute()) {
            echo json_encode(array("success" => true, "message" => "Profile picture updated successfully."));
        } else {
            echo json_encode(array("success" => false, "message" => "Failed to update profile picture in the database."));
        }

        // Close the statement
        $statement = null; // or unset($statement);
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to upload profile picture."));
    }
}
?>
