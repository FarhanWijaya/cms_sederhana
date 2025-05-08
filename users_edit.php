<?php
require_once 'auth.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $role = $_POST['role'] ?? 'editor';
    
    if (!empty($username) && !empty($nama_lengkap)) {
        // Cek apakah username sudah ada (kecuali untuk user yang sedang diedit)
        $check_query = "SELECT id FROM users WHERE username = '" . mysqli_real_escape_string($conn, $username) . "' AND id != $id";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Username sudah digunakan!";
        } else {
            // Escape string untuk mencegah SQL injection
            $username = mysqli_real_escape_string($conn, $username);
            $nama_lengkap = mysqli_real_escape_string($conn, $nama_lengkap);
            $role = mysqli_real_escape_string($conn, $role);
            
            // Update user
            if (!empty($password)) {
                // Update dengan password baru
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "UPDATE users SET 
                         username = '$username', 
                         password = '$hashed_password', 
                         nama_lengkap = '$nama_lengkap', 
                         role = '$role' 
                         WHERE id = $id";
            } else {
                // Update tanpa mengubah password
                $query = "UPDATE users SET 
                         username = '$username', 
                         nama_lengkap = '$nama_lengkap', 
                         role = '$role' 
                         WHERE id = $id";
            }
            
            if (mysqli_query($conn, $query)) {
                header('Location: users.php');
                exit();
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    } else {
        $error = "Username dan nama lengkap harus diisi!";
    }
}

// Redirect jika bukan POST request
header('Location: users.php');
exit(); 