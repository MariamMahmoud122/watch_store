<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}


if (isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = (int)$_GET['id'];
    

    $status = mysqli_real_escape_string($conn, $_GET['status']);
    
    $time_query = "";

    if ($status == 'paid') {
        $time_query = ", paid_at = NOW()";
    } elseif ($status == 'cancelled') {
        $time_query = ", cancelled_at = NOW()";
    }

  
    $query = "UPDATE orders SET status = '$status' $time_query WHERE id = $order_id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: manage_orders.php?msg=success");
        exit;
    } else {
        die("خطأ في التحديث: " . mysqli_error($conn));
    }
} else {
    header("Location: manage_orders.php");
    exit;
}
?>