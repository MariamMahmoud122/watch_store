<?php
session_start();
include "includes/db.php";
include "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] == 'admin');


if ($is_admin && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    mysqli_query($conn, "UPDATE orders SET status='$new_status' WHERE id=$order_id");
}


if (isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];
    $check = mysqli_query($conn, "SELECT id FROM orders WHERE id=$order_id AND user_id=$user_id AND status='pending'");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE orders SET status='cancelled' WHERE id=$order_id");
        $success_msg = "تم إلغاء الطلب بنجاح.";
    } else {
        $error_msg = "عذراً، لا يمكن إلغاء الطلب حالياً.";
    }
}


if ($is_admin) {
    $query = "SELECT orders.*, users.user_name FROM orders 
              JOIN users ON orders.user_id = users.id 
              ORDER BY created_at DESC";
} else {
    $query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
}
$result = mysqli_query($conn, $query);
?>

<div class="container mt-5" dir="rtl">
    <h2 class="fw-bold mb-4 text-center">
        <i class="fa-solid fa-file-invoice text-primary me-2"></i> 
        <?php echo $is_admin ? "إدارة طلبات المتجر" : "تاريخ طلباتي"; ?>
    </h2>

    <?php if(isset($success_msg)): ?> <div class="alert alert-success shadow-sm"><?php echo $success_msg; ?></div> <?php endif; ?>
    <?php if(isset($error_msg)): ?> <div class="alert alert-danger shadow-sm"><?php echo $error_msg; ?></div> <?php endif; ?>

    <div class="card shadow border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="py-3">رقم الطلب</th>
                        <?php if($is_admin): ?> <th>العميل</th> <?php endif; ?>
                        <th>التاريخ</th>
                        <th>الإجمالي</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="fw-bold">#<?php echo $order['id']; ?></td>
                            <?php if($is_admin): ?> <td><?php echo htmlspecialchars($order['user_name']); ?></td> <?php endif; ?>
                            <td><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                            <td class="fw-bold text-success">$<?php echo number_format($order['total_price'], 2); ?></td>
                            <td>
                                <?php 
                                    $s = $order['status'];
                                    $badge = ($s == 'pending') ? 'bg-warning text-dark' : (($s == 'completed') ? 'bg-success' : 'bg-danger');
                                    $status_ar = ($s == 'pending') ? 'قيد الانتظار' : (($s == 'completed') ? 'تم التسليم' : 'ملغي');
                                    echo "<span class='badge $badge rounded-pill px-3'>$status_ar</span>";
                                ?>
                            </td>
                            <td style="width: 180px;">
    <div class="d-grid gap-2">
        
        <a href="order_details.php?id=<?php echo $order['id']; ?>" 
           class="btn btn-primary btn-sm py-2 shadow-sm" 
           style="background-color: #0d6efd; border: none; font-weight: bold;">
            <i class="fa-solid fa-eye"></i> عرض التفاصيل
        </a>

        <?php if($is_admin): ?>
            <form method="POST" class="d-flex gap-1">
                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                <select name="status" class="form-select form-select-sm border-dark">
                    <option value="pending" <?php if($s=='pending') echo 'selected'; ?>>انتظار</option>
                    <option value="completed" <?php if($s=='completed') echo 'selected'; ?>>تم</option>
                    <option value="cancelled" <?php if($s=='cancelled') echo 'selected'; ?>>ملغي</option>
                </select>
                <button name="update_status" class="btn btn-sm btn-dark">
                    <i class="fa-solid fa-save"></i>
                </button>
            </form>
        <?php else: ?>
            <?php if($s == 'pending'): ?>
                <form method="POST" onsubmit="return confirm('أكيد عايز تكنسل الأوردر؟');">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <button name="cancel_order" class="btn btn-danger btn-sm py-2 w-100 shadow-sm" 
                            style="font-weight: bold;">
                        <i class="fa-solid fa-trash-can"></i> كنسل الأوردر
                    </button>
                </form>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>