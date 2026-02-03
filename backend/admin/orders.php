<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$orders = [
    ['id' => 'ORD-001', 'customer' => 'John Doe', 'email' => 'john@example.com', 'amount' => 5500, 'status' => 'Completed', 'date' => '2026-02-02', 'items' => 3],
    ['id' => 'ORD-002', 'customer' => 'Jane Smith', 'email' => 'jane@example.com', 'amount' => 8900, 'status' => 'Pending', 'date' => '2026-02-03', 'items' => 5],
    ['id' => 'ORD-003', 'customer' => 'Mike Johnson', 'email' => 'mike@example.com', 'amount' => 12300, 'status' => 'Processing', 'date' => '2026-02-03', 'items' => 4],
    ['id' => 'ORD-004', 'customer' => 'Sarah Williams', 'email' => 'sarah@example.com', 'amount' => 7200, 'status' => 'Shipped', 'date' => '2026-02-01', 'items' => 2],
    ['id' => 'ORD-005', 'customer' => 'Robert Brown', 'email' => 'robert@example.com', 'amount' => 9800, 'status' => 'Completed', 'date' => '2026-01-31', 'items' => 6],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 30px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-logo {
            padding: 0 25px 30px;
            font-size: 24px;
            font-weight: 900;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 14px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            color: white;
            background: rgba(192, 57, 43, 0.2);
            border-left-color: #c0392b;
        }

        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 30px;
        }

        .header {
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            color: #2c3e50;
        }

        .content-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f5f7fa;
        }

        th {
            padding: 14px;
            text-align: left;
            font-weight: 700;
            color: #2c3e50;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 2px solid #e0e0e0;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #f0f0f0;
            color: #666;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #cfe2ff;
            color: #084298;
        }

        .status-shipped {
            background: #d1ecf1;
            color: #0c5460;
        }

        .action-btn {
            padding: 6px 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 4px;
        }

        .action-btn:hover {
            background: #2980b9;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-logo">⚙️ Admin Panel</div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><span>📊</span> Dashboard</a></li>
                <li><a href="products.php"><span>📦</span> Products</a></li>
                <li><a href="orders.php" class="active"><span>🛒</span> Orders</a></li>
                <li><a href="invoices.php"><span>📄</span> Invoices</a></li>
                <li><a href="analytics.php"><span>📈</span> Analytics</a></li>
                <li><a href="settings.php"><span>⚙️</span> Settings</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>🛒 Order Management</h1>
            </div>

            <div class="content-section">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Amount</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong><?php echo $order['id']; ?></strong></td>
                            <td><?php echo $order['customer']; ?></td>
                            <td><?php echo $order['email']; ?></td>
                            <td><strong>₹<?php echo number_format($order['amount']); ?></strong></td>
                            <td><?php echo $order['items']; ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower(str_replace(' ', '', $order['status'])); ?>">
                                    <?php echo $order['status']; ?>
                                </span>
                            </td>
                            <td><?php echo $order['date']; ?></td>
                            <td>
                                <button class="action-btn">View</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>