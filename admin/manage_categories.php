<?php
session_start();
include "../includes/db.php"; 


if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}

$message = "";

if(isset($_POST['add_cat'])){
    $name = mysqli_real_escape_string($conn, $_POST['cat_name']);
    if(!empty($name)){
        $check = mysqli_query($conn, "SELECT id FROM categories WHERE name='$name'");
        if(mysqli_num_rows($check) > 0){
            $message = "<div class='alert alert-warning'>This category already exists!</div>";
        } else {
            $sql = "INSERT INTO categories (name) VALUES ('$name')";
            if(mysqli_query($conn, $sql)){
                $message = "<div class='alert alert-success'>Category added successfully!</div>";
            }
        }
    }
}

// 2. كود حذف قسم (بعد التأكد إنه مش مربوط بمنتجات)
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    
    // تأكد أولاً أن القسم لا يحتوي على منتجات منعاً لمشاكل الداتابيز
    $check_products = mysqli_query($conn, "SELECT id FROM products WHERE category_id = $id");
    if(mysqli_num_rows($check_products) > 0){
        $message = "<div class='alert alert-danger'>Cannot delete! This category contains products.</div>";
    } else {
        mysqli_query($conn, "DELETE FROM categories WHERE id = $id");
        header("Location: manage_categories.php?msg=deleted");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .main-card { border: none; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .btn-back { margin-bottom: 20px; display: inline-block; text-decoration: none; color: #666; }
    </style>
</head>
<body>

<div class="container mt-5">
    <a href="admin_panel.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card main-card p-4 mb-4">
                <h5 class="mb-3">Add New Category</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="cat_name" class="form-control" placeholder="e.g. Luxury, Sport..." required>
                    </div>
                    <button name="add_cat" class="btn btn-primary w-100">Add Category</button>
                </form>
            </div>
            <?php echo $message; ?>
            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted') echo "<div class='alert alert-success'>Deleted successfully!</div>"; ?>
        </div>

        <div class="col-md-8">
            <div class="card main-card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Existing Categories</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category Name</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
                            while($row = mysqli_fetch_assoc($res)){
                            ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td class="fw-bold"><?php echo $row['name']; ?></td>
                                <td class="text-end">
                                    <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Products in this category might be affected!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'manage_categories.php?delete=' + id;
        }
    })
}
</script>

</body>
</html>