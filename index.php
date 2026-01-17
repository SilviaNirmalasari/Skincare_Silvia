<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

// Ambil statistik untuk Card
$total_produk = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM products"));
$total_stok = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(stok) as total FROM products"))['total'] ?? 0;
$total_kat = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM categories"));

// Ambil data produk untuk tabel
$query = mysqli_query($koneksi, "SELECT products.*, categories.nama_kategori FROM products 
          LEFT JOIN categories ON products.category_id = categories.id ORDER BY products.id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Silvia Beauty</title>
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

        /* --- SIDEBAR UPGRADE --- */
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

        /* --- MAIN CONTENT UPGRADE --- */
        .main-content { margin-left: 260px; flex: 1; padding: 40px; }
        
        .header-top { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 40px; 
        }
        .header-top h2 { font-weight: 700; font-size: 28px; margin: 0; }
        
        .user-profile { 
            display: flex; 
            align-items: center; 
            background: white; 
            padding: 10px 20px; 
            border-radius: 16px; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
        }
        .user-profile i { margin-right: 12px; color: var(--primary); font-size: 20px; }

        /* --- STATS CARDS --- */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px; margin-bottom: 40px; }
        .stat-card { 
            background: white; 
            padding: 25px; 
            border-radius: 24px; 
            display: flex; 
            align-items: center; 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon { 
            width: 60px; 
            height: 60px; 
            border-radius: 18px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 24px; 
            margin-right: 20px; 
        }
        .icon-1 { background: #fff1f2; color: #e11d48; }
        .icon-2 { background: #eff6ff; color: #2563eb; }
        .icon-3 { background: #f0fdf4; color: #16a34a; }
        
        .stat-info h3 { margin: 0; font-size: 26px; font-weight: 700; }
        .stat-info p { margin: 0; font-size: 14px; color: var(--text-gray); font-weight: 500; }

        /* --- TABLE UPGRADE --- */
        .data-container { 
            background: white; 
            padding: 30px; 
            border-radius: 24px; 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);
            border: 1px solid #f1f5f9;
        }
        .table-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 25px; 
        }
        .btn-tambah { 
            background: var(--primary); 
            color: white; 
            text-decoration: none; 
            padding: 12px 24px; 
            border-radius: 14px; 
            font-size: 14px; 
            font-weight: 600; 
            box-shadow: 0 10px 15px -3px rgba(216, 27, 96, 0.3);
            transition: 0.3s;
        }
        .btn-tambah:hover { opacity: 0.9; transform: scale(1.02); }
        
        table { width: 100%; border-collapse: separate; border-spacing: 0 10px; margin-top: -10px; }
        th { 
            text-align: left; 
            padding: 15px 20px; 
            color: var(--text-gray); 
            font-weight: 600; 
            font-size: 12px; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td { 
            padding: 15px 20px; 
            background: white;
            border-top: 1px solid #f8fafc;
            border-bottom: 1px solid #f8fafc;
            font-size: 14px;
        }
        tr td:first-child { border-left: 1px solid #f8fafc; border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
        tr td:last-child { border-right: 1px solid #f8fafc; border-top-right-radius: 12px; border-bottom-right-radius: 12px; }
        
        tr:hover td { background: #fdf2f8; }

        .img-prod { width: 48px; height: 48px; border-radius: 12px; object-fit: cover; border: 2px solid #f1f5f9; }
        .badge-cat { 
            background: #fdf2f8; 
            color: var(--primary); 
            padding: 6px 12px; 
            border-radius: 8px; 
            font-size: 11px; 
            font-weight: 600;
            display: inline-block;
        }
        
        .action-links { display: flex; gap: 10px; }
        .action-links a { 
            width: 35px; height: 35px; 
            display: flex; align-items: center; justify-content: center;
            border-radius: 10px; text-decoration: none; transition: 0.2s;
        }
        .edit { background: #eff6ff; color: #2563eb; }
        .edit:hover { background: #2563eb; color: white; }
        .hapus { background: #fef2f2; color: #ef4444; }
        .hapus:hover { background: #ef4444; color: white; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <h2>✨ Silvia Beauty</h2>
        </div>
        <div class="nav-menu">
            <a href="index.php" class="nav-item active"><i class="fa-solid fa-grid-2"></i> Dashboard</a>
            <a href="katalog.php" class="nav-item"><i class="fa-solid fa-box-open"></i> Katalog</a>
            <a href="tambah.php" class="nav-item"><i class="fa-solid fa-plus-circle"></i> Tambah Produk</a>
            <a href="kategori.php" class="nav-item"><i class="fa-solid fa-tags"></i> Kategori</a>
            <a href="profile.php" class="nav-item"><i class="fa-solid fa-user-gear"></i> Profil Admin</a>
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
                <h2>Dashboard</h2>
                <p style="margin:5px 0 0; color:var(--text-gray); font-size:14px;">Selamat Datang di Sistem Manajemen produk Silvia Beauty</p>
            </div>
            <div class="user-profile">
                <i class="fa-solid fa-circle-user"></i>
                <span style="font-weight:600; font-size:14px;">Administrator</span>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon icon-1"><i class="fa-solid fa-box"></i></div>
                <div class="stat-info">
                    <h3><?= $total_produk; ?></h3>
                    <p>Produk Terdaftar</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-2"><i class="fa-solid fa-list"></i></div>
                <div class="stat-info">
                    <h3><?= $total_kat; ?></h3>
                    <p>Total Kategori</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-3"><i class="fa-solid fa-warehouse"></i></div>
                <div class="stat-info">
                    <h3><?= number_format($total_stok, 0, ',', '.'); ?></h3>
                    <p>Total Stok (Pcs)</p>
                </div>
            </div>
        </div>

        <div class="data-container">
            <div class="table-header">
                <h3 style="margin:0; font-size:18px; font-weight:700;">Daftar Inventori Skincare</h3>
                <a href="tambah.php" class="btn-tambah"><i class="fa-solid fa-plus"></i> &nbsp;Produk Baru</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Brand</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><img src="img_produk/<?= $row['gambar']; ?>" class="img-prod" onerror="this.src='https://via.placeholder.com/150'"></td>
                        <td><b style="color:var(--text-dark);"><?= $row['nama_produk']; ?></b></td>
                        <td><span class="badge-cat"><?= $row['nama_kategori']; ?></span></td>
                        <td style="color: var(--text-gray);"><?= $row['brand']; ?></td>
                        <td><b style="color:var(--primary);">Rp <?= number_format($row['harga'],0,',','.'); ?></b></td>
                        <td><span style="font-weight:600;"><?= $row['stok']; ?></span> <small style="color:var(--text-gray)">pcs</small></td>
                        <td class="action-links">
                            <a href="edit.php?id=<?= $row['id']; ?>" class="edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="hapus.php?id=<?= $row['id']; ?>" class="hapus" onclick="return confirm('Hapus produk ini?')" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>