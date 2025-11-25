<!doctype html>
<html lang="id">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perpustakaan Mahasiswa</title>
  <script src="/_sdk/data_sdk.js"></script>
  <script src="/_sdk/element_sdk.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
  <style>
    body {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    
    .book-card {
      transition: all 0.3s ease;
    }
    
    .book-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }
    
    .profile-dropdown {
      display: none;
      position: absolute;
      right: 0;
      top: 100%;
      margin-top: 0.5rem;
      background: white;
      border-radius: 0.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      min-width: 200px;
      z-index: 50;
    }
    
    .profile-dropdown.active {
      display: block;
    }
    
    .nav-item {
      transition: all 0.2s ease;
    }
    
    .nav-item:hover {
      background: rgba(255, 255, 255, 0.1);
    }
    
    .nav-item.active {
      background: rgba(255, 255, 255, 0.15);
      border-left: 4px solid white;
    }
  </style>
  <style>@view-transition { navigation: auto; }</style>
 </head>
 <body><!-- Login Page -->
  <div id="loginPage" class="min-h-full flex items-center justify-center p-8" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
   <div style="background: white; border-radius: 1rem; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); padding: 3rem; width: 100%; max-width: 420px;">
    <div style="text-align: center; margin-bottom: 2rem;">
     <div style="width: 64px; height: 64px; background: #667eea; border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
      📚
     </div>
     <h1 id="loginLibraryTitle" style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem; color: #1f2937;">Perpustakaan Universitas</h1>
     <p style="color: #6b7280;">Masuk ke akun Anda</p>
    </div>
    <form id="loginForm">
     <div style="margin-bottom: 1.5rem;"><label for="loginEmail" style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Email</label> <input type="email" id="loginEmail" required style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; transition: all 0.2s;" placeholder="email@mahasiswa.ac.id">
     </div>
     <div style="margin-bottom: 1.5rem;"><label for="loginPassword" style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Password</label> <input type="password" id="loginPassword" required style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; transition: all 0.2s;" placeholder="••••••••">
     </div>
     <div id="loginError" style="display: none; padding: 0.75rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; margin-bottom: 1rem; font-size: 0.875rem;"></div><button type="submit" id="loginButton" style="width: 100%; padding: 0.875rem; background: #667eea; color: white; border: none; border-radius: 0.5rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.2s;"> Masuk </button>
    </form>
    <div style="text-align: center; margin-top: 1.5rem; color: #6b7280;">
     Belum punya akun? <a href="#" id="showRegister" style="color: #667eea; font-weight: 600; text-decoration: none;">Daftar di sini</a>
    </div>
   </div>
  </div><!-- Register Page -->
  <div id="registerPage" style="display: none;" class="min-h-full flex items-center justify-center p-8">
   <div style="background: white; border-radius: 1rem; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); padding: 3rem; width: 100%; max-width: 420px;">
    <div style="text-align: center; margin-bottom: 2rem;">
     <div style="width: 64px; height: 64px; background: #667eea; border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
      📚
     </div>
     <h1 id="registerLibraryTitle" style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem; color: #1f2937;">Perpustakaan Universitas</h1>
     <p style="color: #6b7280;">Buat akun baru</p>
    </div>
    <form id="registerForm">
     <div style="margin-bottom: 1.25rem;"><label for="registerNama" style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Nama Lengkap</label> <input type="text" id="registerNama" required style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem;" placeholder="Nama Anda">
     </div>
     <div style="margin-bottom: 1.25rem;"><label for="registerNim" style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">NIM</label> <input type="text" id="registerNim" required style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem;" placeholder="123456789">
     </div>
     <div style="margin-bottom: 1.25rem;"><label for="registerJurusan" style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Jurusan</label> <input type="text" id="registerJurusan" required style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem;" placeholder="Teknik Informatika">
     </div>
     <div style="margin-bottom: 1.25rem;"><label for="registerEmail" style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Email</label> <input type="email" id="registerEmail" required style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem;" placeholder="email@mahasiswa.ac.id">
     </div>
     <div style="margin-bottom: 1.5rem;"><label for="registerPassword" style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Password</label> <input type="password" id="registerPassword" required style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem;" placeholder="••••••••">
     </div>
     <div id="registerError" style="display: none; padding: 0.75rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; margin-bottom: 1rem; font-size: 0.875rem;"></div><button type="submit" id="registerButton" style="width: 100%; padding: 0.875rem; background: #667eea; color: white; border: none; border-radius: 0.5rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.2s;"> Daftar </button>
    </form>
    <div style="text-align: center; margin-top: 1.5rem; color: #6b7280;">
     Sudah punya akun? <a href="#" id="showLogin" style="color: #667eea; font-weight: 600; text-decoration: none;">Masuk di sini</a>
    </div>
   </div>
  </div><!-- Main Dashboard -->
  <div id="dashboardPage" style="display: none; width: 100%; height: 100%; background: #f3f4f6;"><!-- Sidebar -->
   <div id="sidebar" style="position: fixed; left: 0; top: 0; width: 260px; height: 100%; background: #667eea; padding: 1.5rem 0; overflow-y: auto;">
    <div style="padding: 0 1.5rem; margin-bottom: 2rem;">
     <div style="display: flex; align-items: center; gap: 0.75rem;">
      <div style="width: 40px; height: 40px; background: white; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
       📚
      </div>
      <div>
       <h2 id="sidebarLibraryTitle" style="color: white; font-weight: 700; font-size: 1.125rem;">Perpustakaan</h2>
      </div>
     </div>
    </div>
    <nav style="padding: 0 0.75rem;"><a href="#" class="nav-item active" data-page="dashboard" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1rem; color: white; text-decoration: none; border-radius: 0.5rem; margin-bottom: 0.5rem; font-weight: 500;"> <span style="font-size: 1.25rem;">🏠</span> <span>Dashboard</span> </a> <a href="#" class="nav-item" data-page="katalog" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1rem; color: white; text-decoration: none; border-radius: 0.5rem; margin-bottom: 0.5rem; font-weight: 500;"> <span style="font-size: 1.25rem;">📖</span> <span>Katalog Buku</span> </a> <a href="#" class="nav-item" data-page="peminjaman" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1rem; color: white; text-decoration: none; border-radius: 0.5rem; margin-bottom: 0.5rem; font-weight: 500;"> <span style="font-size: 1.25rem;">📋</span> <span>Peminjaman Saya</span> </a> <a href="#" class="nav-item" data-page="riwayat" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1rem; color: white; text-decoration: none; border-radius: 0.5rem; margin-bottom: 0.5rem; font-weight: 500;"> <span style="font-size: 1.25rem;">🕐</span> <span>Riwayat</span> </a>
    </nav>
   </div><!-- Main Content -->
   <div style="margin-left: 260px; height: 100%;"><!-- Top Header -->
    <header style="background: white; border-bottom: 1px solid #e5e7eb; padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between;">
     <div style="flex: 1; max-width: 500px;">
      <div style="position: relative;"><input type="text" id="searchInput" placeholder="Cari buku, penulis, atau kategori..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 3rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.875rem;"> <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); font-size: 1.25rem;">🔍</span>
      </div>
     </div>
     <div style="position: relative;"><button id="profileButton" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 1rem; background: #f3f4f6; border: none; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s;">
       <div style="width: 36px; height: 36px; background: #667eea; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;"><span id="userInitial">U</span>
       </div><span id="userNameHeader" style="font-weight: 500; color: #374151;">User</span> <span style="font-size: 0.75rem;">▼</span> </button>
      <div id="profileDropdown" class="profile-dropdown">
       <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
        <div style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;" id="dropdownName">
         User
        </div>
        <div style="font-size: 0.875rem; color: #6b7280;" id="dropdownEmail">
         email@example.com
        </div>
        <div style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;"><span style="font-weight: 500;">NIM:</span> <span id="dropdownNim">-</span>
        </div>
        <div style="font-size: 0.875rem; color: #6b7280;"><span style="font-weight: 500;">Jurusan:</span> <span id="dropdownJurusan">-</span>
        </div>
       </div><button id="logoutButton" style="width: 100%; padding: 0.75rem 1rem; background: none; border: none; color: #dc2626; font-weight: 500; cursor: pointer; text-align: left; transition: all 0.2s;"> 🚪 Logout </button>
      </div>
     </div>
    </header><!-- Content Area -->
    <main style="padding: 2rem;"><!-- Dashboard Content -->
     <div id="dashboardContent">
      <h1 id="welcomeMessage" style="font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">Selamat datang di perpustakaan digital</h1>
      <p style="color: #6b7280; margin-bottom: 2rem;">Jelajahi koleksi buku dan kelola peminjaman Anda</p>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
       <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
        <div style="font-size: 2rem; margin-bottom: 0.5rem;">
         📚
        </div>
        <div style="font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">
         12,450
        </div>
        <div style="color: #6b7280; font-size: 0.875rem;">
         Total Buku
        </div>
       </div>
       <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
        <div style="font-size: 2rem; margin-bottom: 0.5rem;">
         📖
        </div>
        <div style="font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">
         2
        </div>
        <div style="color: #6b7280; font-size: 0.875rem;">
         Sedang Dipinjam
        </div>
       </div>
       <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
        <div style="font-size: 2rem; margin-bottom: 0.5rem;">
         ⭐
        </div>
        <div style="font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">
         45
        </div>
        <div style="color: #6b7280; font-size: 0.875rem;">
         Total Dibaca
        </div>
       </div>
      </div>
      <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
       <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">Buku Terpopuler</h2>
       <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem;">
        <div style="text-align: center;">
         <div style="width: 100%; height: 220px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.5rem; margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 3rem;">
          📘
         </div>
         <div style="font-weight: 600; font-size: 0.875rem; color: #1f2937; margin-bottom: 0.25rem;">
          Algoritma Pemrograman
         </div>
         <div style="font-size: 0.75rem; color: #6b7280;">
          Rinaldi Munir
         </div>
        </div>
        <div style="text-align: center;">
         <div style="width: 100%; height: 220px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 0.5rem; margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 3rem;">
          📗
         </div>
         <div style="font-weight: 600; font-size: 0.875rem; color: #1f2937; margin-bottom: 0.25rem;">
          Basis Data
         </div>
         <div style="font-size: 0.75rem; color: #6b7280;">
          Abdul Kadir
         </div>
        </div>
        <div style="text-align: center;">
         <div style="width: 100%; height: 220px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 0.5rem; margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 3rem;">
          📙
         </div>
         <div style="font-weight: 600; font-size: 0.875rem; color: #1f2937; margin-bottom: 0.25rem;">
          Jaringan Komputer
         </div>
         <div style="font-size: 0.75rem; color: #6b7280;">
          Andrew S. Tanenbaum
         </div>
        </div>
        <div style="text-align: center;">
         <div style="width: 100%; height: 220px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 0.5rem; margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 3rem;">
          📕
         </div>
         <div style="font-weight: 600; font-size: 0.875rem; color: #1f2937; margin-bottom: 0.25rem;">
          Struktur Data
         </div>
         <div style="font-size: 0.75rem; color: #6b7280;">
          Thomas H. Cormen
         </div>
        </div>
       </div>
      </div>
     </div><!-- Katalog Content -->
     <div id="katalogContent" style="display: none;">
      <h1 style="font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">Katalog Buku</h1>
      <p style="color: #6b7280; margin-bottom: 2rem;">Jelajahi koleksi lengkap perpustakaan kami</p>
      <div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;"><button class="filter-btn active" data-category="semua" style="padding: 0.5rem 1.25rem; background: #667eea; color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer;"> Semua </button> <button class="filter-btn" data-category="teknologi" style="padding: 0.5rem 1.25rem; background: #e5e7eb; color: #374151; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer;"> Teknologi </button> <button class="filter-btn" data-category="sains" style="padding: 0.5rem 1.25rem; background: #e5e7eb; color: #374151; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer;"> Sains </button> <button class="filter-btn" data-category="bisnis" style="padding: 0.5rem 1.25rem; background: #e5e7eb; color: #374151; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer;"> Bisnis </button> <button class="filter-btn" data-category="sastra" style="padding: 0.5rem 1.25rem; background: #e5e7eb; color: #374151; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer;"> Sastra </button>
      </div>
      <div id="bookGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;"></div>
     </div><!-- Peminjaman Content -->
     <div id="peminjamanContent" style="display: none;">
      <h1 style="font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">Peminjaman Saya</h1>
      <p style="color: #6b7280; margin-bottom: 2rem;">Kelola buku yang sedang Anda pinjam</p>
      <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
       <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 80px; height: 110px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; flex-shrink: 0;">
         📘
        </div>
        <div style="flex: 1;">
         <h3 style="font-weight: 600; font-size: 1.125rem; color: #1f2937; margin-bottom: 0.25rem;">Algoritma Pemrograman</h3>
         <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Rinaldi Munir</p>
         <div style="display: flex; gap: 1rem; font-size: 0.875rem;"><span style="color: #6b7280;">Dipinjam: 15 Des 2024</span> <span style="color: #dc2626; font-weight: 500;">Jatuh tempo: 29 Des 2024</span>
         </div>
        </div><button style="padding: 0.5rem 1.25rem; background: #667eea; color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer;"> Kembalikan </button>
       </div>
       <div style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 80px; height: 110px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; flex-shrink: 0;">
         📗
        </div>
        <div style="flex: 1;">
         <h3 style="font-weight: 600; font-size: 1.125rem; color: #1f2937; margin-bottom: 0.25rem;">Basis Data</h3>
         <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Abdul Kadir</p>
         <div style="display: flex; gap: 1rem; font-size: 0.875rem;"><span style="color: #6b7280;">Dipinjam: 18 Des 2024</span> <span style="color: #059669; font-weight: 500;">Jatuh tempo: 1 Jan 2025</span>
         </div>
        </div><button style="padding: 0.5rem 1.25rem; background: #667eea; color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer;"> Kembalikan </button>
       </div>
      </div>
     </div><!-- Riwayat Content -->
     <div id="riwayatContent" style="display: none;">
      <h1 style="font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">Riwayat Peminjaman</h1>
      <p style="color: #6b7280; margin-bottom: 2rem;">Lihat semua buku yang pernah Anda pinjam</p>
      <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
       <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
        <div style="display: flex; align-items: center; gap: 1rem;">
         <div style="width: 60px; height: 80px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0;">
          📙
         </div>
         <div style="flex: 1;">
          <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">Jaringan Komputer</h3>
          <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Andrew S. Tanenbaum</p>
          <div style="font-size: 0.875rem; color: #6b7280;">
           Dipinjam: 1 Des 2024 - Dikembalikan: 14 Des 2024
          </div>
         </div><span style="padding: 0.25rem 0.75rem; background: #d1fae5; color: #065f46; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;"> Selesai </span>
        </div>
       </div>
       <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
        <div style="display: flex; align-items: center; gap: 1rem;">
         <div style="width: 60px; height: 80px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0;">
          📕
         </div>
         <div style="flex: 1;">
          <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">Struktur Data</h3>
          <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Thomas H. Cormen</p>
          <div style="font-size: 0.875rem; color: #6b7280;">
           Dipinjam: 20 Nov 2024 - Dikembalikan: 4 Des 2024
          </div>
         </div><span style="padding: 0.25rem 0.75rem; background: #d1fae5; color: #065f46; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;"> Selesai </span>
        </div>
       </div>
       <div style="padding: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
         <div style="width: 60px; height: 80px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0;">
          📔
         </div>
         <div style="flex: 1;">
          <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">Sistem Operasi</h3>
          <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">William Stallings</p>
          <div style="font-size: 0.875rem; color: #6b7280;">
           Dipinjam: 5 Nov 2024 - Dikembalikan: 19 Nov 2024
          </div>
         </div><span style="padding: 0.25rem 0.75rem; background: #d1fae5; color: #065f46; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;"> Selesai </span>
        </div>
       </div>
      </div>
     </div>
    </main>
   </div>
  </div>
  <script>
    let currentUser = null;
    let allUsers = [];
    let currentPage = 'dashboard';
    let currentFilter = 'semua';
    
    const defaultConfig = {
      library_title: "Perpustakaan Universitas",
      welcome_message: "Selamat datang di perpustakaan digital",
      background_color: "#f3f4f6",
      sidebar_color: "#667eea",
      primary_action_color: "#667eea",
      text_color: "#1f2937",
      secondary_surface_color: "#ffffff",
      font_family: "Inter",
      font_size: 16
    };
    
    const books = [
      { id: 1, title: "Algoritma Pemrograman", author: "Rinaldi Munir", category: "teknologi", icon: "📘", gradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)" },
      { id: 2, title: "Basis Data", author: "Abdul Kadir", category: "teknologi", icon: "📗", gradient: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)" },
      { id: 3, title: "Jaringan Komputer", author: "Andrew S. Tanenbaum", category: "teknologi", icon: "📙", gradient: "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)" },
      { id: 4, title: "Struktur Data", author: "Thomas H. Cormen", category: "teknologi", icon: "📕", gradient: "linear-gradient(135deg, #fa709a 0%, #fee140 100%)" },
      { id: 5, title: "Fisika Dasar", author: "Halliday & Resnick", category: "sains", icon: "📘", gradient: "linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)" },
      { id: 6, title: "Kimia Organik", author: "John McMurry", category: "sains", icon: "📗", gradient: "linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)" },
      { id: 7, title: "Manajemen Strategis", author: "Fred R. David", category: "bisnis", icon: "📙", gradient: "linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)" },
      { id: 8, title: "Akuntansi Keuangan", author: "Kieso", category: "bisnis", icon: "📕", gradient: "linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%)" },
      { id: 9, title: "Laskar Pelangi", author: "Andrea Hirata", category: "sastra", icon: "📘", gradient: "linear-gradient(135deg, #fdcbf1 0%, #e6dee9 100%)" },
      { id: 10, title: "Bumi Manusia", author: "Pramoedya Ananta Toer", category: "sastra", icon: "📗", gradient: "linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)" }
    ];
    
    const dataHandler = {
      onDataChanged(data) {
        allUsers = data;
      }
    };
    
    async function initializeSdk() {
      if (window.dataSdk) {
        const result = await window.dataSdk.init(dataHandler);
        if (!result.isOk) {
          console.error("Failed to initialize data SDK");
        }
      }
      
      if (window.elementSdk) {
        window.elementSdk.init({
          defaultConfig,
          onConfigChange: async (config) => {
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontStack = 'Inter, sans-serif';
            const baseSize = config.font_size || defaultConfig.font_size;
            const fullFontFamily = `${customFont}, ${baseFontStack}`;
            
            document.body.style.fontFamily = fullFontFamily;
            
            document.getElementById('loginLibraryTitle').textContent = config.library_title || defaultConfig.library_title;
            document.getElementById('registerLibraryTitle').textContent = config.library_title || defaultConfig.library_title;
            document.getElementById('sidebarLibraryTitle').textContent = config.library_title || defaultConfig.library_title;
            document.getElementById('welcomeMessage').textContent = config.welcome_message || defaultConfig.welcome_message;
            
            document.getElementById('loginLibraryTitle').style.fontSize = `${baseSize * 1.875}px`;
            document.getElementById('registerLibraryTitle').style.fontSize = `${baseSize * 1.875}px`;
            document.getElementById('sidebarLibraryTitle').style.fontSize = `${baseSize * 1.125}px`;
            document.getElementById('welcomeMessage').style.fontSize = `${baseSize * 2}px`;
            
            const backgroundColor = config.background_color || defaultConfig.background_color;
            const sidebarColor = config.sidebar_color || defaultConfig.sidebar_color;
            const primaryActionColor = config.primary_action_color || defaultConfig.primary_action_color;
            const textColor = config.text_color || defaultConfig.text_color;
            const secondarySurfaceColor = config.secondary_surface_color || defaultConfig.secondary_surface_color;
            
            document.getElementById('dashboardPage').style.background = backgroundColor;
            document.getElementById('sidebar').style.background = sidebarColor;
            
            const loginButton = document.getElementById('loginButton');
            loginButton.style.background = primaryActionColor;
            
            const registerButton = document.getElementById('registerButton');
            registerButton.style.background = primaryActionColor;
            
            document.querySelectorAll('h1, h2, h3, p, span, label, button').forEach(el => {
              if (!el.style.color || el.style.color === defaultConfig.text_color) {
                el.style.color = textColor;
              }
            });
            
            document.querySelectorAll('.book-card, header').forEach(el => {
              el.style.background = secondarySurfaceColor;
            });
          },
          mapToCapabilities: (config) => ({
            recolorables: [
              {
                get: () => config.background_color || defaultConfig.background_color,
                set: (value) => {
                  window.elementSdk.config.background_color = value;
                  window.elementSdk.setConfig({ background_color: value });
                }
              },
              {
                get: () => config.secondary_surface_color || defaultConfig.secondary_surface_color,
                set: (value) => {
                  window.elementSdk.config.secondary_surface_color = value;
                  window.elementSdk.setConfig({ secondary_surface_color: value });
                }
              },
              {
                get: () => config.text_color || defaultConfig.text_color,
                set: (value) => {
                  window.elementSdk.config.text_color = value;
                  window.elementSdk.setConfig({ text_color: value });
                }
              },
              {
                get: () => config.primary_action_color || defaultConfig.primary_action_color,
                set: (value) => {
                  window.elementSdk.config.primary_action_color = value;
                  window.elementSdk.setConfig({ primary_action_color: value });
                }
              },
              {
                get: () => config.sidebar_color || defaultConfig.sidebar_color,
                set: (value) => {
                  window.elementSdk.config.sidebar_color = value;
                  window.elementSdk.setConfig({ sidebar_color: value });
                }
              }
            ],
            borderables: [],
            fontEditable: {
              get: () => config.font_family || defaultConfig.font_family,
              set: (value) => {
                window.elementSdk.config.font_family = value;
                window.elementSdk.setConfig({ font_family: value });
              }
            },
            fontSizeable: {
              get: () => config.font_size || defaultConfig.font_size,
              set: (value) => {
                window.elementSdk.config.font_size = value;
                window.elementSdk.setConfig({ font_size: value });
              }
            }
          }),
          mapToEditPanelValues: (config) => new Map([
            ["library_title", config.library_title || defaultConfig.library_title],
            ["welcome_message", config.welcome_message || defaultConfig.welcome_message]
          ])
        });
      }
    }
    
    function renderBooks(filter = 'semua', searchQuery = '') {
      const bookGrid = document.getElementById('bookGrid');
      bookGrid.innerHTML = '';
      
      const filteredBooks = books.filter(book => {
        const matchesFilter = filter === 'semua' || book.category === filter;
        const matchesSearch = searchQuery === '' || 
          book.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
          book.author.toLowerCase().includes(searchQuery.toLowerCase());
        return matchesFilter && matchesSearch;
      });
      
      filteredBooks.forEach(book => {
        const card = document.createElement('div');
        card.className = 'book-card';
        card.style.cssText = 'background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden; cursor: pointer;';
        
        card.innerHTML = `
          <div style="width: 100%; height: 280px; background: ${book.gradient}; display: flex; align-items: center; justify-content: center; font-size: 4rem;">
            ${book.icon}
          </div>
          <div style="padding: 1rem;">
            <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem; font-size: 0.9375rem;">${book.title}</h3>
            <p style="color: #6b7280; font-size: 0.8125rem; margin-bottom: 0.75rem;">${book.author}</p>
            <button style="width: 100%; padding: 0.5rem; background: #667eea; color: white; border: none; border-radius: 0.5rem; font-weight: 500; font-size: 0.875rem; cursor: pointer;">
              Pinjam Buku
            </button>
          </div>
        `;
        
        bookGrid.appendChild(card);
      });
    }
    
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const email = document.getElementById('loginEmail').value;
      const password = document.getElementById('loginPassword').value;
      const errorDiv = document.getElementById('loginError');
      const loginButton = document.getElementById('loginButton');
      
      loginButton.disabled = true;
      loginButton.textContent = 'Memproses...';
      
      await new Promise(resolve => setTimeout(resolve, 500));
      
      const user = allUsers.find(u => u.email === email && u.password === password);
      
      if (user) {
        currentUser = user;
        errorDiv.style.display = 'none';
        
        document.getElementById('userInitial').textContent = user.nama.charAt(0).toUpperCase();
        document.getElementById('userNameHeader').textContent = user.nama;
        document.getElementById('dropdownName').textContent = user.nama;
        document.getElementById('dropdownEmail').textContent = user.email;
        document.getElementById('dropdownNim').textContent = user.nim;
        document.getElementById('dropdownJurusan').textContent = user.jurusan;
        
        document.getElementById('loginPage').style.display = 'none';
        document.getElementById('dashboardPage').style.display = 'block';
        renderBooks();
      } else {
        errorDiv.textContent = 'Email atau password salah';
        errorDiv.style.display = 'block';
      }
      
      loginButton.disabled = false;
      loginButton.textContent = 'Masuk';
    });
    
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      
      if (allUsers.length >= 999) {
        const errorDiv = document.getElementById('registerError');
        errorDiv.textContent = 'Maksimal 999 pengguna sudah tercapai. Tidak dapat mendaftar lagi.';
        errorDiv.style.display = 'block';
        return;
      }
      
      const nama = document.getElementById('registerNama').value;
      const nim = document.getElementById('registerNim').value;
      const jurusan = document.getElementById('registerJurusan').value;
      const email = document.getElementById('registerEmail').value;
      const password = document.getElementById('registerPassword').value;
      const errorDiv = document.getElementById('registerError');
      const registerButton = document.getElementById('registerButton');
      
      registerButton.disabled = true;
      registerButton.textContent = 'Memproses...';
      
      const existingUser = allUsers.find(u => u.email === email);
      if (existingUser) {
        errorDiv.textContent = 'Email sudah terdaftar';
        errorDiv.style.display = 'block';
        registerButton.disabled = false;
        registerButton.textContent = 'Daftar';
        return;
      }
      
      const result = await window.dataSdk.create({
        email,
        password,
        nama,
        nim,
        jurusan,
        createdAt: new Date().toISOString()
      });
      
      if (result.isOk) {
        errorDiv.style.display = 'none';
        document.getElementById('registerPage').style.display = 'none';
        document.getElementById('loginPage').style.display = 'flex';
        document.getElementById('registerForm').reset();
      } else {
        errorDiv.textContent = 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.';
        errorDiv.style.display = 'block';
      }
      
      registerButton.disabled = false;
      registerButton.textContent = 'Daftar';
    });
    
    document.getElementById('showRegister').addEventListener('click', (e) => {
      e.preventDefault();
      document.getElementById('loginPage').style.display = 'none';
      document.getElementById('registerPage').style.display = 'flex';
    });
    
    document.getElementById('showLogin').addEventListener('click', (e) => {
      e.preventDefault();
      document.getElementById('registerPage').style.display = 'none';
      document.getElementById('loginPage').style.display = 'flex';
    });
    
    document.getElementById('profileButton').addEventListener('click', () => {
      const dropdown = document.getElementById('profileDropdown');
      dropdown.classList.toggle('active');
    });
    
    document.addEventListener('click', (e) => {
      const profileButton = document.getElementById('profileButton');
      const dropdown = document.getElementById('profileDropdown');
      if (!profileButton.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('active');
      }
    });
    
    document.getElementById('logoutButton').addEventListener('click', () => {
      currentUser = null;
      document.getElementById('dashboardPage').style.display = 'none';
      document.getElementById('loginPage').style.display = 'flex';
      document.getElementById('profileDropdown').classList.remove('active');
    });
    
    document.querySelectorAll('.nav-item').forEach(item => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        
        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        item.classList.add('active');
        
        const page = item.dataset.page;
        currentPage = page;
        
        document.getElementById('dashboardContent').style.display = 'none';
        document.getElementById('katalogContent').style.display = 'none';
        document.getElementById('peminjamanContent').style.display = 'none';
        document.getElementById('riwayatContent').style.display = 'none';
        
        if (page === 'dashboard') {
          document.getElementById('dashboardContent').style.display = 'block';
        } else if (page === 'katalog') {
          document.getElementById('katalogContent').style.display = 'block';
          renderBooks(currentFilter);
        } else if (page === 'peminjaman') {
          document.getElementById('peminjamanContent').style.display = 'block';
        } else if (page === 'riwayat') {
          document.getElementById('riwayatContent').style.display = 'block';
        }
      });
    });
    
    document.querySelectorAll('.filter-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.filter-btn').forEach(b => {
          b.style.background = '#e5e7eb';
          b.style.color = '#374151';
          b.classList.remove('active');
        });
        btn.style.background = '#667eea';
        btn.style.color = 'white';
        btn.classList.add('active');
        
        currentFilter = btn.dataset.category;
        renderBooks(currentFilter, document.getElementById('searchInput').value);
      });
    });
    
    document.getElementById('searchInput').addEventListener('input', (e) => {
      if (currentPage === 'katalog') {
        renderBooks(currentFilter, e.target.value);
      }
    });
    
    initializeSdk();
  </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9a3a5cc906594ad9',t:'MTc2NDAwMjU3Ny4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script>
 </body>
</html>