<?php
session_start();

include "../includes/db.php"; 


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}


$query = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $query);


if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
  
    if ($id == $_SESSION['user_id']) {
        header("Location: users.php?error=self_delete");
        exit;
    }
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");
    header("Location: users.php?msg=deleted");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --main-color: #864383; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-admin { background-color: var(--main-color); color: white; padding: 15px; }
        .card { border-radius: 15px; border: none; }
        .table thead { background-color: var(--main-color); color: white; }
        .avatar-circle {
            width: 40px; height: 40px; background-color: #ddd;
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; font-weight: bold; color: #555;
        }
    </style>
</head>
<body>

<div class="navbar-admin shadow-sm mb-4">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="fa-solid fa-users-gear me-2"></i> إدارة الأعضاء</h4>
        <div class="d-flex gap-2">
            <a href="manage_orders.php" class="btn btn-outline-light btn-sm">الطلبات</a>
            <a href="../index.php" class="btn btn-light btn-sm">الموقع الرئيسي</a>
        </div>
    </div>
</div>

<div class="container mt-4">
    <?php if(isset($_GET['error']) && $_GET['error'] == 'self_delete'): ?>
        <div class="alert alert-danger">لا يمكنك حذف حسابك الشخصي أثناء تسجيل الدخول!</div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-user-group text-muted me-2"></i> قائمة المستخدمين المسجلين</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الصلاحية</th>
                            <th>تاريخ التسجيل</th>
                            <th>إدارة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = mysqli_fetch_assoc($result)): ?>
                        <tr class="text-center">
                            <td><?php echo $user['id']; ?></td>
                            <td class="text-end ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3 ms-2">
                                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($user['name']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php if($user['role'] == 'admin'): ?>
                                    <span class="badge bg-dark"><i class="fa-solid fa-user-shield me-1"></i> Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><i class="fa-solid fa-user me-1"></i> User</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?php echo date('Y-m-d', strtotime($user['created_at'] ?? 'now')); ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="users.php?delete=<?php echo $user['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم نهائياً؟')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>