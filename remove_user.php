<?php
session_start();

// Ensure the user is logged in as a manager
if (!isset($_SESSION['manager_loggedin'])) {
    header('Location: login_manager.php');
    exit;
}

// Include the database connection file
require_once "database.php";

// Check if an ID is provided
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prepare the delete statement
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Redirect back to the user management page with a success message
        header("Location: sign_up.php?message=User+removed+successfully");
        exit();
    } else {
        // Redirect back with an error message
        header("Location: sign_up.php?error=Failed+to+remove+user");
        exit();
    }
} else {
    // Redirect back with an error message if no ID is provided
    header("Location: sign_up.php?error=No+user+ID+provided");
    exit();
}

$conn->close();
?>
