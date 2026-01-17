<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

// Ambil data kategori untuk dropdown
$cat_list = mysqli_query($koneksi, "SELECT * FROM categories");

if (isset($_POST['simpan'])) {
    $pilihan = explode("|", $_POST['produk_pilihan']);
    $nama  = $pilihan[0]; 
    $brand = $pilihan[1]; 
    $harga = $pilihan[2]; 
    
    $cat_id    = $_POST['category_id'];
    $kulit     = $_POST['jenis_kulit'];
    $stok      = $_POST['stok'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    $nama_gambar = "";
    if ($_FILES['gambar']['name'] != "") {
        $nama_gambar = time() . '_' . $_FILES['gambar']['name'];
        $lokasi_simpan = 'img_produk/' . $nama_gambar;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $lokasi_simpan);
    }

    $sql = "INSERT INTO products (category_id, nama_produk, brand, jenis_kulit, harga, stok, gambar, deskripsi) 
            VALUES ('$cat_id', '$nama', '$brand', '$kulit', '$harga', '$stok', '$nama_gambar', '$deskripsi')";
    
    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Produk berhasil ditambahkan!'); window.location='katalog.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Silvia Beauty</title>
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
            z-index: 100;
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
        
        .header-top { margin-bottom: 30px; }
        .header-top h2 { font-size: 28px; font-weight: 700; margin: 0; }

        /* --- FORM STYLING --- */
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);
            max-width: 800px;
            border: 1px solid #f1f5f9;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .full-width { grid-column: span 2; }

        .input-group { margin-bottom: 20px; }
        
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            padding-left: 4px;
        }

        select, input, textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-family: 'Poppins';
            font-size: 14px;
            transition: 0.3s;
            box-sizing: border-box;
            outline: none;
            background: #f8fafc;
        }

        select:focus, input:focus, textarea:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px var(--primary-light);
        }

        input[type="file"] {
            padding: 10px;
            background: white;
            border: 2px dashed #e2e8f0;
            cursor: pointer;
        }

        .btn-submit {
            background: var(--primary);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 10px 15px -3px rgba(216, 27, 96, 0.3);
        }

        .btn-submit:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .info-box {
            background: #fff5f8;
            border-left: 4px solid var(--primary);
            padding: 15px;
            border-radius: 12px;
            font-size: 12px;
            color: #ad1457;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
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
            <a href="tambah.php" class="nav-item active"><i class="fa-solid fa-plus-circle"></i> Tambah Produk</a>
            <a href="kategori.php" class="nav-item"><i class="fa-solid fa-tags"></i> Kategori</a>
            <a href="profile.php" class="nav-item"><i class="fa-solid fa-user-gear"></i> Profil Admin</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header-top">
            <p style="color: var(--primary); font-weight: 600; margin: 0; font-size: 14px;">Inventory System</p>
            <h2>Tambah Produk Baru</h2>
        </div>

        <div class="form-container">
            <div class="info-box">
                <i class="fa-solid fa-circle-info"></i>
                Data yang Anda masukkan akan langsung terintegrasi dengan katalog user secara real-time.
            </div>

            <form method="post" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="input-group full-width">
                        <label>Pilih Produk & Brand Base</label>
                        <select name="produk_pilihan" required>
                            <option value="">-- Cari Produk --</option>
                            <option value="Skincare Toner Glad2glow|Glad2glow|45000">Glad2glow - Toner (Rp 45.000)</option>
                            <option value="C-Power Serum|Scarlett|75000">Scarlett - C-Power Serum (Rp 75.000)</option>
                            <option value="5X Ceramide Repair|Skintific|135000">Skintific - 5X Ceramide (Rp 135.000)</option>
                            <option value="Sunscreen Gel SPF 30|Azarine|65000">Azarine - Sunscreen Gel (Rp 65.000)</option>
                            <option value="Facial Wash|Emina|35000">Emina - Facial Wash (Rp 35.000)</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Kategori</label>
                        <select name="category_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php while($cat = mysqli_fetch_assoc($cat_list)) : ?>
                                <option value="<?= $cat['id']; ?>"><?= $cat['nama_kategori']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Target Jenis Kulit</label>
                        <select name="jenis_kulit" required>
                            <option value="Kering">Kulit Kering</option>
                            <option value="Berminyak">Kulit Berminyak</option>
                            <option value="Sensitif">Kulit Sensitif</option>
                            <option value="Kombinasi">Kulit Kombinasi</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Jumlah Stok</label>
                        <input type="number" name="stok" placeholder="Contoh: 25" required>
                    </div>

                    <div class="input-group">
                        <label>Foto Produk</label>
                        <input type="file" name="gambar" accept="image/*">
                    </div>

                    <div class="input-group full-width">
                        <label>Deskripsi Produk</label>
                        <textarea name="deskripsi" rows="4" placeholder="Jelaskan manfaat dan kandungan produk..."></textarea>
                    </div>
                </div>

                <button type="submit" name="simpan" class="btn-submit">
                    <i class="fa-solid fa-cloud-arrow-up"></i> &nbsp; Simpan ke Database
                </button>
            </form>
        </div>
    </div>

</body>
</html>