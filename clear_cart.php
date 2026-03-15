<?php
session_start();
include "includes/db.php";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    $query = "DELETE FROM cart WHERE user_id = $user_id";
    
    if (mysqli_query($conn, $query)) {
        
        header("Location: cart.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>