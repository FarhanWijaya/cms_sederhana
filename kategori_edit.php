<?php
require_once 'auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nama_kategori = $_POST['nama_kategori'] ?? '';
    
    if (!empty($nama_kategori)) {
        // Escape string untuk mencegah SQL injection
        $nama_kategori = mysqli_real_escape_string($conn, $nama_kategori);
        
        // Update kategori
        $query = "UPDATE kategori SET nama_kategori = '$nama_kategori' WHERE id = $id";
        
        if (mysqli_query($conn, $query)) {
            header('Location: kategori.php');
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    } else {
        $error = "Nama kategori harus diisi!";
    }
}

// Redirect jika bukan POST request
header('Location: kategori.php');
exit(); 