<?php 
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

if (isset($_POST['user_name']) && isset($_POST['password']) && isset($_POST['full_name']) && $_SESSION['role'] == 'admin') {
    include "../DB_connection.php";

    function validate_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    $user_name = validate_input($_POST['user_name']);
    $password = validate_input($_POST['password']);
    $full_name = validate_input($_POST['full_name']);

    if (empty($user_name)) {
        $em = "User name is required";
        header("Location: ../add-user.php?error=$em");
        exit();
    } else if (empty($password)) {
        $em = "Password is required";
        header("Location: ../add-user.php?error=$em");
        exit();
    } else if (empty($full_name)) {
        $em = "Full name is required";
        header("Location: ../add-user.php?error=$em");
        exit();
    } else {
        try {
            include "Model/User.php";
            $password = password_hash($password, PASSWORD_DEFAULT);

            $data = array($full_name, $user_name, $password, "employee");
            $result = insert_user($conn, $data);
            
            // Check if insertion was successful
            if ($result) {
                $em = "User created successfully";
                header("Location: ../user.php?success=$em");
                exit();
            } else {
                $em = "Failed to create user";
                header("Location: ../add-user.php?error=$em");
                exit();
            }
        } catch (PDOException $e) {
            $em = "Database error: " . $e->getMessage();
            header("Location: ../add-user.php?error=$em");
            exit();
        } catch (Exception $e) {
            $em = "Error: " . $e->getMessage();
            header("Location: ../add-user.php?error=$em");
            exit();
        }
    }
} else {
    $em = "All fields are required";
    header("Location: ../add-user.php?error=$em");
    exit();
}

} else { 
    $em = "First login";
    header("Location: ../login.php?error=$em");
    exit();
}