<?php
session_start();
include "includes/db.php";

if (isset($_POST['confirm_order'])) {
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $user_id = $_SESSION['user_id'];
    $errors = [];


    if (empty($phone)) {
        $errors['phone'] = "برجاء إدخال رقم الهاتف.";
    } elseif (!preg_match('/^[0-9]{11}$/', $phone)) {
        $errors['phone'] = "رقم الهاتف يجب أن يكون 11 رقم.";
    }

    if (empty($address)) {
        $errors['address'] = "برجاء إدخال العنوان بالتفصيل.";
    }

    
    if (!empty($errors)) {
        $_SESSION['validation_errors'] = $errors;
        $_SESSION['old_post'] = $_POST; 
        header("Location: " . $_SERVER['HTTP_REFERER']); // بيرجعك لصفحة الشحن تاني مش الإندكس
        exit;
    }


    
    $cart_res = mysqli_query($conn, "SELECT SUM(qty * price) as total FROM cart WHERE user_id = $user_id");
    $cart_total = mysqli_fetch_assoc($cart_res)['total'];

 
    $insert_order = mysqli_query($conn, "INSERT INTO orders (user_id, total_price, status, phone, address) 
                                         VALUES ($user_id, '$cart_total', 'pending', '$phone', '$address')");
    
    if ($insert_order) {
        $order_id = mysqli_insert_id($conn);

       
        $cart_items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");
        while ($item = mysqli_fetch_assoc($cart_items)) {
            $p_id = $item['product_id'];
            $qty = $item['qty'];
            $price = $item['price'];
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, qty, price) 
                                 VALUES ($order_id, $p_id, $qty, '$price')");
        }


        mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");

        header("Location: order_details.php?id=" . $order_id);
        exit;
    }
} else {

    header("Location: index.php");
    exit;
}