<?php
/**
 * Update profile picture
 * Method: POST
 * Source : Estouan Gachelin
 *
 * This file handles the profile picture update server-side logic
 * It receives the profile picture file from the client
 * It validates the file type and size
 * If the file is valid, it moves the uploaded file to the target directory and updates the user's profile picture in the database
 * It sends a JSON response indicating the success or failure of the update process
 */
session_start();
include "../db_connexion.php";
global $conn;
// Check if the user is logged in and if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    // Define the base directory for profile pictures
    $baseProfilePictureDir =  "../uploads/profile_picture/"; // Adjust based on your project structure

    // Ensure the directory exists
    if (!is_dir($baseProfilePictureDir)) {
        mkdir($baseProfilePictureDir, 0777, true); // Creates the directory with appropriate permissions
    }

    // Generate a unique file name
    $targetFileName = uniqid() . '_' . basename($_FILES["profile_picture"]["name"]);
    $targetFilePath = $baseProfilePictureDir . $targetFileName;

    // Validate the file type and size
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
    $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes

    if (!in_array($fileType, $allowedExtensions)) {
        echo json_encode(["success" => false, "message" => "Only JPG, JPEG, PNG, and GIF files are allowed."]);
        exit;
    }

    if ($_FILES["profile_picture"]["size"] > $maxFileSize) {
        echo json_encode(["success" => false, "message" => "File size exceeds the limit of 5MB."]);
        exit;
    }

    // Attempt to move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
        // Get the user ID from the session
        $userId = $_SESSION['user_id'];

        // Fetch the current profile picture from the database
        $selectQuery = "SELECT profile_picture FROM users WHERE id = :id";
        $statement = $conn->prepare($selectQuery);
        $statement->bindParam(":id", $userId);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $oldProfilePicture = $result['profile_picture'];

        // Delete the old profile picture if it exists
        if ($oldProfilePicture && file_exists($baseProfilePictureDir . $oldProfilePicture)) {
            unlink($baseProfilePictureDir . $oldProfilePicture);
        }

        // Update the user's profile picture in the database
        $updateQuery = "UPDATE users SET profile_picture = :profile_picture WHERE id = :id";
        $statement = $conn->prepare($updateQuery);
        $statement->bindParam(":profile_picture", $targetFileName); // Store only the file name
        $statement->bindParam(":id", $userId);

        if ($statement->execute()) {
            echo json_encode(["success" => true, "message" => "Profile picture updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update profile picture in the database."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Failed to upload profile picture."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Vous devez être connecté pour mettre à jour votre photo de profil."]);
}
?>
