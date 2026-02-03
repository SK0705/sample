<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Sample data (in production, fetch from database)
$stats = [
    'total_orders' => 245,
    'total_revenue' => 125000,
    'total_products' => 6,
    'pending_orders' => 12
];

$recent_orders = [
    ['id' => 'ORD-001', 'customer' => 'John Doe', 'amount' => 5500, 'status' => 'Completed', 'date' => '2026-02-02'],
    ['id' => 'ORD-002', 'customer' => 'Jane Smith', 'amount' => 8900, 'status' => 'Pending', 'date' => '2026-02-03'],
    ['id' => 'ORD-003', 'customer' => 'Mike Johnson', 'amount' => 12300, 'status' => 'Processing', 'date' => '2026-02-03'],
    ['id' => 'ORD-004', 'customer' => 'Sarah Williams', 'amount' => 7200, 'status' => 'Shipped', 'date' => '2026-02-01'],
    ['id' => 'ORD-005', 'customer' => 'Robert Brown', 'amount' => 9800, 'status' => 'Completed', 'date' => '2026-01-31'],
];

$products = [
    ['id' => 1, 'name' => 'Iron Bar – 10mm', 'price' => 55, 'stock' => 450],
    ['id' => 2, 'name' => 'Steel Plate – 5mm', 'price' => 75, 'stock' => 320],
    ['id' => 3, 'name' => 'Steel Rod – 12mm', 'price' => 65, 'stock' => 580],
    ['id' => 4, 'name' => 'Iron Angle – 50x50mm', 'price' => 48, 'stock' => 220],
    ['id' => 5, 'name' => 'Steel Channel – 75mm', 'price' => 85, 'stock' => 180],
    ['id' => 6, 'name' => 'Iron Flat – 40x10mm', 'price' => 45, 'stock' => 310],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Steel & Iron Traders</title>
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

        /* ===== Sidebar ===== */
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

        .sidebar-logo span {
            font-size: 28px;
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

        .sidebar-menu a span {
            font-size: 18px;
        }

        .sidebar-logout {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            padding: 0 25px;
        }

        .logout-btn {
            width: 100%;
            background: #c0392b;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #a93226;
            box-shadow: 0 4px 12px rgba(192, 57, 43, 0.3);
        }

        /* ===== Main Content ===== */
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 28px;
            color: #2c3e50;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .user-details {
            text-align: right;
        }

        .user-details p:first-child {
            font-weight: 700;
            color: #2c3e50;
        }

        .user-details p:last-child {
            font-size: 12px;
            color: #999;
        }

        /* ===== Stats Grid ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            transform: translateY(-4px);
        }

        .stat-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 900;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 13px;
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ===== Tables ===== */
        .content-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 800;
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

        .action-btn.delete {
            background: #e74c3c;
        }

        .action-btn.delete:hover {
            background: #c0392b;
        }

        /* ===== Responsive ===== */
        @media (max-width: 1024px) {
            .sidebar {
                width: 240px;
            }

            .main-content {
                margin-left: 240px;
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                border-bottom: 2px solid rgba(0, 0, 0, 0.1);
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .user-info {
                justify-content: flex-start;
            }

            .user-details {
                text-align: left;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .sidebar-menu {
                display: flex;
                overflow-x: auto;
                padding: 0;
            }

            .sidebar-menu li {
                flex-shrink: 0;
            }

            .sidebar-logout {
                position: static;
                margin-top: 20px;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 20px;
            }

            .stat-value {
                font-size: 20px;
            }

            .content-section {
                padding: 15px;
            }

            table {
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-logo">
                <span>⚙️</span>
                <div>Admin Panel</div>
            </div>

            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active"><span>📊</span> Dashboard</a></li>
                <li><a href="products.php"><span>📦</span> Products</a></li>
                <li><a href="orders.php"><span>🛒</span> Orders</a></li>
                <li><a href="invoices.php"><span>📄</span> Invoices</a></li>
                <li><a href="analytics.php"><span>📈</span> Analytics</a></li>
                <li><a href="settings.php"><span>⚙️</span> Settings</a></li>
            </ul>

            <div class="sidebar-logout">
                <a href="logout.php" style="display: block; margin-bottom: 10px;">
                    <button class="logout-btn">🚪 Logout</button>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['admin_username'], 0, 1)); ?></div>
                    <div class="user-details">
                        <p><?php echo ucfirst($_SESSION['admin_username']); ?></p>
                        <p>Admin</p>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">🛒</div>
                    <div class="stat-value"><?php echo number_format($stats['total_orders']); ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-value">₹<?php echo number_format($stats['total_revenue']); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">📦</div>
                    <div class="stat-value"><?php echo $stats['total_products']; ?></div>
                    <div class="stat-label">Products</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-value"><?php echo $stats['pending_orders']; ?></div>
                    <div class="stat-label">Pending Orders</div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="content-section">
                <div class="section-title">📋 Recent Orders</div>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $order): ?>
                        <tr>
                            <td><strong><?php echo $order['id']; ?></strong></td>
                            <td><?php echo $order['customer']; ?></td>
                            <td><strong>₹<?php echo number_format($order['amount']); ?></strong></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                    <?php echo $order['status']; ?>
                                </span>
                            </td>
                            <td><?php echo $order['date']; ?></td>
                            <td>
                                <button class="action-btn">View</button>
                                <button class="action-btn delete">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Products Table -->
            <div class="content-section">
                <div class="section-title">📦 Products Inventory</div>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><strong><?php echo $product['name']; ?></strong></td>
                            <td>₹<?php echo number_format($product['price']); ?></td>
                            <td>
                                <span style="background: #d4edda; padding: 4px 8px; border-radius: 4px; color: #155724; font-weight: 700;">
                                    <?php echo $product['stock']; ?> kg
                                </span>
                            </td>
                            <td>
                                <button class="action-btn">Edit</button>
                                <button class="action-btn delete">Delete</button>
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