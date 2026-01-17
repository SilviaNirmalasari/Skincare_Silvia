<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

// Ambil statistik untuk Profile
$produk_count = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM products"));
$kategori_count = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM categories"));
$stok_count = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(stok) as total FROM products"))['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin - Silvia Beauty</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #d81b60;
            --primary-light: #fce4ec;
            --bg: #f0f2f5;
            --sidebar: #ffffff;
            --text-dark: #1e293b;
            --text-gray: #64748b;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            margin: 0;
            display: flex;
            color: var(--text-dark);
        }

        /* --- SIDEBAR (IDENTIK DENGAN DASHBOARD) --- */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: var(--sidebar);
            border-right: 1px solid #e2e8f0;
            position: fixed;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,0.02);
        }
        .sidebar-header { padding: 40px 30px; text-align: left; }
        .sidebar-header h2 {
            color: var(--primary);
            font-size: 22px;
            margin: 0;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
       
        .nav-menu { flex: 1; padding: 10px 20px; }
        .nav-item {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            color: var(--text-gray);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
        }
        .nav-item i { width: 25px; font-size: 18px; }
        .nav-item:hover {
            background: #fff5f8;
            color: var(--primary);
            transform: translateX(5px);
        }
        .nav-item.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(216, 27, 96, 0.3);
        }
       
        .sidebar-footer { padding: 20px; border-top: 1px solid #f1f5f9; }
        .btn-logout {
            display: flex;
            align-items: center;
            color: #ef4444;
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-logout:hover { background: #fef2f2; }

        /* --- MAIN CONTENT --- */
        .main-content { margin-left: 260px; flex: 1; padding: 40px; display: flex; flex-direction: column; }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
        .header-top h2 { font-weight: 700; font-size: 28px; margin: 0; }

        /* --- PROFILE CARD UPGRADE --- */
        .profile-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
        }

        .profile-card {
            background: white;
            width: 100%;
            max-width: 500px;
            padding: 50px 40px;
            border-radius: 30px;
            text-align: center;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
            position: relative;
        }

        .profile-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 8px;
            background: var(--primary);
            border-radius: 30px 30px 0 0;
        }

        .avatar-section {
            width: 120px;
            height: 120px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            margin: 0 auto 25px;
            transform: rotate(-5deg);
            box-shadow: 0 10px 20px rgba(216, 27, 96, 0.1);
        }

        .admin-name {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            text-transform: uppercase;
        }

        .admin-role {
            color: var(--primary);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1.5px;
            margin-top: 5px;
            display: block;
        }

        /* --- STATS MINI GRID --- */
        .profile-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 35px 0;
        }

        .p-stat-box {
            background: #f8fafc;
            padding: 15px 10px;
            border-radius: 20px;
            border: 1px solid #f1f5f9;
        }

        .p-stat-box span {
            display: block;
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .p-stat-box small {
            font-size: 11px;
            color: var(--text-gray);
            font-weight: 500;
        }

        .session-info {
            background: #fff5f8;
            color: var(--primary);
            padding: 12px;
            border-radius: 15px;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <h2>✨ Silvia Beauty</h2>
        </div>
        <div class="nav-menu">
            <a href="index.php" class="nav-item"><i class="fa-solid fa-grid-2"></i> Dashboard</a>
            <a href="katalog.php" class="nav-item"><i class="fa-solid fa-box-open"></i> Katalog</a>
            <a href="tambah.php" class="nav-item"><i class="fa-solid fa-plus-circle"></i> Tambah Produk</a>
            <a href="kategori.php" class="nav-item"><i class="fa-solid fa-tags"></i> Kategori</a>
            <a href="profile.php" class="nav-item active"><i class="fa-solid fa-user-gear"></i> Profil Admin</a>
        </div>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout" onclick="return confirm('Yakin ingin keluar?')">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> &nbsp; Logout
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="header-top">
            <div>
                <h2>Profil Admin</h2>
                <p style="margin:5px 0 0; color:var(--text-gray); font-size:14px;">Informasi akun administrator sistem.</p>
            </div>
        </div>

        <div class="profile-container">
            <div class="profile-card">
                <div class="avatar-section">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                
                <span class="admin-role">SISTEM ADMINISTRATOR</span>
                <h2 class="admin-name"><?= $_SESSION['admin']; ?></h2>
                <p style="color: var(--text-gray); font-size: 14px; margin-top: 5px;">Silvia Beauty Premium v1.0.4</p>

                <div class="profile-stats">
                    <div class="p-stat-box">
                        <span><?= $produk_count; ?></span>
                        <small>Produk</small>
                    </div>
                    <div class="p-stat-box">
                        <span><?= $kategori_count; ?></span>
                        <small>Kategori</small>
                    </div>
                    <div class="p-stat-box">
                        <span><?= number_format($stok_count, 0, ',', '.'); ?></span>
                        <small>Total Stok</small>
                    </div>
                </div>

                <div class="session-info">
                    <i class="fa-regular fa-clock"></i>
                    Sesi Aktif: <?= date('d M Y'); ?>
                </div>
            </div>
        </div>
    </div>

</body>
</html>