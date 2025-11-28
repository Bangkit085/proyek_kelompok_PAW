<?php
include '../koneksi.php';

$aksi = $_GET['aksi'];

// --- PROSES UPLOAD EBOOK ---
if($aksi == "upload"){
    $id_buku = $_POST['id_buku'];
    
    // Ambil Info File
    $file     = $_FILES['file_ebook'];
    $filename = $file['name'];
    $ukuran   = $file['size'];
    $error    = $file['error'];
    $tmp_name = $file['tmp_name'];

    // 1. CEK ERROR UPLOAD DARI SERVER
    if($error === 1){
        echo "<script>alert('Gagal Upload: File terlalu besar melebihi batas server (upload_max_filesize). Coba file yang lebih kecil (di bawah 2MB) atau atur php.ini'); window.location='upload.php';</script>";
        exit();
    } elseif($error === 4){
        echo "<script>alert('Gagal: Tidak ada file yang diupload.'); window.location='upload.php';</script>";
        exit();
    }

    // 2. AMBIL EKSTENSI FILE (Cara Paling Aman)
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // 3. VALIDASI EKSTENSI (Harus PDF)
    if($ext == 'pdf'){
        
        // 4. VALIDASI UKURAN
        if($ukuran < 10485760){ // Max 10MB
            
            // Rename file agar unik
            $rand = rand();
            $nama_bersih = str_replace(' ', '_', $filename); 
            $nama_file_baru = $rand . '_' . $nama_bersih;
            
            // Upload ke folder
            $tujuan = '../../uploads/file_ebook/' . $nama_file_baru;
            
            if(move_uploaded_file($tmp_name, $tujuan)){
                
                // --- INSERT DATABASE (PDO Prepared Statement) ---
                try {
                    $sql = "INSERT INTO ebook (id_buku, file_path, file_format) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$id_buku, $nama_file_baru, $ext]);
                    
                    header("location:list_ebook.php?pesan=sukses_upload");
                } catch (PDOException $e) {
                    echo "Gagal Database: " . $e->getMessage();
                }
                
            } else {
                echo "Gagal memindahkan file. Pastikan folder <b>/uploads/file_ebook/</b> sudah dibuat!";
            }
            
        } else {
            echo "<script>alert('Ukuran file terlalu besar! Max 10MB.'); window.location='upload.php';</script>";
        }
    } else {
        echo "<script>alert('Format file tidak valid! Sistem membaca ekstensi: .$ext'); window.location='list_ebook.php?pesan=gagal_ext';</script>";
    }

// --- PROSES HAPUS EBOOK ---
} elseif($aksi == "hapus"){
    $id = $_GET['id'];
    $file = $_GET['file'];

    if($file != "" && file_exists('../../uploads/file_ebook/'.$file)){
        unlink('../../uploads/file_ebook/'.$file);
    }

    // --- DELETE DATABASE (PDO Prepared Statement) ---
    $stmt = $conn->prepare("DELETE FROM ebook WHERE id_ebook = ?");
    $stmt->execute([$id]);
    
    header("location:list_ebook.php?pesan=sukses_hapus");
}
?>