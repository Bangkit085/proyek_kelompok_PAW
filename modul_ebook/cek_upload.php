<?php
// Tampilkan semua error biar kelihatan
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h3>🔍 DIAGNOSA SERVER PHP</h3>";
echo "<b>Upload Max Filesize (Server):</b> " . ini_get('upload_max_filesize') . "<br>";
echo "<b>Post Max Size (Server):</b> " . ini_get('post_max_size') . "<br>";
echo "<hr>";

if(isset($_POST['tombol_cek'])){
    echo "<h3>📂 DATA YANG DITERIMA:</h3>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    
    if(isset($_FILES['test_file'])){
        $err = $_FILES['test_file']['error'];
        echo "<b>Kode Error:</b> $err <br>";
        
        if($err == 0) echo "✅ Status: Sukses (File masuk ke folder temp)<br>";
        elseif($err == 1) echo "❌ Error 1: File melebihi upload_max_filesize (GANTI PHP.INI!)<br>";
        elseif($err == 2) echo "❌ Error 2: File melebihi MAX_FILE_SIZE di form HTML<br>";
        elseif($err == 3) echo "❌ Error 3: File hanya terupload sebagian (Koneksi putus?)<br>";
        elseif($err == 4) echo "❌ Error 4: Tidak ada file yang dipilih<br>";
        elseif($err == 6) echo "❌ Error 6: Folder temporary server hilang<br>";
        elseif($err == 7) echo "❌ Error 7: Gagal menulis ke disk (Permission?)<br>";

        echo "<br><b>Nama File Asli:</b> " . $_FILES['test_file']['name'];
    }
}
?>

<form action="" method="POST" enctype="multipart/form-data" style="background:#eee; padding:20px; border:1px dashed #333;">
    <label>Pilih File PDF yang Tadi Gagal:</label><br>
    <input type="file" name="test_file" required><br><br>
    <button type="submit" name="tombol_cek">🔍 CEK FILE SEKARANG</button>
</form>