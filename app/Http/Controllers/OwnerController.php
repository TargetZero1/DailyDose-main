<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class OwnerController extends Controller
{
    public function index()
    {
        // hitung jumlah pelanggan dari users
        $jumlahPelanggan = User::where('role', 'pelanggan')->count();

        // data penjualan (kosong dulu)
        $penjualan = [];

        // chart kosong dulu
        $tanggal = [];
        $totalPenjualan = [];

        return view('owner', compact(
            'jumlahPelanggan',
            'penjualan',
            'tanggal',
            'totalPenjualan'
        ));
    }
}
