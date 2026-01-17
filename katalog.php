<?php
session_start();
include 'koneksi.php';
// Cek login admin agar sidebar tetap sinkron
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

// Ambil data produk
$query = mysqli_query($koneksi, "SELECT products.*, categories.nama_kategori FROM products 
          LEFT JOIN categories ON products.category_id = categories.id ORDER BY products.id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - Silvia Beauty</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #d81b60;
            --primary-light: #fdf2f8;
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

        /* --- SIDEBAR (IDENTIK) --- */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: var(--sidebar);
            border-right: 1px solid #e2e8f0;
            position: fixed;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header { padding: 40px 30px; }
        .sidebar-header h2 { color: var(--primary); font-size: 22px; margin: 0; font-weight: 700; }
        
        .nav-menu { flex: 1; padding: 10px 20px; }
        .nav-item {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            color: var(--text-gray);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: 0.3s;
            font-weight: 500;
        }
        .nav-item i { width: 25px; font-size: 18px; }
        .nav-item:hover, .nav-item.active {
            background: var(--primary-light);
            color: var(--primary);
        }
        .nav-item.active { background: var(--primary); color: white; }

        /* --- MAIN CONTENT --- */
        .main-content { margin-left: 260px; flex: 1; padding: 40px; }
        
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 40px;
        }
        .header-section h2 { font-size: 28px; font-weight: 700; margin: 0; }

        /* --- PRODUCT GRID --- */
        .grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); 
            gap: 25px; 
        }

        .card { 
            background: white; 
            border-radius: 24px; 
            overflow: hidden; 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02); 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
            text-decoration: none; 
            color: inherit; 
            border: 1px solid #f1f5f9;
            position: relative;
        }
        
        .card:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 20px 25px -5px rgba(216, 27, 96, 0.1);
        }

        .image-container {
            position: relative;
            width: 100%;
            height: 280px;
            overflow: hidden;
        }

        .card img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            transition: 0.5s;
        }
        
        .card:hover img { transform: scale(1.1); }

        .category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 12px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 700;
            color: var(--primary);
            backdrop-filter: blur(5px);
        }

        .p-content { padding: 20px; }
        .brand { 
            color: var(--text-gray); 
            font-size: 11px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            margin-bottom: 5px;
            display: block;
        }
        
        .product-name {
            font-weight: 600;
            font-size: 16px;
            color: var(--text-dark);
            margin: 5px 0 15px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 48px;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
        }

        .price { 
            color: var(--primary); 
            font-weight: 700; 
            font-size: 18px; 
        }

        .btn-view {
            width: 35px;
            height: 35px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .card:hover .btn-view {
            background: var(--primary);
            color: white;
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
            <a href="katalog.php" class="nav-item active"><i class="fa-solid fa-box-open"></i> Katalog</a>
            <a href="tambah.php" class="nav-item"><i class="fa-solid fa-plus-circle"></i> Tambah Produk</a>
            <a href="kategori.php" class="nav-item"><i class="fa-solid fa-tags"></i> Kategori</a>
            <a href="profile.php" class="nav-item"><i class="fa-solid fa-user-gear"></i> Profil Admin</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header-section">
            <div>
                <p style="color: var(--primary); font-weight: 600; margin: 0; font-size: 14px;">Product Gallery</p>
                <h2>Katalog Koleksi</h2>
            </div>
            <div style="color: var(--text-gray); font-size: 14px;">
                Total: <b><?= mysqli_num_rows($query); ?></b> Produk
            </div>
        </div>

        <div class="grid">
            <?php while($row = mysqli_fetch_assoc($query)) : ?>
            <a href="detail.php?id=<?= $row['id']; ?>" class="card">
                <div class="image-container">
                    <span class="category-badge"><?= $row['nama_kategori']; ?></span>
                    <img src="img_produk/<?= $row['gambar']; ?>" onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                </div>
                
                <div class="p-content">
                    <span class="brand"><?= $row['brand']; ?></span>
                    <div class="product-name"><?= $row['nama_produk']; ?></div>
                    
                    <div class="card-footer">
                        <div class="price">Rp <?= number_format($row['harga'],0,',','.'); ?></div>
                        <div class="btn-view">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </a>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>