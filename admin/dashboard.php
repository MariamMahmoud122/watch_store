<?php
session_start();
include "../includes/db.php";


if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}


$count_products = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM products"));
$count_cats     = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM categories"));
$count_users    = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='user'"));

$count_orders   = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM orders")); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | Watch Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: 0.3s;
            color: white;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
        .bg-products { background: linear-gradient(45deg, #4099ff, #73b4ff); }
        .bg-cats { background: linear-gradient(45deg, #2ed8b6, #59e0c5); }
        .bg-users { background: linear-gradient(45deg, #ffb64d, #ffcb80); }
        .bg-orders { background: linear-gradient(45deg, #ff5370, #ff869a); }
        .card-link { color: white; text-decoration: none; font-weight: bold; }
        .card-link:hover { color: #eee; text-decoration: underline; }
        hr { opacity: 0.2; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php"><i class="fa-solid fa-gauge-high text-warning"></i> ADMIN PANEL</a>
        <div class="d-flex align-items-center">
            <span class="text-light me-3 small">Welcome, <?php echo $_SESSION['user_name']; ?></span>
            <a href="../index.php" class="btn btn-outline-light btn-sm me-2"><i class="fa-solid fa-shop"></i> Store</a>
            <a href="../logout.php" class="btn btn-danger btn-sm"><i class="fa-solid fa-power-off"></i></a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Dashboard Overview</h2>
            <p class="text-muted">Manage your store products, orders, and users from here.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-products shadow p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo $count_products; ?></h3>
                        <p class="mb-0">Total Products</p>
                    </div>
                    <i class="fa-solid fa-clock fa-3x opacity-50"></i>
                </div>
                <hr>
                <a href="admin_products.php" class="card-link small d-block text-end">Manage <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-cats shadow p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo $count_cats; ?></h3>
                        <p class="mb-0">Categories</p>
                    </div>
                    <i class="fa-solid fa-tags fa-3x opacity-50"></i>
                </div>
                <hr>
                <a href="manage_categories.php" class="card-link small d-block text-end">Manage <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-users shadow p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo $count_users; ?></h3>
                        <p class="mb-0">Customers</p>
                    </div>
                    <i class="fa-solid fa-users fa-3x opacity-50"></i>
                </div>
                <hr>
                <a href="users.php" class="card-link small d-block text-end">View All <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-orders shadow p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo $count_orders; ?></h3> 
                        <p class="mb-0">New Orders</p>
                    </div>
                    <i class="fa-solid fa-truck fa-3x opacity-50"></i>
                </div>
                <hr>
                <a href="manage_orders.php" class="card-link small d-block text-end">View Orders <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4">
                <h5><i class="fa-solid fa-bolt text-warning"></i> Quick Actions</h5>
                <div class="list-group list-group-flush mt-3">
                    <a href="admin_products.php" class="list-group-item list-group-item-action border-0 px-0">
                        <i class="fa-solid fa-plus-circle text-success me-2"></i> Add New Watch Product
                    </a>
                    <a href="manage_orders.php" class="list-group-item list-group-item-action border-0 px-0">
                        <i class="fa-solid fa-circle-check text-primary me-2"></i> Review Recent Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>