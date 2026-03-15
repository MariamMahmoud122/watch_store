<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "includes/db.php"; 


$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $u_id = $_SESSION['user_id'];
    $cart_res = mysqli_query($conn, "SELECT SUM(qty) AS total_items FROM cart WHERE user_id = $u_id");
    $cart_data = mysqli_fetch_assoc($cart_res);
    $cart_count = $cart_data['total_items'] ?? 0;
} else {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $cart_count += $item['qty'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Watch Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .bg-custom { background: #864383 !important; }
        .nav-link { transition: 0.3s; padding: 0.5rem 1rem !important; }
        .nav-link:hover { opacity: 0.8; background: rgba(255,255,255,0.1); border-radius: 5px; }
        .badge-cart { font-size: 0.65rem; padding: 0.35em 0.5em; }
        #cart-count { transition: transform 0.2s ease-in-out; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-custom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fa-solid fa-clock"></i> WATCH STORE
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="product.php">Products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link position-relative px-3" href="cart.php">
                        <i class="fa-solid fa-cart-shopping fs-5"></i>
                        <span id="cart-count" 
                              class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-danger badge-cart"
                              style="<?php echo ($cart_count > 0) ? '' : 'display: none;'; ?>">
                            <?php echo $cart_count; ?>
                        </span>
                    </a>
                </li>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="profile.php">
                            <i class="fa-solid fa-circle-user"></i> <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                        </a>
                    </li>

                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold" href="admin/dashboard.php">
                            <i class="fa-solid fa-user-shield"></i> Admin Panel
                        </a>
                    </li>
                    <?php endif; ?>

                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-sm btn-outline-light" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-light ms-lg-2" href="register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if(isset($_SESSION['auth_error'])): ?>
    Swal.fire({
        icon: 'warning',
        title: 'Access Denied!',
        text: '<?php echo $_SESSION['auth_error']; ?>',
        confirmButtonColor: '#864383',
        confirmButtonText: 'Login Now',
        showCancelButton: true
    }).then((result) => {
        if (result.isConfirmed) window.location.href = 'login.php';
    });
    <?php unset($_SESSION['auth_error']); ?>
<?php endif; ?>
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>