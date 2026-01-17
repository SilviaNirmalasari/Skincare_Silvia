<?php
session_start();
include 'koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

// Ambil ID dari URL
if (!isset($_GET['id'])) { header("Location: katalog.php"); exit; }
$id = $_GET['id'];

// Ambil data produk detail
$query = mysqli_query($koneksi, "SELECT products.*, categories.nama_kategori 
          FROM products 
          LEFT JOIN categories ON products.category_id = categories.id 
          WHERE products.id = '$id'");
$p = mysqli_fetch_assoc($query);

if (!$p) { echo "Produk tidak ditemukan!"; exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail <?= $p['nama_produk']; ?> - Silvia Beauty</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #d81b60;
            --primary-light: #fdf2f8;
            --bg: #f0f2f5;
            --text-dark: #1e293b;
            --text-gray: #64748b;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .detail-card {
            background: white;
            max-width: 900px;
            width: 100%;
            border-radius: 30px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1);
        }

        /* Bagian Gambar */
        .detail-image {
            flex: 1;
            background: #fff;
            position: relative;
        }

        .detail-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: white;
            width: 45px;
            height: 45px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--text-dark);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .back-btn:hover { background: var(--primary); color: white; }

        /* Bagian Info */
        .detail-info {
            flex: 1.2;
            padding: 50px;
            display: flex;
            flex-direction: column;
        }

        .badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            background: var(--primary-light);
            color: var(--primary);
            margin-bottom: 15px;
        }

        .brand-name { color: var(--text-gray); font-size: 14px; text-transform: uppercase; letter-spacing: 2px; }
        .product-title { font-size: 32px; font-weight: 700; margin: 10px 0; color: var(--text-dark); }
        .price-tag { font-size: 24px; color: var(--primary); font-weight: 700; margin-bottom: 25px; }

        .specs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 20px;
        }

        .spec-item span { display: block; font-size: 12px; color: var(--text-gray); }
        .spec-item b { font-size: 14px; color: var(--text-dark); }

        .description { color: var(--text-gray); font-size: 14px; line-height: 1.6; margin-bottom: 40px; }

        .action-btns { display: flex; gap: 15px; }

        .btn {
            flex: 1;
            padding: 15px;
            border-radius: 15px;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-edit { background: var(--primary); color: white; box-shadow: 0 10px 15px rgba(216, 27, 96, 0.2); }
        .btn-delete { background: #fee2e2; color: #ef4444; }

        .btn:hover { transform: translateY(-3px); opacity: 0.9; }

        @media (max-width: 768px) {
            .detail-card { flex-direction: column; }
            .detail-image { height: 350px; }
        }
    </style>
</head>
<body>

    <div class="detail-card">
        <div class="detail-image">
            <a href="katalog.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
            <img src="img_produk/<?= $p['gambar']; ?>" onerror="this.src='https://via.placeholder.com/500x600?text=Beauty+Product'">
        </div>

        <div class="detail-info">
            <div class="top-meta">
                <span class="badge"><?= $p['nama_kategori']; ?></span>
                <div class="brand-name"><?= $p['brand']; ?></div>
                <h1 class="product-title"><?= $p['nama_produk']; ?></h1>
                <div class="price-tag">Rp <?= number_format($p['harga'],0,',','.'); ?></div>
            </div>

            <div class="specs">
                <div class="spec-item">
                    <span>Target Kulit</span>
                    <b><i class="fa-solid fa-droplet" style="color:#3b82f6"></i> <?= $p['jenis_kulit']; ?></b>
                </div>
                <div class="spec-item">
                    <span>Persediaan</span>
                    <b><i class="fa-solid fa-box" style="color:#10b981"></i> <?= $p['stok']; ?> unit</b>
                </div>
            </div>

            <div class="description">
                <h4 style="margin-bottom:10px; color: var(--text-dark)">Deskripsi Produk</h4>
                <?= nl2br($p['deskripsi']); ?>
            </div>

            <div class="action-btns">
                <a href="edit.php?id=<?= $p['id']; ?>" class="btn btn-edit">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Produk
                </a>
                <a href="hapus.php?id=<?= $p['id']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                    <i class="fa-solid fa-trash"></i> Hapus
                </a>
            </div>
        </div>
    </div>

</body>
</html>