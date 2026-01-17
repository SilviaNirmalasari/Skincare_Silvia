<?php
include 'koneksi.php';

$query = mysqli_query($koneksi, "SELECT products.*, categories.nama_kategori FROM products 
          LEFT JOIN categories ON products.category_id = categories.id ORDER BY RAND() LIMIT 8");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Silvia Beauty | Your Glow Up Partner</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #d81b60; --pink-soft: #fce4ec; --dark: #1a1a1a; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #ffffff; color: var(--dark); overflow-x: hidden; }

        /* NAVBAR */
        nav { display: flex; justify-content: space-between; align-items: center; padding: 20px 8%; position: fixed; width: 100%; top: 0; background: rgba(255,255,255,0.8); backdrop-filter: blur(15px); z-index: 1000; border-bottom: 1px solid rgba(216, 27, 96, 0.1); }
        .logo { font-weight: 700; color: var(--primary); font-size: 24px; letter-spacing: -1px; }
        .nav-links a { text-decoration: none; color: #555; margin-left: 35px; font-size: 14px; font-weight: 500; transition: 0.3s; }
        .nav-links a:hover { color: var(--primary); }
        .btn-admin { padding: 10px 25px; background: var(--primary); color: white !important; border-radius: 50px; box-shadow: 0 4px 15px rgba(216, 27, 96, 0.3); }

        /* HERO SECTION */
        .hero { display: flex; align-items: center; padding: 150px 8% 100px; min-height: 90vh; background: radial-gradient(circle at 80% 20%, var(--pink-soft), #ffffff); }
        .hero-text { flex: 1; padding-right: 50px; }
        .hero-text span { color: var(--primary); font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 2px; }
        .hero-text h1 { font-size: 60px; line-height: 1.1; margin: 15px 0 25px; font-weight: 700; }
        .hero-text p { color: #666; font-size: 18px; margin-bottom: 35px; line-height: 1.6; }
        .btn-explore { padding: 16px 40px; background: var(--dark); color: white; text-decoration: none; border-radius: 50px; font-weight: 600; display: inline-block; transition: 0.3s; }
        .btn-explore:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

        /* PRODUCT SECTION */
        .products { padding: 80px 8%; background: #fff9fb; }
        .section-title { text-align: center; margin-bottom: 50px; }
        .section-title h2 { font-size: 32px; margin-bottom: 10px; }
        .section-title .line { width: 60px; height: 4px; background: var(--primary); margin: 0 auto; border-radius: 2px; }

        .grid-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; }
        .product-card { background: white; padding: 20px; border-radius: 25px; transition: 0.3s; position: relative; border: 1px solid #f0f0f0; }
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
        .product-card img { width: 100%; height: 250px; object-fit: cover; border-radius: 20px; margin-bottom: 15px; }
        .cat-tag { font-size: 11px; color: var(--primary); background: var(--pink-soft); padding: 4px 10px; border-radius: 50px; font-weight: 600; }
        .product-card h3 { font-size: 18px; margin: 10px 0 5px; color: var(--dark); }
        .product-card p { font-size: 13px; color: #888; margin-bottom: 15px; }
        .price { font-weight: 700; color: var(--primary); font-size: 18px; }

        /* FOOTER */
        footer { background: var(--dark); color: #888; padding: 60px 8% 30px; text-align: center; }
        .footer-logo { color: white; font-size: 24px; font-weight: 700; margin-bottom: 20px; }
        .social-links { margin-bottom: 30px; }
        .social-links a { color: white; margin: 0 15px; font-size: 20px; transition: 0.3s; }
        .social-links a:hover { color: var(--primary); }
    </style>
</head>
<body>

    <nav>
        <div class="logo">✨ Silvia Beauty</div>
        <div class="nav-links">
            <a href="#home">Home</a>
            <a href="#produk">Katalog</a>
            <a href="login.php" class="btn-admin">Login</a>
        </div>
    </nav>

    <section class="hero" id="home">
        <div class="hero-text">
            <span>✨ Premium Skincare Collection</span>
            <h1>Discover Your <br><span style="color: var(--primary);">Natural Glow</span></h1>
            <p>Rawat kulitmu dengan produk pilihan terbaik dari Silvia Beauty. Koleksi skincare original untuk kecantikan alami setiap hari.</p>
            <a href="#produk" class="btn-explore">Lihat Skincare</a>
        </div>
        <div class="hero-image">
            <img src="img_produk/hero_banner.jpg" style="width: 100%; max-width: 550px; border-radius: 30px; transform: rotate(2deg); box-shadow: 20px 20px 60px rgba(0,0,0,0.1);" alt="Skincare Glow" onerror="this.src='https://via.placeholder.com/550x400?text=Silvia+Beauty+Skincare'">
        </div>
    </section>

    <section class="products" id="produk">
        <div class="section-title">
            <h2>Our Best Seller</h2>
            <div class="line"></div>
        </div>

        <div class="grid-container">
            <?php while($row = mysqli_fetch_assoc($query)) : ?>
            <div class="product-card">
                <img src="img_produk/<?= $row['gambar']; ?>" onerror="this.src='https://via.placeholder.com/300x300?text=Product'">
                <span class="cat-tag"><?= $row['nama_kategori']; ?></span>
                <h3><?= $row['nama_produk']; ?></h3>
                <p><?= $row['brand']; ?></p>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="price">Rp <?= number_format($row['harga'],0,',','.'); ?></span>
                    <a href="#" style="color: var(--dark);"><i class="fa-solid fa-circle-plus fa-xl"></i></a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <footer>
        <div class="footer-logo">✨ Silvia Beauty</div>
        <p>Your premium destination for authentic skincare and beauty products.</p>
        <div class="social-links" style="margin-top: 20px;">
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
            <a href="#"><i class="fa-brands fa-tiktok"></i></a>
            <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
        <p style="font-size: 12px; margin-top: 40px;">&copy; 2026 Silvia Beauty Management System. All Rights Reserved.</p>
    </footer>

</body>
</html>