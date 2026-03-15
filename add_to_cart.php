<?php
session_start();
include "includes/db.php";


if(!isset($_SESSION['user_id'])){
    echo "login_required";
    exit;
}

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    exit;
}

$id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];


$result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id LIMIT 1");
$product = mysqli_fetch_assoc($result);

if(!$product){
    exit; 
}


$check_cart = mysqli_query($conn, "SELECT id FROM cart WHERE user_id = $user_id AND product_id = $id");

if(mysqli_num_rows($check_cart) > 0){
   
    mysqli_query($conn, "UPDATE cart SET qty = qty + 1 WHERE user_id = $user_id AND product_id = $id");
} else {
    
    $title = mysqli_real_escape_string($conn, $product['title']);
    $price = $product['price'];
    $image = $product['image'];
    
    mysqli_query($conn, "INSERT INTO cart (user_id, product_id, title, price, image, qty) 
                         VALUES ('$user_id', '$id', '$title', '$price', '$image', 1)");
}



if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][$id] = [
    "title" => $product['title'],
    "price" => $product['price'],
    "image" => $product['image'],
    "qty" => (isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id]['qty'] + 1 : 1)
];

echo "added";