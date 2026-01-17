<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM products WHERE id = $id");
echo "<script>alert('Data terhapus!'); window.location='index.php';</script>";
?>