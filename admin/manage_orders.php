<?php
session_start();

include "../includes/db.php"; 


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}


$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}


$sql = "SELECT orders.*, users.name as customer_name, users.email as customer_email 
        FROM orders 
        JOIN users ON orders.user_id = users.id ";

if (!empty($search)) {
  
    $sql .= " WHERE orders.id = '$search' OR users.name LIKE '%$search%' ";
}

$sql .= " ORDER BY orders.id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة إدارة الطلبات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --main-color: #864383; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-admin { background-color: var(--main-color); color: white; padding: 15px; }
        .card { border-radius: 15px; border: none; }
        .table thead { background-color: var(--main-color); color: white; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        .time-text { font-size: 0.8rem; color: #6c757d; display: block; }
        .search-section { background: white; border-radius: 15px; padding: 20px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
    </style>
</head>
<body>

<div class="navbar-admin shadow-sm mb-4">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="fa-solid fa-user-shield me-2"></i> لوحة تحكم الأدمن</h4>
        <a href="../index.php" class="btn btn-light btn-sm">العودة للموقع</a>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-11">
            
            <div class="search-section shadow-sm">
                <form action="" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 shadow-none" 
                                   placeholder="ابحث برقم الطلب (مثلاً: 5) أو اسم العميل..." 
                                   value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold" style="background: var(--main-color); border:none;">بحث</button>
                        <?php if(!empty($search)): ?>
                            <a href="manage_orders.php" class="btn btn-outline-secondary">إلغاء</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-dark fw-bold">إدارة طلبات الزبائن</h5>
                    <?php if(!empty($search)): ?>
                        <span class="badge bg-light text-dark border">نتائج البحث عن: <?php echo htmlspecialchars($search); ?></span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i> تم تحديث حالة الطلب والتوقيت بنجاح!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border text-center">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>العميل</th>
                                    <th>توقيت الطلب</th>
                                    <th>توقيت الدفع</th>
                                    <th>توقيت الإلغاء</th>
                                    <th>المبلغ</th>
                                    <th>الحالة</th>
                                    <th>إدارة</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($result) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td class="fw-bold text-primary">#<?php echo $row['id']; ?></td>
                                        <td class="text-end">
                                            <div class="fw-bold"><?php echo htmlspecialchars($row['customer_name']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($row['customer_email']); ?></small>
                                        </td>
                                        
                                        <td>
                                            <span class="text-dark"><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></span>
                                            <span class="time-text"><?php echo date('h:i A', strtotime($row['created_at'])); ?></span>
                                        </td>

                                        <td>
                                            <?php if($row['paid_at']): ?>
                                                <span class="text-success fw-bold"><?php echo date('h:i A', strtotime($row['paid_at'])); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if($row['cancelled_at']): ?>
                                                <span class="text-danger fw-bold"><?php echo date('h:i A', strtotime($row['cancelled_at'])); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="fw-bold text-dark">$<?php echo number_format($row['total_price'], 2); ?></td>

                                        <td>
                                            <?php 
                                                $status_map = [
                                                    'pending' => 'bg-warning text-dark',
                                                    'paid' => 'bg-success text-white',
                                                    'shipped' => 'bg-info text-white',
                                                    'cancelled' => 'bg-danger text-white'
                                                ];
                                                $class = $status_map[strtolower($row['status'])] ?? 'bg-secondary text-white';
                                            ?>
                                            <span class="badge status-badge <?php echo $class; ?>">
                                                <?php echo strtoupper($row['status']); ?>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="btn-group border rounded shadow-sm bg-white">
                                                <a href="view_order.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light" title="عرض التفاصيل">
                                                    <i class="fa-solid fa-eye text-primary"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
                                                <ul class="dropdown-menu shadow">
                                                    <li><a class="dropdown-item" href="update_status.php?id=<?php echo $row['id']; ?>&status=shipped"><i class="fa-solid fa-truck me-2 text-info"></i> شحن الطلب</a></li>
                                                    <li><a class="dropdown-item" href="update_status.php?id=<?php echo $row['id']; ?>&status=paid"><i class="fa-solid fa-check me-2 text-success"></i> تم الدفع</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="update_status.php?id=<?php echo $row['id']; ?>&status=cancelled"><i class="fa-solid fa-xmark me-2"></i> إلغاء (Cancel)</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="fa-solid fa-magnifying-glass-blur fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لم نجد أي طلبات مطابقة لـ "<strong><?php echo htmlspecialchars($search); ?></strong>"</p>
                                            <a href="manage_orders.php" class="btn btn-sm btn-link">عرض جميع الطلبات</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>