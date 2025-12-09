<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <style>
        body { background-color: rgb(240, 238, 230); }
        .sidebar { background-color: #352b06; }
        .sidebar a { color: #f0eee6; }
        .sidebar a:hover { color: #af9c5e; }
        .title { color: #352b06; }
        .btn-main { background-color: #af9c5e; color: white; }
        .btn-main:hover { background-color: #352b06; }
        .card { background-color: white; border: 2px solid #af9c5e; }
    </style>
</head>
<body class="flex min-h-screen">

    <div class="sidebar w-64 p-6 text-lg flex flex-col gap-4">
        <h2 class="text-2xl font-bold text-white mb-4">Admin Cafe</h2>
        <a href="#menu">Kelola Menu</a>
        <a href="#reservasi">Reservasi Pelanggan</a>
        <a href="#pesanan">Pesanan Menu</a>
    </div>

    <div class="flex-1 p-8 space-y-12">

        <section id="menu">
            <h2 class="text-3xl font-bold title mb-4">Kelola Menu</h2>

            <div class="card p-6 rounded-xl mb-6">
                <h3 class="text-xl font-semibold mb-3">Tambah Menu Baru</h3>
                <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" placeholder="Nama Menu" class="p-2 border rounded-lg" />
                    <input type="number" placeholder="Harga" class="p-2 border rounded-lg" />
                    <input type="text" placeholder="Kategori (makanan/minuman)" class="p-2 border rounded-lg" />
                    <button class="btn-main p-2 rounded-lg md:col-span-3">Tambah</button>
                </form>
            </div>

            <div class="card p-6 rounded-xl">
                <h3 class="text-xl font-semibold mb-3">Daftar Menu</h3>
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="p-2">Nama</th>
                            <th class="p-2">Harga</th>
                            <th class="p-2">Kategori</th>
                            <th class="p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-2">Cappuccino</td>
                            <td class="p-2">25000</td>
                            <td class="p-2">Minuman</td>
                            <td class="p-2 flex gap-2">
                                <button class="btn-main p-1 px-3 rounded">Edit</button>
                                <button class="bg-red-500 text-white p-1 px-3 rounded">Hapus</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">Pasta Creamy</td>
                            <td class="p-2">38000</td>
                            <td class="p-2">Makanan</td>
                            <td class="p-2 flex gap-2">
                                <button class="btn-main p-1 px-3 rounded">Edit</button>
                                <button class="bg-red-500 text-white p-1 px-3 rounded">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="reservasi">
            <h2 class="text-3xl font-bold title mb-4">Reservasi Pelanggan</h2>

            <div class="card p-6 rounded-xl">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="p-2">Nama</th>
                            <th class="p-2">Tanggal</th>
                            <th class="p-2">Waktu</th>
                            <th class="p-2">Orang</th>
                            <th class="p-2">Area</th>
                            <th class="p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-2">Yemima</td>
                            <td class="p-2">2025-11-25</td>
                            <td class="p-2">18:00</td>
                            <td class="p-2">2</td>
                            <td class="p-2">Indoor</td>
                            <td class="p-2">
                                <button class="btn-main p-1 px-3 rounded">Detail</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="pesanan">
            <h2 class="text-3xl font-bold title mb-4">Pesanan Menu Pelanggan</h2>

            <div class="card p-6 rounded-xl">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="p-2">Nama Pelanggan</th>
                            <th class="p-2">Item</th>
                            <th class="p-2">Total Harga</th>
                            <th class="p-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-2">Natasha</td>
                            <td class="p-2">Latte x2, Croissant x1</td>
                            <td class="p-2">68.000</td>
                            <td class="p-2 text-yellow-700 font-semibold">Menunggu</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <h4></h4>
    </div>
</body>
</html>
