<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { background-color: #352b06; }
        .card { background-color: #fff; border: 2px solid #af9c5e; }
        .title { color: #af9c5e; }
    </style>
</head>
<body class="p-6">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold text-center mb-6 title">Dashboard Owner Café</h1>

        <!-- GRID 2 KOLOM -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- TOTAL PENJUALAN PER HARI -->
            <div class="card p-4 rounded shadow">
                <h2 class="text-xl font-bold mb-2 text-center text-[#352b06]">Total Penjualan Harian</h2>
                <canvas id="salesChart"></canvas>
            </div>

            <!-- JUMLAH PELANGGAN PER HARI -->
            <div class="card p-4 rounded shadow">
                <h2 class="text-xl font-bold mb-2 text-center text-[#352b06]">Jumlah Pelanggan Hari Ini</h2>
                <p class="text-5xl font-extrabold text-center text-[#af9c5e] mt-6">{{ $jumlahPelanggan }}</p>
                <p class="text-center text-[#352b06] mt-2">yang telah datang hari ini</p>
            </div>

        </div>

        <!-- TABEL DETAIL PENJUALAN -->
        <div class="card p-4 rounded shadow mt-8">
            <h2 class="text-xl font-bold mb-4 text-center text-[#352b06]">Detail Penjualan</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded">
                    <thead class="bg-[#af9c5e] text-[#352b06]">
                        <tr>
                            <th class="py-2 px-4 border">Tanggal</th>
                            <th class="py-2 px-4 border">Pelanggan</th>
                            <th class="py-2 px-4 border">Total</th>
                            <th class="py-2 px-4 border">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penjualan as $item)
                            <tr class="odd:bg-gray-100 even:bg-[#e8e0bd] text-[#352b06]">
                                <td class="py-2 px-4 border">{{ $item->tanggal }}</td>
                                <td class="py-2 px-4 border">{{ $item->pelanggan }}</td>
                                <td class="py-2 px-4 border font-semibold">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                <td class="py-2 px-4 border">{{ $item->keterangan }}</td>
                            </tr>
                        @endforeach

                        @if(count($penjualan) == 0)
                            <tr>
                                <td colspan="4" class="text-center py-4">Belum ada data penjualan.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- CHART SCRIPT -->
    <script>
        const ctx = document.getElementById('salesChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($tanggal),
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: @json($totalPenjualan),
                    borderWidth: 2,
                    borderColor: '#af9c5e',
                    backgroundColor: 'rgba(175,156,94,0.4)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });
    </script>
    <!-- Tombol Export -->
    <div class="flex gap-4 justify-end mt-6">
        <a href="{{ route('owner.export.pdf') }}" class="bg-[#af9c5e] text-[#352b06] px-4 py-2 rounded font-semibold shadow hover:opacity-90">Export PDF</a>
        <a href="{{ route('owner.export.excel') }}" class="bg-[#352b06] text-[#af9c5e] px-4 py-2 rounded font-semibold shadow hover:opacity-90">Export Excel</a>
    </div>
</body>
</html>
