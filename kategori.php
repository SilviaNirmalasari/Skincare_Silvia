<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

// Tambah Kategori
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);
    mysqli_query($koneksi, "INSERT INTO categories (nama_kategori) VALUES ('$nama')");
    header("Location: kategori.php");
}

// Hapus Kategori
if (isset($_GET['hapus'])) {
    $id_h = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM categories WHERE id='$id_h'");
    header("Location: kategori.php");
}

$query = mysqli_query($koneksi, "SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Silvia Beauty</title>
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

        /* --- MAIN CONTENT (IDENTIK DENGAN DASHBOARD) --- */
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

        /* --- GRID LAYOUT --- */
        .content-grid { display: grid; grid-template-columns: 350px 1fr; gap: 30px; align-items: start; }

        .data-container { 
            background: white; 
            padding: 30px; 
            border-radius: 24px; 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);
            border: 1px solid #f1f5f9;
        }

        /* --- FORM STYLES --- */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 10px; color: var(--text-dark); }
        
        input[type="text"] { 
            width: 100%; padding: 12px 16px; border-radius: 12px; border: 1.5px solid #e2e8f0; 
            outline: none; transition: 0.3s; box-sizing: border-box; font-family: 'Poppins', sans-serif;
        }
        input[type="text"]:focus { border-color: var(--primary); background: #fff9fb; }
        
        .btn-simpan { 
            width: 100%; background: var(--primary); color: white; border: none; padding: 14px; 
            border-radius: 12px; cursor: pointer; font-weight: 600; transition: 0.3s;
            box-shadow: 0 4px 12px rgba(216, 27, 96, 0.2);
        }
        .btn-simpan:hover { opacity: 0.9; transform: translateY(-2px); }

        /* --- TABLE STYLES (SAMA DENGAN DASHBOARD) --- */
        table { width: 100%; border-collapse: separate; border-spacing: 0 8px; margin-top: -10px; }
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

        .no-badge { background: #f1f5f9; color: var(--text-gray); padding: 5px 10px; border-radius: 8px; font-weight: 700; font-size: 12px; }
        .cat-name { font-weight: 600; color: var(--text-dark); font-size: 15px; }

        .btn-delete { 
            width: 35px; height: 35px; background: #fef2f2; color: #ef4444;
            display: flex; align-items: center; justify-content: center;
            border-radius: 10px; text-decoration: none; transition: 0.2s;
        }
        .btn-delete:hover { background: #ef4444; color: white; }
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
            <a href="kategori.php" class="nav-item active"><i class="fa-solid fa-tags"></i> Kategori</a>
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
                <h2>Kelola Kategori</h2>
                <p style="margin:5px 0 0; color:var(--text-gray); font-size:14px;">Atur kategori produk skincare Silvia Beauty.</p>
            </div>
            <div class="user-profile">
                <i class="fa-solid fa-circle-user"></i>
                <span style="font-weight:600; font-size:14px;">Administrator</span>
            </div>
        </div>

        <div class="content-grid">
            <div class="data-container">
                <h3 style="margin-top:0; font-size:18px; margin-bottom:25px;">Tambah Kategori</h3>
                <form method="post">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" name="nama_kategori" placeholder="Masukan nama kategori..." required autofocus>
                    </div>
                    <button type="submit" name="tambah" class="btn-simpan">
                        <i class="fa-solid fa-save"></i> &nbsp; Simpan Kategori
                    </button>
                </form>
            </div>

            <div class="data-container">
                <h3 style="margin:0; font-size:18px; margin-bottom:20px; font-weight:700;">Daftar Kategori</h3>
                <table>
                    <thead>
                        <tr>
                            <th width="80">No</th>
                            <th>Nama Kategori</th>
                            <th style="text-align: center;" width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; while($c = mysqli_fetch_assoc($query)) : ?>
                        <tr>
                            <td><span class="no-badge"><?= $no++; ?></span></td>
                            <td><span class="cat-name"><?= $c['nama_kategori']; ?></span></td>
                            <td style="display: flex; justify-content: center;">
                                <a href="kategori.php?hapus=<?= $c['id']; ?>" class="btn-delete" title="Hapus" onclick="return confirm('Hapus kategori ini?')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>