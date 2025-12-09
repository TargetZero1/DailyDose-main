<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReservasiController extends Controller
{
    // Tentukan kapasitas setiap area tempat duduk
    private $areaCapacities = [
        'Indoor' => 50,
        'Outdoor' => 40,
        'VIP Room' => 20,
    ];

    // Slot waktu yang tersedia (interval 30 menit)
    private $timeSlots = [
        '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
        '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
        '15:00', '15:30', '16:00', '16:30', '17:00', '17:30',
        '18:00', '18:30', '19:00', '19:30', '20:00', '20:30'
    ];

    public function index()
    {
        $reservations = Reservasi::latest()->paginate(10);
        return view('reservasi.index', compact('reservations'));
    }

    public function create()
    {
        return view('reservasi');
    }

    /**
     * Cek ketersediaan slot waktu untuk tanggal dan area tertentu
     */
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'area' => 'required|string|in:Indoor,Outdoor,VIP Room',
            'jumlah' => 'required|integer|min:1',
        ]);

        $date = $validated['tanggal'];
        $area = $validated['area'];
        $requestedGuests = $validated['jumlah'];
        $capacity = $this->areaCapacities[$area] ?? 50;

        $availability = [];

        foreach ($this->timeSlots as $time) {
            // Count existing reservations for this area, date, and time
            $existingReservations = Reservasi::where('tanggal', $date)
                ->where('area', $area)
                ->where('waktu', $time)
                ->sum('jumlah');

            $totalGuests = $existingReservations + $requestedGuests;
            $isAvailable = $totalGuests <= $capacity;

            $availability[$time] = [
                'available' => $isAvailable,
                'capacity' => $capacity,
                'booked' => $existingReservations,
                'remaining' => max(0, $capacity - $existingReservations),
            ];
        }

        return response()->json($availability);
    }

    public function store(Request $request)
    {
        try {
            // Prevent admins from making reservations
            if (Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'pemilik')) {
                return back()->with('error', 'Admin tidak dapat membuat reservasi');
            }

            $request->validate([
                'nama' => 'required|string',
                'no_hp' => 'required|string',
                'tanggal' => 'required|date',
                'waktu' => 'required',
                'jumlah' => 'required|integer|min:1',
                'area' => 'required|string|in:Indoor,Outdoor,VIP Room',
            ]);

            // Check capacity before creating reservation
            $date = $request->tanggal;
            $area = $request->area;
            $requestedGuests = (int)$request->jumlah;
            $capacity = $this->areaCapacities[$area] ?? 50;

            // Count existing reservations for this date, time, and area
            $existingReservations = Reservasi::where('tanggal', $date)
                ->where('area', $area)
                ->where('waktu', $request->waktu)
                ->sum('jumlah');

            $totalGuests = $existingReservations + $requestedGuests;

            // Reject if over capacity
            if ($totalGuests > $capacity) {
                $remaining = $capacity - $existingReservations;
                return back()
                    ->withInput()
                    ->withErrors([
                        'capacity' => "This time slot is fully booked. {$capacity} people max for {$area}, but {$existingReservations} are already reserved. You can only add {$remaining} more guests."
                    ]);
            }

            $reservasi = Reservasi::create([
                'nama'    => $request->nama,
                'no_hp'   => $request->no_hp,
                'tanggal' => $request->tanggal,
                'waktu'   => $request->waktu,
                'jumlah'  => $request->jumlah,
                'area'    => $request->area,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('reservasi.confirmation', $reservasi->id);
        } catch (\Exception $e) {
            \Log::error('Error creating reservation: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal membuat reservasi. Coba lagi.');
        }
    }

    public function confirmation(Reservasi $reservasi)
    {
        // Generate QR Code using simple-qrcode
        $tanggalFormat = is_string($reservasi->tanggal) ? $reservasi->tanggal : $reservasi->tanggal->format('d/m/Y');
        $qrData = "Name: {$reservasi->nama}\nPhone: {$reservasi->no_hp}\nDate: {$tanggalFormat}\nTime: {$reservasi->waktu}\nGuests: {$reservasi->jumlah}\nArea: {$reservasi->area}";

        $qrSvg = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->margin(1)
            ->generate($qrData);

        // Encode to base64 for embedding in HTML (svg avoids imagick dependency)
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        // Generate WhatsApp message
        $message = "🎉 *Reservation Confirmed!*\n\n";
        $message .= "👤 Name: {$reservasi->nama}\n";
        $message .= "📞 Phone: {$reservasi->no_hp}\n";
        $message .= "📅 Date: " . (is_string($reservasi->tanggal) ? $reservasi->tanggal : $reservasi->tanggal->format('d M Y')) . "\n";
        $message .= "🕐 Time: {$reservasi->waktu}\n";
        $message .= "👥 Guests: {$reservasi->jumlah}\n";
        $message .= "🪑 Area: {$reservasi->area}\n\n";
        $message .= "Thank you for choosing DailyDose! 😊";

        $whatsappLink = "https://wa.me/62882009759102?text=" . urlencode($message);

        return view('reservations.confirmation', [
            'reservasi' => $reservasi,
            'qrCode' => $qrCodeBase64,
            'whatsappLink' => $whatsappLink
        ]);
    }

    public function show(Reservasi $reservasi)
    {
        return view('reservasi.show', compact('reservasi'));
    }

    public function edit(Reservasi $reservasi)
    {
        return view('reservasi.edit', compact('reservasi'));
    }

    public function update(Request $request, Reservasi $reservasi)
    {
        try {
            $request->validate([
                'nama' => 'required|string',
                'no_hp' => 'required|string',
                'tanggal' => 'required|date',
                'waktu' => 'required',
                'jumlah' => 'required|integer|min:1',
                'area' => 'required|string',
            ]);

            $reservasi->update($request->only(['nama', 'no_hp', 'tanggal', 'waktu', 'jumlah', 'area']));

            return redirect()->route('reservasi.show', $reservasi)
                             ->with('success', 'Reservasi berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Error updating reservation: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update reservation.');
        }
    }

    public function destroy(Reservasi $reservasi)
    {
        try {
            $reservasi->delete();

            return redirect()->route('menu')
                             ->with('success', 'Reservasi berhasil dihapus!');
        } catch (\Exception $e) {
            \Log::error('Error deleting reservation: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete reservation.');
        }
    }
}
