<?php

/**
 * pages/verifikasi_users.php — Halaman Verifikasi Pendaftaran User (Admin Only)
 */
?>
<div class="page" id="page-verifikasi-users">
    <div class="page-header">
        <div>
            <div class="page-title">✅ Verifikasi User</div>
            <div class="page-subtitle">Kelola pendaftaran akun baru yang menunggu persetujuan</div>
        </div>
    </div>

    <!-- Stats Verifikasi -->
    <div class="stats-grid" id="verifikasi-stats"></div>

    <!-- Tabel Verifikasi -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Daftar Pendaftaran</div>
            <span id="verifikasi-count" style="font-size:12px;color:var(--text-muted)"></span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-verifikasi"></tbody>
            </table>
            <div id="empty-verifikasi" class="empty-state" style="display:none">
                <div class="empty-icon">✅</div>
                <div>Tidak ada pendaftaran yang menunggu verifikasi</div>
            </div>
        </div>
    </div>
</div>
