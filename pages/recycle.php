<?php

/**
 * pages/recycle.php - Recycle bin for deleted inventory items.
 */
?>
<div class="page" id="page-recycle">
	<div class="page-header">
		<div>
			<div class="page-title">Recycle Bin</div>
			<div class="page-subtitle">Pulihkan barang atau hapus permanen. Barang akan dihapus otomatis setelah 30 hari.</div>
		</div>
	</div>

	<div class="card">
		<div class="card-header">
			<div>
				<div style="font-weight:600">Barang terhapus</div>
				<div style="font-size:12px;color:var(--text-muted)" id="recycle-count">0 barang</div>
			</div>
		</div>
		<div class="table-wrap">
			<table>
				<thead>
					<tr>
						<th>Barang</th>
						<th>Kategori</th>
						<th>Kode</th>
						<th>Dihapus pada</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody id="tabel-recycle"></tbody>
			</table>
			<div class="empty-state" id="empty-recycle" style="display:none">
				<div class="empty-icon">🗑️</div>
				<div class="empty-text">Recycle bin kosong.</div>
			</div>
		</div>
	</div>
</div>
