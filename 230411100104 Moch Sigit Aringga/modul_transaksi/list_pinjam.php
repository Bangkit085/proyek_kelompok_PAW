<?php
include "../koneksi.php";
include "../sidebar.php";
?>

<div class="content">
    <h3 class="mb-4">Daftar Peminjaman</h3>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Mahasiswa</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $query = mysqli_query($conn, "SELECT peminjaman.*, pengguna.nama, buku.judul 
                                          FROM peminjaman
                                          JOIN pengguna ON peminjaman.id_pengguna = pengguna.id_pengguna
                                          JOIN buku ON peminjaman.id_buku = buku.id_buku
                                          ORDER BY id_peminjaman DESC");

            $no = 1;
            while ($row = mysqli_fetch_assoc($query)) :
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['judul'] ?></td>
                    <td><?= $row['tanggal_pinjam'] ?></td>
                    <td><?= $row['tanggal_jatuh_tempo'] ?></td>
                    <td>
                        <?php if ($row['status'] == 'dipinjam') : ?>
                            <span class="badge bg-primary">Dipinjam</span>
                        <?php elseif ($row['status'] == 'dikembalikan') : ?>
                            <span class="badge bg-success">Dikembalikan</span>
                        <?php else : ?>
                            <span class="badge bg-danger">Terlambat</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if ($row['status'] == 'dipinjam') : ?>
                            <a href="kembalikan.php?id=<?= $row['id_peminjaman'] ?>" 
                               class="btn btn-sm btn-warning">Kembalikan</a>
                        <?php endif; ?>

                        <?php if ($row['status'] == 'dipinjam' && $row['tanggal_kembali'] == NULL) : ?>
                            <a href="proses.php?aksi=setujui&id=<?= $row['id_peminjaman'] ?>" 
                               class="btn btn-sm btn-success">Setujui</a>
                        <?php endif; ?>

                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>
