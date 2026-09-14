<?php

/**
 * pages/edit_profil.php — Halaman Edit Profil User
 */
$currentUser = getCurrentUser();
?>
<!-- HALAMAN EDIT PROFIL -->
<div class="page" id="page-edit-profil">

    <!-- Header Halaman -->
    <div class="page-header">
        <div>
            <div class="page-title">Edit Profil</div>
            <div class="page-subtitle">Kelola informasi akun Anda</div>
        </div>
    </div>

    <!-- Container Profil -->
    <div style="max-width: 800px; margin: 0 auto;">
        
        <!-- Alert untuk pesan sukses/error -->
        <div id="alert-profil" style="display: none; margin-bottom: 1.5rem; padding: 1rem; border-radius: 8px; font-size: 0.9rem;">
            <span id="alert-profil-text"></span>
        </div>

        <!-- Card Foto Profil -->
        <div class="card" style="margin-bottom: 1.5rem; text-align: center; padding: 2rem;">
            <div style="margin-bottom: 1rem;">
                <div class="foto-profil-preview" style="width: 150px; height: 150px; margin: 0 auto; border-radius: 50%; overflow: hidden; border: 3px solid var(--border); background: var(--surface2); display: flex; align-items: center; justify-content: center; position: relative;">
                    <img id="preview-foto-profil" src="" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                    <div id="preview-initials" style="font-size: 3rem; font-weight: 700; color: var(--text-muted);">
                        <?= strtoupper(substr($currentUser['nama'], 0, 2)) ?>
                    </div>
                </div>
            </div>
            <div style="margin-bottom: 0.5rem;">
                <label for="input-foto-profil" class="btn btn-sm" style="cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
                    📷 Pilih Foto
                </label>
                <input type="file" id="input-foto-profil" accept="image/jpeg,image/jpg,image/png" style="display: none;">
            </div>
            <div style="font-size: 0.8rem; color: var(--text-muted);">
                Format: JPG, PNG | Maksimal: 2MB
            </div>
            <button id="btn-hapus-foto" class="btn btn-sm" style="margin-top: 0.5rem; display: none; background: rgba(248,113,113,0.1); color: var(--danger);" onclick="hapusFotoProfil()">
                🗑️ Hapus Foto
            </button>
        </div>

        <!-- Card Form Edit Data -->
        <div class="card">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.1rem; font-weight: 600;">Informasi Akun</h3>
            
            <form id="form-edit-profil" onsubmit="return false;">
                
                <!-- Nama -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text);">
                        Nama Lengkap
                    </label>
                    <input 
                        type="text" 
                        id="input-nama" 
                        name="nama" 
                        class="form-control" 
                        value="<?= htmlspecialchars($currentUser['nama']) ?>"
                        required
                        style="width: 100%; padding: 0.75rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 6px; color: var(--text); font-size: 0.9rem;">
                </div>

                <!-- Username -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text);">
                        Username
                    </label>
                    <input 
                        type="text" 
                        id="input-username" 
                        name="username" 
                        class="form-control" 
                        value="<?= htmlspecialchars($currentUser['username']) ?>"
                        required
                        style="width: 100%; padding: 0.75rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 6px; color: var(--text); font-size: 0.9rem;">
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                        Username harus unik dan akan digunakan untuk login
                    </div>
                </div>

                <!-- Divider -->
                <div style="height: 1px; background: var(--border); margin: 2rem 0;"></div>

                <h4 style="margin-bottom: 1rem; font-size: 0.95rem; font-weight: 600; color: var(--text-muted);">
                    Ubah Password (Opsional)
                </h4>
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1rem;">
                    Kosongkan jika tidak ingin mengubah password
                </div>

                <!-- Password Lama -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text);">
                        Password Lama
                    </label>
                    <input 
                        type="password" 
                        id="input-password-lama" 
                        name="password_lama" 
                        class="form-control" 
                        placeholder="Masukkan password lama"
                        style="width: 100%; padding: 0.75rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 6px; color: var(--text); font-size: 0.9rem;">
                </div>

                <!-- Password Baru -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text);">
                        Password Baru
                    </label>
                    <input 
                        type="password" 
                        id="input-password-baru" 
                        name="password_baru" 
                        class="form-control" 
                        placeholder="Masukkan password baru"
                        style="width: 100%; padding: 0.75rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 6px; color: var(--text); font-size: 0.9rem;">
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                        Minimal 6 karakter
                    </div>
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text);">
                        Konfirmasi Password Baru
                    </label>
                    <input 
                        type="password" 
                        id="input-password-konfirmasi" 
                        name="password_konfirmasi" 
                        class="form-control" 
                        placeholder="Ulangi password baru"
                        style="width: 100%; padding: 0.75rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 6px; color: var(--text); font-size: 0.9rem;">
                </div>

                <!-- Tombol Submit -->
                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="button" class="btn btn-primary" onclick="simpanProfil()" style="flex: 1;">
                        💾 Simpan Perubahan
                    </button>
                    <button type="button" class="btn" style="flex: 0.3; background: var(--surface2); color: var(--text-muted);" onclick="batalEditProfil()">
                        Batal
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>

<script>
// Preview Foto Profil saat dipilih
document.getElementById('input-foto-profil').addEventListener('change', function(e) {
    const file = e.target.files[0];
    
    if (!file) return;
    
    // Validasi ukuran file (max 2MB)
    if (file.size > 2 * 1024 * 1024) {
        showAlertProfil('error', 'Ukuran file terlalu besar! Maksimal 2MB.');
        e.target.value = '';
        return;
    }
    
    // Validasi tipe file
    if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
        showAlertProfil('error', 'Format file tidak didukung! Gunakan JPG atau PNG.');
        e.target.value = '';
        return;
    }
    
    // Preview image
    const reader = new FileReader();
    reader.onload = function(event) {
        const previewImg = document.getElementById('preview-foto-profil');
        const previewInitials = document.getElementById('preview-initials');
        
        previewImg.src = event.target.result;
        previewImg.style.display = 'block';
        previewInitials.style.display = 'none';
        
        // Show tombol hapus
        document.getElementById('btn-hapus-foto').style.display = 'inline-block';
    };
    reader.readAsDataURL(file);
});

// Hapus foto profil
function hapusFotoProfil() {
    if (!confirm('Hapus foto profil?')) return;
    
    document.getElementById('input-foto-profil').value = '';
    document.getElementById('preview-foto-profil').style.display = 'none';
    document.getElementById('preview-initials').style.display = 'flex';
    document.getElementById('btn-hapus-foto').style.display = 'none';
}

// Simpan Profil
async function simpanProfil() {
    const formData = new FormData();
    
    // Ambil data form
    const nama = document.getElementById('input-nama').value.trim();
    const username = document.getElementById('input-username').value.trim();
    const passwordLama = document.getElementById('input-password-lama').value;
    const passwordBaru = document.getElementById('input-password-baru').value;
    const passwordKonfirmasi = document.getElementById('input-password-konfirmasi').value;
    
    // Validasi
    if (!nama || !username) {
        showAlertProfil('error', 'Nama dan username harus diisi!');
        return;
    }
    
    // Validasi password jika ada yang diisi
    if (passwordLama || passwordBaru || passwordKonfirmasi) {
        if (!passwordLama) {
            showAlertProfil('error', 'Masukkan password lama untuk mengubah password!');
            return;
        }
        if (!passwordBaru) {
            showAlertProfil('error', 'Masukkan password baru!');
            return;
        }
        if (passwordBaru.length < 6) {
            showAlertProfil('error', 'Password baru minimal 6 karakter!');
            return;
        }
        if (passwordBaru !== passwordKonfirmasi) {
            showAlertProfil('error', 'Konfirmasi password tidak cocok!');
            return;
        }
    }
    
    // Append data ke FormData
    formData.append('nama', nama);
    formData.append('username', username);
    
    if (passwordLama) {
        formData.append('password_lama', passwordLama);
        formData.append('password_baru', passwordBaru);
    }
    
    // Append foto jika ada
    const fotoInput = document.getElementById('input-foto-profil');
    if (fotoInput.files.length > 0) {
        formData.append('foto_profil', fotoInput.files[0]);
    }
    
    try {
        const response = await fetch('api/update_profil.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlertProfil('success', result.message || 'Profil berhasil diperbarui!');
            
            // Update session data di JavaScript
            if (result.data) {
                window.APP_USER = result.data.nama;
                
                // Update sidebar user info
                updateSidebarUserInfo(result.data);
                
                // Clear password fields
                document.getElementById('input-password-lama').value = '';
                document.getElementById('input-password-baru').value = '';
                document.getElementById('input-password-konfirmasi').value = '';
            }
            
            // Reload halaman setelah 1.5 detik
            setTimeout(() => {
                location.reload();
            }, 1500);
            
        } else {
            showAlertProfil('error', result.message || 'Gagal memperbarui profil!');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlertProfil('error', 'Terjadi kesalahan saat menyimpan data!');
    }
}

// Update sidebar user info
function updateSidebarUserInfo(data) {
    // Update nama
    const userNameElement = document.querySelector('.user-name');
    if (userNameElement) {
        userNameElement.textContent = data.nama;
        userNameElement.setAttribute('title', data.nama);
    }
    
    // Update avatar initials
    const userAvatarElement = document.querySelector('.user-avatar');
    if (userAvatarElement && !data.foto_profil) {
        const initials = data.nama.substring(0, 2).toUpperCase();
        userAvatarElement.textContent = initials;
    }
    
    // Update foto profil jika ada
    if (data.foto_profil) {
        // Implementasi update foto di sidebar akan ditambahkan nanti
    }
}

// Batal edit profil
function batalEditProfil() {
    // Kembali ke dashboard
    showPage('dashboard', document.querySelector('.nav-item'));
}

// Show alert profil
function showAlertProfil(type, message) {
    const alertDiv = document.getElementById('alert-profil');
    const alertText = document.getElementById('alert-profil-text');
    
    alertText.textContent = message;
    alertDiv.style.display = 'block';
    
    if (type === 'success') {
        alertDiv.style.background = 'rgba(52, 211, 153, 0.15)';
        alertDiv.style.border = '1px solid rgba(52, 211, 153, 0.3)';
        alertDiv.style.color = '#34d399';
    } else {
        alertDiv.style.background = 'rgba(248, 113, 113, 0.15)';
        alertDiv.style.border = '1px solid rgba(248, 113, 113, 0.3)';
        alertDiv.style.color = '#f87171';
    }
    
    // Auto hide setelah 5 detik
    setTimeout(() => {
        alertDiv.style.display = 'none';
    }, 5000);
    
    // Scroll ke atas
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Load foto profil saat halaman dimuat
window.addEventListener('DOMContentLoaded', function() {
    loadFotoProfilSaatIni();
});

// Load foto profil yang sudah ada
async function loadFotoProfilSaatIni() {
    try {
        const response = await fetch('api/get_profil.php');
        const result = await response.json();
        
        if (result.success && result.data.foto_profil) {
            const previewImg = document.getElementById('preview-foto-profil');
            const previewInitials = document.getElementById('preview-initials');
            
            previewImg.src = result.data.foto_profil;
            previewImg.style.display = 'block';
            previewInitials.style.display = 'none';
            document.getElementById('btn-hapus-foto').style.display = 'inline-block';
        }
    } catch (error) {
        console.error('Error loading foto profil:', error);
    }
}
</script>
