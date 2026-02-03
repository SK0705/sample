<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$products = [
    ['id' => 1, 'name' => 'Iron Bar – 10mm', 'price' => 55, 'stock' => 450, 'category' => 'Iron Bars'],
    ['id' => 2, 'name' => 'Steel Plate – 5mm', 'price' => 75, 'stock' => 320, 'category' => 'Steel Plates'],
    ['id' => 3, 'name' => 'Steel Rod – 12mm', 'price' => 65, 'stock' => 580, 'category' => 'Rods'],
    ['id' => 4, 'name' => 'Iron Angle – 50x50mm', 'price' => 48, 'stock' => 220, 'category' => 'Angles'],
    ['id' => 5, 'name' => 'Steel Channel – 75mm', 'price' => 85, 'stock' => 180, 'category' => 'Channels'],
    ['id' => 6, 'name' => 'Iron Flat – 40x10mm', 'price' => 45, 'stock' => 310, 'category' => 'Flats'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Admin Panel</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 28px;
            color: #2c3e50;
        }

        .add-btn {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-btn:hover {
            box-shadow: 0 6px 16px rgba(192, 57, 43, 0.3);
            transform: translateY(-2px);
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

            .header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
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
                <li><a href="products.php" class="active"><span>📦</span> Products</a></li>
                <li><a href="orders.php"><span>🛒</span> Orders</a></li>
                <li><a href="invoices.php"><span>📄</span> Invoices</a></li>
                <li><a href="analytics.php"><span>📈</span> Analytics</a></li>
                <li><a href="settings.php"><span>⚙️</span> Settings</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>📦 Products Management</h1>
                <button class="add-btn">+ Add New Product</button>
            </div>

            <div class="content-section">
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><strong><?php echo $product['name']; ?></strong></td>
                            <td><?php echo $product['category']; ?></td>
                            <td><strong>₹<?php echo number_format($product['price']); ?></strong></td>
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