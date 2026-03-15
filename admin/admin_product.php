<?php
session_start();
require_once '../includes/db.php';


if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}

$success = $error = "";


if(isset($_POST['add_product'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = $_POST['category_id'];

    $image_name = time() . '_' . $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $upload_dir = "../assets/images/" . $image_name;

    if(move_uploaded_file($image_tmp, $upload_dir)){
        $stmt = $conn->prepare("INSERT INTO products (title, price, description, category_id, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsis", $title, $price, $description, $category_id, $image_name);
        if($stmt->execute()) $success = "تم إضافة المنتج بنجاح!";
        else $error = "حدث خطأ أثناء الإضافة.";
    } else {
        $error = "فشل في رفع الصورة.";
    }
}

if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $res = mysqli_query($conn, "SELECT image FROM products WHERE id=$id");
    if($row = mysqli_fetch_assoc($res)){
        if(file_exists("../assets/images/".$row['image'])) unlink("../assets/images/".$row['image']);
        mysqli_query($conn, "DELETE FROM products WHERE id=$id");
        header("Location: admin_products.php?msg=deleted");
        exit;
    }
}


if(isset($_POST['update_product'])){
    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = $_POST['category_id'];

    $image_update = "";
    if(!empty($_FILES['image']['name'])){
        $image_name = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/".$image_name);
        $image_update = ", image='$image_name'";
    }

    $sql = "UPDATE products SET title=?, price=?, description=?, category_id=? $image_update WHERE id=?";
    $stmt = $conn->prepare("UPDATE products SET title=?, price=?, description=?, category_id=? $image_update WHERE id=?");
    $stmt->bind_param("sdsii", $title, $price, $description, $category_id, $id);
    
    if($stmt->execute()) $success = "تم التعديل بنجاح!";
    else $error = "فشل التعديل.";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة المنتجات | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
        .card { border: none; box-shadow: 0 0 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-boxes-stacked text-primary"></i> إدارة المنتجات</h2>
        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">العودة للرئيسية</a>
    </div>

    <?php if($success): ?> <script>Swal.fire('نجاح', '<?php echo $success; ?>', 'success');</script> <?php endif; ?>
    <?php if(isset($_GET['msg']) && $_GET['msg']=='deleted'): ?> <div class="alert alert-success">تم حذف المنتج بنجاح.</div> <?php endif; ?>

    <div class="card mb-5">
        <div class="card-header bg-white fw-bold">إضافة منتج جديد</div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">اسم الساعة</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">السعر ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">القسم</label>
                    <select name="category_id" class="form-select" required>
                        <?php 
                        $cats = mysqli_query($conn, "SELECT * FROM categories");
                        while($c = mysqli_fetch_assoc($cats)) echo "<option value='{$c['id']}'>{$c['name']}</option>";
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">الصورة</label>
                    <input type="file" name="image" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" name="add_product" class="btn btn-success px-5">إضافة المنتج</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th>الصورة</th>
                        <th>الاسم</th>
                        <th>السعر</th>
                        <th>القسم</th>
                        <th class="text-center">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn,"SELECT products.*, categories.name AS cat_name FROM products LEFT JOIN categories ON products.category_id=categories.id ORDER BY id DESC");
                    while($row = mysqli_fetch_assoc($res)){
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $row['id']; ?></td>
                        <td><img src="../assets/images/<?php echo $row['image']; ?>" class="product-img"></td>
                        <td class="fw-bold"><?php echo $row['title']; ?></td>
                        <td class="text-success fw-bold">$<?php echo $row['price']; ?></td>
                        <td><span class="badge bg-info text-dark"><?php echo $row['cat_name']; ?></span></td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm edit-btn" 
                                    data-id="<?php echo $row['id']; ?>"
                                    data-title="<?php echo $row['title']; ?>"
                                    data-price="<?php echo $row['price']; ?>"
                                    data-category="<?php echo $row['category_id']; ?>"
                                    data-description="<?php echo $row['description']; ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <a href="admin_products.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm delete-confirm">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل بيانات المنتج</h5>
                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_id">
                <div class="mb-3">
                    <label>اسم المنتج</label>
                    <input type="text" name="title" id="edit_title" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>السعر ($)</label>
                        <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>القسم</label>
                        <select name="category_id" id="edit_cat" class="form-select">
                            <?php 
                            mysqli_data_seek($cats, 0);
                            while($c = mysqli_fetch_assoc($cats)) echo "<option value='{$c['id']}'>{$c['name']}</option>";
                            ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label>الوصف</label>
                    <textarea name="description" id="edit_desc" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label>تغيير الصورة (اختياري)</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="update_product" class="btn btn-primary">حفظ التعديلات</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.onclick = function() {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_title').value = this.dataset.title;
            document.getElementById('edit_price').value = this.dataset.price;
            document.getElementById('edit_cat').value = this.dataset.category;
            document.getElementById('edit_desc').value = this.dataset.description;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        };
    });

   
    document.querySelectorAll('.delete-confirm').forEach(btn => {
        btn.onclick = function(e) {
            e.preventDefault();
            const url = this.href;
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن تتمكن من التراجع عن الحذف!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'نعم، احذف!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = url;
            });
        };
    });
</script>
</body>
</html>