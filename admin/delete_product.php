<?php
session_start();
include "../includes/db.php";

// 1. حماية الصفحة (الأمان أولاً)
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}

if(isset($_GET['id'])){
    $id = (int)$_GET['id'];

    $img_query = mysqli_query($conn, "SELECT image FROM products WHERE id = $id");
    $img_data = mysqli_fetch_assoc($img_query);
    
    if($img_data){
        $img_path = "../assets/images/" . $img_data['image'];
        if(file_exists($img_path)){
            unlink($img_path); 
        }
    }

    
    $delete = mysqli_query($conn, "DELETE FROM products WHERE id = $id");

    if($delete){
        header("Location: admin_panel.php?msg=deleted");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    header("Location: admin_panel.php");
}
exit;
?>