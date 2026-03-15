<?php

include "includes/db.php";
include "includes/header.php";


$id = intval($_GET['id']);
if(!isset($_GET['id'])){
    echo "<div class='container mt-5'><h3>المنتج غير موجود</h3></div>";
    include "includes/footer.php";
    exit;
}

$id = intval($_GET['id']);

$query = "SELECT * FROM products WHERE id = $id";

$result = mysqli_query($conn,$query);

$product = mysqli_fetch_assoc($result);

if(!$product){
    echo "<div class='container mt-5'><h3>المنتج غير موجود</h3></div>";
    include "includes/footer.php";
    exit;
}


$query = "

SELECT products.*, categories.name AS category_name

FROM products

LEFT JOIN categories

ON products.category_id = categories.id

WHERE products.id = $id

";

$result = mysqli_query($conn,$query);

$product = mysqli_fetch_assoc($result);

?>

<div class="container mt-5">

<div class="row">

<div class="col-md-6">

<img src="assets/images/<?php echo $product['image']; ?>" class="img-fluid">

</div>

<div class="col-md-6">

<h2><?php echo $product['title']; ?></h2>

<p class="text-muted"><?php echo $product['category_name']; ?></p>

<h3 class="text-danger">

<?php echo number_format($product['price'],2); ?> $

</h3>

<p>

<?php echo nl2br($product['description']); ?>

</p>

</div>

</div>

</div>

<?php include "includes/footer.php"; ?>