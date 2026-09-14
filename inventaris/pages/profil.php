<?php
/**
 * pages/profil.php
 * Profil Saya — Admin
 */
?>

<div class="page" id="page-profil">

    <div class="page-header">
        <div>
            <div class="page-title">Profil Saya</div>
            <div class="page-subtitle">
                Kelola informasi profil akun Anda
            </div>
        </div>
    </div>

    <div class="card" style="max-width:720px;">
        <div class="card-header">
            <div>
                <div class="card-title">Informasi Profil</div>
                <div class="card-subtitle">
                    Ubah nama, username, foto profil, atau password.
                </div>
            </div>
        </div>

        <div style="padding:24px;">

            <!-- FOTO PROFIL -->
            <div style="text-align:center;margin-bottom:28px;">

                <div id="profil-preview"
                     style="
                        width:110px;
                        height:110px;
                        border-radius:50%;
                        margin:0 auto 14px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        overflow:hidden;
                        background:var(--surface2);
                        border:2px solid var(--border);
                        font-size:32px;
                        font-weight:700;
                        color:var(--accent);
                     ">
                    <span id="profil-initial">AD</span>
                </div>

                <label class="btn btn-secondary btn-sm"
                       style="cursor:pointer;">
                    🖼️ Pilih Foto
                    <input
                        type="file"
                        id="profil-foto"
                        accept="image/jpeg,image/png,image/webp"
                        style="display:none;"
                        onchange="previewProfilFoto(this)"
                    >
                </label>

                <div style="
                    font-size:11px;
                    color:var(--text-muted);
                    margin-top:8px;
                ">
                    JPG, PNG, atau WebP. Maksimal 2 MB.
                </div>

            </div>

            <!-- NAMA -->
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input
                    type="text"
                    id="profil-nama"
                    class="form-input"
                    placeholder="Nama lengkap"
                >
            </div>

            <!-- USERNAME -->
            <div class="form-group">
                <label class="form-label">Username</label>
                <input
                    type="text"
                    id="profil-username"
                    class="form-input"
                    placeholder="Username"
                >
            </div>

            <div style="
                height:1px;
                background:var(--border);
                margin:24px 0;
            "></div>

            <div style="
                font-size:14px;
                font-weight:600;
                margin-bottom:16px;
            ">
                🔐 Ubah Password
            </div>

            <!-- PASSWORD LAMA -->
            <div class="form-group">
                <label class="form-label">
                    Password Lama (untuk verifikasi)
                </label>
                <input
                    type="password"
                    id="profil-password-lama"
                    class="form-input"
                    placeholder="Masukkan password lama"
                >
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <label class="form-label">
                    Password Baru
                </label>
                <input
                    type="password"
                    id="profil-password"
                    class="form-input"
                    placeholder="Kosongkan jika tidak ingin mengubah"
                >
            </div>

            <!-- KONFIRMASI PASSWORD -->
            <div class="form-group">
                <label class="form-label">
                    Konfirmasi Password Baru
                </label>
                <input
                    type="password"
                    id="profil-password-confirm"
                    class="form-input"
                    placeholder="Ulangi password baru"
                >
            </div>

            <div style="
                display:flex;
                justify-content:flex-end;
                gap:8px;
                margin-top:24px;
            ">
                <button
                    class="btn btn-primary"
                    onclick="simpanProfil()"
                >
                    💾 Simpan Perubahan
                </button>
            </div>

        </div>
    </div>

</div>