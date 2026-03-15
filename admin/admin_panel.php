<?php
session_start();
include "../includes/db.php"; 

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}


$total_products = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM products"));
$total_cats = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM categories"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .sidebar-stat { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .table img { border-radius: 5px; object-fit: cover; border: 1px solid #ddd; }
        .btn-action { margin: 2px; }
        .nav-admin { background: #343a40; color: white; padding: 15px; margin-bottom: 30px; }
    </style>
</head>
<body>

<div class="nav-admin d-flex justify-content-between align-items-center">
    <h4 class="mb-0"><i class="fa-solid fa-gears"></i> Watch Store Dashboard</h4>
    <div>
        <a href="../index.php" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-eye"></i> View Website</a>
        <a href="../logout.php" class="btn btn-danger btn-sm"><i class="fa-solid fa-sign-out"></i> Logout</a>
    </div>
</div>

<div class="container">
    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="sidebar-stat text-center">
                <h6 class="text-muted">Total Products</h6>
                <h2 class="text-primary"><?php echo $total_products; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="sidebar-stat text-center">
                <h6 class="text-muted">Categories</h6>
                <h2 class="text-success"><?php echo $total_cats; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="sidebar-stat text-center">
                <h6 class="text-muted">Admin Role</h6>
                <h2 class="text-warning"><i class="fa-solid fa-user-shield"></i></h2>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">Product Management</h5>
            <div>
                <a href="manage_categories.php" class="btn btn-outline-secondary btn-sm me-2">
                    <i class="fa-solid fa-list"></i> Categories
                </a>
                <a href="add_product.php" class="btn btn-success btn-sm">
                    <i class="fa-solid fa-plus"></i> Add New Product
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Product Title</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT products.*, categories.name AS category_name 
                                  FROM products 
                                  LEFT JOIN categories ON products.category_id = categories.id 
                                  ORDER BY products.id DESC";
                        $result = mysqli_query($conn, $query);

                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)){
                        ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td>
                                <img src="../assets/images/<?php echo $row['image']; ?>" width="60" height="60" alt="product">
                            </td>
                            <td class="fw-bold"><?php echo $row['title']; ?></td>
                            <td><span class="badge bg-info text-dark"><?php echo $row['category_name']; ?></span></td>
                            <td class="text-success fw-bold">$<?php echo number_format($row['price'], 2); ?></td>
                            <td class="text-center">
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm btn-action">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                                <button class="btn btn-danger btn-sm btn-action" onclick="deleteConfirm(<?php echo $row['id']; ?>)">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No products found. Start adding some!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>