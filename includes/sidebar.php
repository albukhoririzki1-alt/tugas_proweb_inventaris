<?php

/**
 * includes/sidebar.php — Sidebar navigasi berbasis peran
 */
$peran = $_SESSION['peran'];
$nama  = $_SESSION['nama'];
$initials = strtoupper(substr($nama, 0, 2));

$badgeColors = [
    'admin'    => ['bg' => 'rgba(248,113,113,0.15)', 'c' => '#f87171', 'label' => '👑 Admin'],
    'guru'     => ['bg' => 'rgba(52,211,153,0.15)',  'c' => '#34d399', 'label' => '📚 Guru'],
];
$badge = $badgeColors[$peran];
?>
<!-- SIDEBAR NAVIGASI -->
<aside class="sidebar" id="sidebar">

    <!-- Logo -->
    <div class="sidebar-logo">
        <div class="logo-mark">📦 INVENTARIS</div>
        <div class="logo-sub">Sistem Inventaris Sekolah</div>
    </div>

    <!-- Navigasi Utama -->
    <nav class="sidebar-nav">
        <div class="nav-section-label">Menu Utama</div>

        <button class="nav-item active" onclick="showPage('dashboard', this)">
            <span class="icon">📊</span> Dashboard
        </button>

        <button class="nav-item" onclick="showPage('barang', this)">
            <span class="icon">📦</span> Daftar Barang
            <span class="nav-badge" id="badge-total">0</span>
        </button>

        <button class="nav-item" onclick="showPage('peminjaman', this)">
            <span class="icon">🔄</span> Peminjaman
            <span class="nav-badge" id="badge-pinjam" style="display:none"></span>
        </button>

        <div class="nav-section-label" style="margin-top:8px">Laporan</div>

        <button class="nav-item" onclick="showPage('kondisi', this)">
            <span class="icon">🔧</span> Kondisi Barang
        </button>



        <?php if ($peran === 'admin' || $peran === 'guru'): ?>
            <button class="nav-item" onclick="showPage('laporan', this)">
                <span class="icon">📈</span> Laporan &amp; Export
            </button>
        <?php endif; ?>

        <?php if ($peran === 'admin'): ?>
            <div class="nav-section-label" style="margin-top:8px">Administrasi</div>
            <button class="nav-item" onclick="showPage('kelola-users', this)">
                <span class="icon">👥</span> Kelola User
            </button>
            <button class="nav-item" onclick="showPage('verifikasi-users', this)">
                <span class="icon">✅</span> Verifikasi user
            </button>
            <button class="nav-item" onclick="showPage('recycle', this)">
                <span class="icon">🗑️</span> Recycle Bin
            </button>
        <?php endif; ?>

    </nav>

    <!-- Info Pengguna  -->
    <div class="sidebar-user" style="cursor: pointer; position: relative;" onclick="toggleUserMenu(event)">
        <div class="user-avatar" id="sidebar-avatar">
            <?php
            // Cek apakah ada foto profil
            try {
                $pdo = getDB();
                $stmt = $pdo->prepare('SELECT foto_profil FROM users WHERE id = ?');
                $stmt->execute([$_SESSION['user_id']]);
                $userData = $stmt->fetch();
                
                if (!empty($userData['foto_profil'])):
                ?>
                    <img src="<?= htmlspecialchars($userData['foto_profil']) ?>" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                <?php else: ?>
                    <?= htmlspecialchars($initials) ?>
                <?php endif;
            } catch (Exception $e) {
                // Jika error, tampilkan initials saja
                echo htmlspecialchars($initials);
            }
            ?>
        </div>
        <div style="flex:1;min-width:0">
            <div class="user-name" title="<?= htmlspecialchars($nama) ?>"><?= htmlspecialchars($nama) ?></div>
            <div class="user-role" style="color:<?= $badge['c'] ?>"><?= $badge['label'] ?></div>
        </div>
        <span style="font-size: 0.8rem; color: var(--text-muted);">▼</span>
        
        <!-- Dropdown Menu User -->
        <div class="user-dropdown-menu" id="userDropdownMenu" style="display: none; position: absolute; bottom: 100%; left: 0; right: 0; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; margin-bottom: 0.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.3); z-index: 1000;">
            <button onclick="showPage('edit-profil', event); event.stopPropagation();" style="width: 100%; padding: 0.75rem 1rem; background: none; border: none; color: var(--text); text-align: left; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; border-radius: 8px 8px 0 0; transition: all 0.2s;">
                <span>👤</span> Edit Profil
            </button>
            <div style="height: 1px; background: var(--border); margin: 0 0.5rem;"></div>
            <a href="logout.php" onclick="event.stopPropagation();" style="width: 100%; padding: 0.75rem 1rem; background: none; border: none; color: var(--danger); text-align: left; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; text-decoration: none; border-radius: 0 0 8px 8px; transition: all 0.2s;">
                <span>⏻</span> Logout
            </a>
        </div>
    </div>

</aside>

<script>
// Toggle user dropdown menu
function toggleUserMenu(event) {
    event.stopPropagation();
    const menu = document.getElementById('userDropdownMenu');
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const menu = document.getElementById('userDropdownMenu');
    const userDiv = document.querySelector('.sidebar-user');
    
    if (menu && userDiv && !userDiv.contains(event.target)) {
        menu.style.display = 'none';
    }
});

// Hover effects for dropdown items
document.addEventListener('DOMContentLoaded', function() {
    const dropdownButtons = document.querySelectorAll('.user-dropdown-menu button, .user-dropdown-menu a');
    
    dropdownButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.background = 'var(--surface2)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.background = 'none';
        });
    });
});

// Load foto profil dinamis via AJAX
async function loadUserProfilePhoto() {
    try {
        const response = await fetch('api/get_profil.php');
        const result = await response.json();
        
        if (result.success && result.data.foto_profil) {
            const avatar = document.getElementById('sidebar-avatar');
            if (avatar) {
                avatar.innerHTML = `<img src="${result.data.foto_profil}" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
            }
        }
    } catch (error) {
        console.log('Foto profil tidak tersedia');
    }
}

// Call on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadUserProfilePhoto);
} else {
    loadUserProfilePhoto();
}
</script>