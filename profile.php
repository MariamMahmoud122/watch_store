
<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

include "includes/db.php";
include "includes/header.php";

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn,"SELECT * FROM users WHERE id=$user_id");
$user = mysqli_fetch_assoc($query);
?>

<div class="container mt-5">

<div class="row">


<div class="col-md-4">

<div class="card p-4 text-center shadow">

<i class="fa-solid fa-user fa-4x mb-3"></i>

<h4><?php echo $user['name']; ?></h4>

<p class="text-muted"><?php echo $user['email']; ?></p>

<p>
<b>Role :</b> <?php echo $user['role'] ?? 'user'; ?>
</p>

</div>

</div>



<div class="col-md-8">

<div class="card p-4 shadow">

<h4 class="mb-4">Account Menu</h4>

<div class="list-group">

<a href="edit_profile.php" class="list-group-item list-group-item-action">
<i class="fa-solid fa-user-pen"></i> Edit Profile
</a>

<a href="orders.php" class="list-group-item list-group-item-action">
<i class="fa-solid fa-box"></i> My Orders
</a>

<a href="cart.php" class="list-group-item list-group-item-action">
<i class="fa-solid fa-cart-shopping"></i> My Cart
</a>

<?php if(($user['role'] ?? '') == 'admin'): ?>

<a href="admin/dashboard.php" class="list-group-item list-group-item-action">
<i class="fa-solid fa-gauge"></i> Admin Dashboard
</a>

<?php endif; ?>

</div>

</div>

</div>

</div>

</div>

<?php include "includes/footer.php"; ?>