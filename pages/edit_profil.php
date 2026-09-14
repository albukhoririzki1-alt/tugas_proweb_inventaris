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
            <div class="page-title">✨ Edit Profil</div>
            <div class="page-subtitle">Kelola informasi akun Anda</div>
        </div>
    </div>

    <!-- Container Profil -->
    <div style="max-width: 900px; margin: 0 auto;">
        
        <!-- Alert untuk pesan sukses/error -->
        <div id="alert-profil" style="display: none; margin-bottom: 1.5rem; padding: 1rem 1.25rem; border-radius: 12px; font-size: 0.9rem; backdrop-filter: blur(10px); animation: slideDown 0.3s ease;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span id="alert-icon" style="font-size: 1.25rem;"></span>
                <span id="alert-profil-text"></span>
            </div>
        </div>

        <!-- Layout Grid: 2 kolom -->
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; align-items: start;">
            
            <!-- Kolom Kiri: Card Foto Profil -->
            <div class="card" style="text-align: center; padding: 2rem 1.5rem; position: sticky; top: 1rem;">
                <div style="margin-bottom: 1.25rem;">
                    <div class="foto-profil-preview" style="width: 180px; height: 180px; margin: 0 auto; border-radius: 50%; overflow: hidden; border: 4px solid var(--accent); background: linear-gradient(135deg, var(--surface2) 0%, var(--surface) 100%); display: flex; align-items: center; justify-content: center; position: relative; box-shadow: 0 8px 24px rgba(79, 138, 255, 0.15); transition: all 0.3s ease;">
                        <img id="preview-foto-profil" src="" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                        <div id="preview-initials" style="font-size: 3.5rem; font-weight: 800; color: var(--accent); font-family: 'Syne', sans-serif;">
                            <?= strtoupper(substr($currentUser['nama'], 0, 2)) ?>
                        </div>
                        <!-- Overlay hover effect -->
                        <div class="photo-overlay" style="position: absolute; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s; border-radius: 50%;">
                            <span style="color: white; font-size: 2rem;">📷</span>
                        </div>
                    </div>
                </div>
                
                <div style="margin-bottom: 0.75rem;">
                    <label for="input-foto-profil" class="btn btn-primary" style="cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; padding: 0.65rem 1.25rem; border-radius: 8px;">
                        📷 Pilih Foto
                    </label>
                    <input type="file" id="input-foto-profil" accept="image/jpeg,image/jpg,image/png" style="display: none;">
                </div>
                
                <div style="font-size: 0.75rem; color: var(--text-muted); line-height: 1.5;">
                    <div style="margin-bottom: 0.25rem;">📎 JPG, PNG</div>
                    <div>📊 Maks. 2MB</div>
                </div>
                
                <button id="btn-hapus-foto" class="btn" style="margin-top: 1rem; display: none; width: 100%; background: rgba(248,113,113,0.1); color: var(--danger); border: 1px solid rgba(248,113,113,0.3); font-size: 0.85rem; padding: 0.5rem;" onclick="hapusFotoProfil()">
                    🗑️ Hapus Foto
                </button>
                
                <!-- Info User -->
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem;">Role</div>
                    <div style="display: inline-block; padding: 0.35rem 0.75rem; background: <?= $currentUser['peran'] === 'admin' ? 'rgba(248,113,113,0.15)' : 'rgba(52,211,153,0.15)' ?>; color: <?= $currentUser['peran'] === 'admin' ? '#f87171' : '#34d399' ?>; border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                        <?= $currentUser['peran'] === 'admin' ? '👑 Admin' : '📚 Guru' ?>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Card Form Edit Data -->
            <div class="card" style="padding: 2rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border);">
                    <span style="font-size: 1.5rem;">👤</span>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text); font-family: 'Syne', sans-serif;">Informasi Akun</h3>
                </div>
                
                <form id="form-edit-profil" onsubmit="return false;">
                    
                    <!-- Grid 2 kolom untuk Nama dan Username -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                        
                        <!-- Nama -->
                        <div class="form-group">
                            <label class="form-label" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-weight: 600; color: var(--text); font-size: 0.9rem;">
                                <span>📝</span> Nama Lengkap
                            </label>
                            <input 
                                type="text" 
                                id="input-nama" 
                                name="nama" 
                                class="form-control" 
                                value="<?= htmlspecialchars($currentUser['nama']) ?>"
                                required
                                style="width: 100%; padding: 0.85rem 1rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 0.9rem; transition: all 0.2s;"
                                onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(79,138,255,0.1)'"
                                onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                        </div>

                        <!-- Username -->
                        <div class="form-group">
                            <label class="form-label" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-weight: 600; color: var(--text); font-size: 0.9rem;">
                                <span>🔑</span> Username
                            </label>
                            <input 
                                type="text" 
                                id="input-username" 
                                name="username" 
                                class="form-control" 
                                value="<?= htmlspecialchars($currentUser['username']) ?>"
                                required
                                style="width: 100%; padding: 0.85rem 1rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 0.9rem; transition: all 0.2s;"
                                onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(79,138,255,0.1)'"
                                onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                        </div>
                    
                    </div>
                    
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: -0.75rem; margin-bottom: 1.5rem; padding: 0.75rem; background: rgba(79,138,255,0.05); border-radius: 6px; border-left: 3px solid var(--accent);">
                        💡 Username harus unik dan akan digunakan untuk login
                    </div>

                    <!-- Divider Keamanan -->
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin: 2rem 0 1.5rem 0;">
                        <div style="flex: 1; height: 1px; background: var(--border);"></div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 600; color: var(--accent);">
                            <span>🔐</span> Keamanan
                        </div>
                        <div style="flex: 1; height: 1px; background: var(--border);"></div>
                    </div>

                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.25rem; padding: 0.75rem; background: rgba(251,146,60,0.05); border-radius: 6px; border-left: 3px solid var(--warn);">
                        ⚠️ Kosongkan semua field password jika tidak ingin mengubah password
                    </div>

                    <!-- Password Fields -->
                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label class="form-label" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-weight: 600; color: var(--text); font-size: 0.9rem;">
                            <span>🔒</span> Password Lama
                        </label>
                        <input 
                            type="password" 
                            id="input-password-lama" 
                            name="password_lama" 
                            class="form-control" 
                            placeholder="Masukkan password lama"
                            style="width: 100%; padding: 0.85rem 1rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 0.9rem; transition: all 0.2s;"
                            onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(79,138,255,0.1)'"
                            onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        
                        <!-- Password Baru -->
                        <div class="form-group">
                            <label class="form-label" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-weight: 600; color: var(--text); font-size: 0.9rem;">
                                <span>🔑</span> Password Baru
                            </label>
                            <input 
                                type="password" 
                                id="input-password-baru" 
                                name="password_baru" 
                                class="form-control" 
                                placeholder="Minimal 6 karakter"
                                style="width: 100%; padding: 0.85rem 1rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 0.9rem; transition: all 0.2s;"
                                onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(79,138,255,0.1)'"
                                onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="form-group">
                            <label class="form-label" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-weight: 600; color: var(--text); font-size: 0.9rem;">
                                <span>✅</span> Konfirmasi Password
                            </label>
                            <input 
                                type="password" 
                                id="input-password-konfirmasi" 
                                name="password_konfirmasi" 
                                class="form-control" 
                                placeholder="Ulangi password baru"
                                style="width: 100%; padding: 0.85rem 1rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 0.9rem; transition: all 0.2s;"
                                onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(79,138,255,0.1)'"
                                onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                        </div>
                    
                    </div>

                    <!-- Tombol Submit -->
                    <div style="display: flex; gap: 1rem; margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                        <button type="button" class="btn btn-primary" onclick="simpanProfil()" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.9rem 1.5rem; font-weight: 600; font-size: 0.95rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(79,138,255,0.25); transition: all 0.2s;">
                            <span>💾</span> Simpan Perubahan
                        </button>
                        <button type="button" class="btn" style="background: var(--surface2); color: var(--text-muted); padding: 0.9rem 1.5rem; font-weight: 500; border-radius: 10px; transition: all 0.2s;" onclick="batalEditProfil()" onmouseover="this.style.background='var(--surface)'" onmouseout="this.style.background='var(--surface2)'">
                            Batal
                        </button>
                    </div>

                </form>
            </div>
        
        </div>

    </div>

</div>

<style>
.foto-profil-preview:hover .photo-overlay {
    opacity: 1;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive untuk mobile */
@media (max-width: 768px) {
    #page-edit-profil > div > div {
        grid-template-columns: 1fr !important;
    }
    
    #page-edit-profil .card:first-child {
        position: static !important;
    }
}
</style>

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
    const alertIcon = document.getElementById('alert-icon');
    
    alertText.textContent = message;
    alertDiv.style.display = 'block';
    
    if (type === 'success') {
        alertDiv.style.background = 'linear-gradient(135deg, rgba(52, 211, 153, 0.15) 0%, rgba(52, 211, 153, 0.05) 100%)';
        alertDiv.style.border = '1px solid rgba(52, 211, 153, 0.3)';
        alertDiv.style.color = '#34d399';
        alertIcon.textContent = '✅';
    } else {
        alertDiv.style.background = 'linear-gradient(135deg, rgba(248, 113, 113, 0.15) 0%, rgba(248, 113, 113, 0.05) 100%)';
        alertDiv.style.border = '1px solid rgba(248, 113, 113, 0.3)';
        alertDiv.style.color = '#f87171';
        alertIcon.textContent = '❌';
    }
    
    // Auto hide setelah 5 detik
    setTimeout(() => {
        alertDiv.style.display = 'none';
    }, 5000);
    
    // Scroll ke atas dengan smooth
    document.getElementById('page-edit-profil').scrollIntoView({ behavior: 'smooth', block: 'start' });
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
