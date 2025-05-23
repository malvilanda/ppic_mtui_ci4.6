<?= $this->include('dashboard/header') ?>

<!-- Tambahkan CSS Select2 -->
<link href="<?= base_url('assets/select2/select2.min.css') ?>" rel="stylesheet" />
<link href="<?= base_url('assets/select2/select2-bootstrap-5-theme.min.css') ?>" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Sweet Alert -->
<link href="<?= base_url('assets/sweetalert2/sweetalert2.min.css') ?>" rel="stylesheet" />
<script src="<?= base_url('assets/sweetalert2/sweetalert2.all.min.js') ?>"></script>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold mb-6">Transaksi Bahan Baku</h2>
        
        <form action="<?= base_url('transaksi/bahan-baku/save') ?>" method="POST" class="space-y-6" id="transactionForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jenis Bahan Baku -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Bahan Baku</label>
                    <select name="item_id" id="itemSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih Bahan Baku</option>
                        <?php foreach ($items as $item): ?>
                            <option value="<?= $item['id'] ?>" data-stock="<?= $item['stock'] ?>"><?= $item['name'] ?> - <?= $item['warehouse_name'] ?> (Stok: <?= $item['stock'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <p id="stockWarning" class="hidden mt-2 text-sm text-red-600">Stok tidak mencukupi untuk transaksi keluar</p>
                </div>

                <!-- Jenis Transaksi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Transaksi</label>
                    <select name="type" id="transactionType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="masuk">Masuk</option>
                        <option value="keluar">Keluar</option>
                    </select>
                </div>

                <!-- Unit (muncul saat transaksi keluar) -->
                <div id="unitField" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Pengambil</label>
                    <select name="unit_id" id="unitSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Unit</option>
                        <?php foreach ($units as $unit): ?>
                            <option value="<?= $unit['id'] ?>"><?= $unit['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Gudang -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gudang</label>
                    <select name="warehouse_id" id="warehouseSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih Gudang</option>
                        <?php foreach ($warehouses as $warehouse): ?>
                            <option value="<?= $warehouse['id'] ?>"><?= $warehouse['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Jumlah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <input type="number" name="quantity" id="quantityInput" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <!-- Catatan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Riwayat Transaksi -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold mb-4">Riwayat Transaksi Bahan Baku</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bahan Baku</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gudang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">PIC</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($transactions as $trans): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?= date('d/m/Y H:i', strtotime($trans['created_at'])) ?></td>
                        <td class="px-6 py-4"><?= esc($trans['item_name']) ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $trans['type'] === 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= ucfirst($trans['type']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4"><?= esc($trans['warehouse_name']) ?></td>
                        <td class="px-6 py-4"><?= esc($trans['unit_name']) ?></td>
                        <td class="px-6 py-4"><?= number_format($trans['quantity']) ?></td>
                        <td class="px-6 py-4"><?= esc($trans['pic_name']) ?></td>
                        <td class="px-6 py-4"><?= esc($trans['notes']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script -->
<script src="<?= base_url('assets/select2/jquery-3.7.1.min.js') ?>"></script>
<script src="<?= base_url('assets/select2/select2.min.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('transactionForm');
    const transactionType = document.getElementById('transactionType');
    const unitField = document.getElementById('unitField');
    const unitSelect = document.getElementById('unitSelect');
    const itemSelect = document.getElementById('itemSelect');
    const stockWarning = document.getElementById('stockWarning');
    const quantityInput = document.getElementById('quantityInput');
    const warehouseSelect = document.getElementById('warehouseSelect');

    // Inisialisasi Select2
    $('#itemSelect').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Bahan Baku',
        allowClear: true,
        width: '100%'
    });

    $('#warehouseSelect').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Gudang',
        allowClear: true,
        width: '100%'
    });

    $('#unitSelect').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Unit',
        allowClear: true,
        width: '100%'
    });

    // Function untuk mengecek part number
    async function checkPartNumber() {
        const warehouseId = warehouseSelect.value;
        const itemId = itemSelect.value;

        if (!warehouseId || !itemId) return true;

        try {
            const response = await fetch(`<?= base_url('transaksi/check-part-number') ?>/${warehouseId}/${itemId}`);
            const data = await response.json();

            if (!data.exists) {
                await Swal.fire({
                    title: 'Peringatan!',
                    text: 'Part number tidak tersedia di gudang yang dipilih',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            return true;
        } catch (error) {
            console.error('Error checking part number:', error);
            await Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memeriksa part number',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return false;
        }
    }

    // Function to toggle unit field
    function toggleUnitField() {
        const isOutgoing = transactionType.value === 'keluar';
        unitField.style.display = isOutgoing ? 'block' : 'none';
        unitSelect.required = isOutgoing;
        
        if (isOutgoing) {
            checkStock();
        } else {
            stockWarning.classList.add('hidden');
        }
    }

    // Function to check stock availability
    function checkStock() {
        if (transactionType.value === 'keluar' && itemSelect.value) {
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            const currentStock = parseInt(selectedOption.dataset.stock);
            const quantity = parseInt(quantityInput.value) || 0;
            
            if (quantity > currentStock) {
                stockWarning.classList.remove('hidden');
                return false;
            }
        }
        stockWarning.classList.add('hidden');
        return true;
    }

    // Event listeners untuk perubahan nilai
    transactionType.addEventListener('change', toggleUnitField);
    itemSelect.addEventListener('change', checkStock);
    quantityInput.addEventListener('input', checkStock);

    // Form validation
    form.addEventListener('submit', async function(e) {
        e.preventDefault(); // Mencegah form submit default

        // Validasi dasar form
        if (!form.checkValidity()) {
            e.stopPropagation();
            await Swal.fire({
                title: 'Error!',
                text: 'Mohon lengkapi semua field yang diperlukan',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Cek part number
        // const isPartNumberValid = await checkPartNumber();
        // if (!isPartNumberValid) {
        //     return;
        // }

        // Validasi stok untuk transaksi keluar
        if (transactionType.value === 'keluar') {
            if (!checkStock()) {
                await Swal.fire({
                    title: 'Error!',
                    text: 'Stok tidak mencukupi untuk transaksi keluar',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            if (!unitSelect.value) {
                await Swal.fire({
                    title: 'Error!',
                    text: 'Unit pengambil harus dipilih untuk transaksi keluar',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                unitSelect.focus();
                return;
            }
        }

        // Jika semua validasi berhasil, submit form
        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            await Swal.fire({
                title: 'Sukses!',
                text: 'Transaksi berhasil disimpan',
                icon: 'success',
                confirmButtonText: 'OK'
            });

            // Redirect atau refresh halaman
            window.location.reload();
        } catch (error) {
            console.error('Error:', error);
            await Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat menyimpan transaksi',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });

    // Initial toggle
    toggleUnitField();
});
</script>