<?php
session_start();
include "../db_connexion.php";
global $conn;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    // Define the base directory for banner pictures
    $baseBannerDir = "../uploads/profile_banner/"; // Adjust based on your project structure

    // Ensure the directory exists
    if (!is_dir($baseBannerDir)) {
        mkdir($baseBannerDir, 0777, true); // Creates the directory with appropriate permissions
    }

    // Generate a unique file name
    $targetFileName = uniqid() . '_' . basename($_FILES["banner"]["name"]);
    $targetFilePath = $baseBannerDir . $targetFileName;

    // Validate the file type and size
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
    $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes

    if (!in_array($fileType, $allowedExtensions)) {
        echo json_encode(["success" => false, "message" => "Only JPG, JPEG, PNG, and GIF files are allowed."]);
        exit;
    }

    if ($_FILES["banner"]["size"] > $maxFileSize) {
        echo json_encode(["success" => false, "message" => "File size exceeds the limit of 10MB."]);
        exit;
    }

    // Attempt to move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["banner"]["tmp_name"], $targetFilePath)) {
        // Get the user ID from the session
        $userId = $_SESSION['user_id'];

        // Fetch the current banner from the database
        $selectQuery = "SELECT banner_picture FROM users WHERE id = :id";
        $statement = $conn->prepare($selectQuery);
        $statement->bindParam(":id", $userId, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $oldBanner = $result['banner_picture'];

        // Delete the old banner if it exists
        if ($oldBanner && file_exists($baseBannerDir . $oldBanner)) {
            unlink($baseBannerDir . $oldBanner);
        }

        // Update the user's banner in the database
        $updateQuery = "UPDATE users SET banner_picture = :banner_picture WHERE id = :id";
        $statement = $conn->prepare($updateQuery);
        $statement->bindParam(":banner_picture", $targetFileName, PDO::PARAM_STR); // Store only the file name
        $statement->bindParam(":id", $userId, PDO::PARAM_INT);

        if ($statement->execute()) {
            echo json_encode(["success" => true, "message" => "Banner updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update banner in the database."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Failed to upload the banner."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
