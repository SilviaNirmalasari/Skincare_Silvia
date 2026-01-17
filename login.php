<?php
session_start();
include 'koneksi.php';

// Jika sudah login, langsung lempar ke dashboard
if (isset($_SESSION['admin'])) { header("Location: index.php"); exit; }

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['admin'] = $row['username'];
        $_SESSION['admin_id'] = $row['id']; // Menyimpan ID untuk keperluan profil
        header("Location: index.php");
        exit;
    } else {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Silvia Beauty</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #d81b60;
            --accent: #ad1457;
            --bg-gradient: linear-gradient(135deg, #fce4ec 0%, #f8bbd0 100%);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        /* Dekorasi Latar Belakang */
        .glass-circle {
            position: absolute;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(5px);
            border-radius: 50%;
            z-index: -1;
            animation: float 6s ease-in-out infinite;
        }
        .c1 { width: 300px; height: 300px; top: -100px; right: -50px; }
        .c2 { width: 200px; height: 200px; bottom: -50px; left: -50px; animation-delay: 2s; }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.85);
            padding: 50px 45px;
            border-radius: 35px;
            box-shadow: 0 25px 50px -12px rgba(216, 27, 96, 0.15);
            width: 100%;
            max-width: 400px;
            text-align: center;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            transform: scale(1);
            transition: 0.3s;
        }

        .brand-logo {
            width: 70px;
            height: 70px;
            background: var(--primary);
            color: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(216, 27, 96, 0.3);
        }

        h2 { color: var(--text-dark); margin-bottom: 8px; font-weight: 700; font-size: 24px; }
        p { color: #718096; font-size: 14px; margin-bottom: 35px; }

        .input-group { position: relative; margin-bottom: 25px; }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 18px;
        }

        input {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border-radius: 18px;
            border: 1.5px solid transparent;
            background-color: white;
            box-sizing: border-box;
            font-family: 'Poppins';
            transition: 0.4s;
            outline: none;
            font-size: 14px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 10px 15px -3px rgba(216, 27, 96, 0.1);
        }

        button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            border: none;
            border-radius: 18px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(216, 27, 96, 0.25);
            transition: 0.3s;
            margin-top: 10px;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(216, 27, 96, 0.35);
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 25px;
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            transition: 0.3s;
        }

        .btn-home:hover { color: var(--primary); }

        .error-msg {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 25px;
            border: 1px solid #fecaca;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            animation: shake 0.4s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .footer-text { margin-top: 30px; font-size: 11px; color: #a0aec0; }
    </style>
</head>
<body>
    <div class="glass-circle c1"></div>
    <div class="glass-circle c2"></div>

    <div class="login-card">
        <div class="brand-logo">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>
        <h2>✨Welcome</h2>
        <p>Silvia Beauty Management System</p>

        <?php if (isset($error)) : ?>
            <div class="error-msg">
                <i class="fa-solid fa-circle-exclamation"></i>
                Username atau password salah!
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="username" placeholder="Username" required autocomplete="off">
            </div>
            
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" name="login">
                SIGN IN &nbsp;<i class="fa-solid fa-right-to-bracket"></i>
            </button>
        </form>

        <a href="home.php" class="btn-home">
            <i class="fa-solid fa-house-chimney"></i> Back to Homepage
        </a>

        <div class="footer-text">
            &copy; 2026 Silvia Beauty Hub • Secure Encryption
        </div>
    </div>
</body>
</html>