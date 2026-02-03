<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
        .admin-container { display: flex; min-height: 100vh; }
        .sidebar { width: 280px; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: white; padding: 30px 0; position: fixed; height: 100vh; overflow-y: auto; box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1); }
        .sidebar-logo { padding: 0 25px 30px; font-size: 24px; font-weight: 900; border-bottom: 2px solid rgba(255, 255, 255, 0.1); }
        .sidebar-menu { list-style: none; padding: 20px 0; }
        .sidebar-menu a { color: rgba(255, 255, 255, 0.7); text-decoration: none; padding: 14px 25px; display: flex; align-items: center; gap: 12px; font-size: 14px; font-weight: 600; transition: all 0.3s ease; border-left: 3px solid transparent; }
        .sidebar-menu a:hover, .sidebar-menu a.active { color: white; background: rgba(192, 57, 43, 0.2); border-left-color: #c0392b; }
        .main-content { margin-left: 280px; flex: 1; padding: 30px; }
        .header { background: white; padding: 25px 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); margin-bottom: 30px; }
        .header h1 { font-size: 28px; color: #2c3e50; }
        .content-section { background: white; border-radius: 12px; padding: 40px; text-align: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); }
        .content-section h2 { color: #999; font-size: 24px; margin-bottom: 15px; }
        @media (max-width: 768px) { .sidebar { width: 100%; height: auto; position: relative; } .main-content { margin-left: 0; padding: 15px; } }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-logo">⚙️ Admin Panel</div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><span>📊</span> Dashboard</a></li>
                <li><a href="products.php"><span>📦</span> Products</a></li>
                <li><a href="orders.php"><span>🛒</span> Orders</a></li>
                <li><a href="invoices.php"><span>📄</span> Invoices</a></li>
                <li><a href="analytics.php"><span>📈</span> Analytics</a></li>
                <li><a href="settings.php" class="active"><span>⚙️</span> Settings</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header"><h1>⚙️ Settings</h1></div>
            <div class="content-section">
                <h2>🔧 Coming Soon</h2>
                <p>System settings and configuration options will be available soon.</p>
            </div>
        </div>
    </div>
</body>
</html>