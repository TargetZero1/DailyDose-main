@extends('layouts.app')

@section('content')

<div style="background: linear-gradient(135deg, #fef3e2 0%, #fae9d0 100%); min-height: 100vh; padding: 40px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 16px;">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%); color: white; padding: 40px 24px; border-radius: 16px; margin-bottom: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); text-align: center;">
            <h1 style="font-size: 36px; font-weight: 700; margin-bottom: 8px;">
                <i class="fas fa-shopping-bag"></i> My Orders
            </h1>
            <p style="font-size: 16px; opacity: 0.95;">Track and manage all your orders and reservations</p>
        </div>

        <!-- Tabs -->
        <div style="background: white; border-radius: 12px 12px 0 0; border-bottom: 2px solid #e0e0e0; display: flex; gap: 0; margin-bottom: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow-x: auto;">
            <button onclick="showTab('orders')" id="orders-tab" style="flex: 1; min-width: 200px; padding: 18px 24px; border: none; background: none; cursor: pointer; font-weight: 600; font-size: 15px; color: #d4a574; border-bottom: 4px solid #d4a574; transition: all 0.3s; white-space: nowrap;">
                <i class="fas fa-shopping-bag"></i> My Orders
            </button>
            <button onclick="showTab('reservations')" id="reservations-tab" style="flex: 1; min-width: 200px; padding: 18px 24px; border: none; background: none; cursor: pointer; font-weight: 600; font-size: 15px; color: #999; border-bottom: 4px solid transparent; transition: all 0.3s; white-space: nowrap;">
                <i class="fas fa-calendar-check"></i> My Reservations
            </button>
        </div>

        <!-- ORDERS TAB -->
        <div id="orders-content" style="display: block; background: white; border-radius: 0 0 12px 12px;">
            @if($orders->isEmpty())
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="fas fa-shopping-bag" style="font-size: 64px; color: #e0d5c7; display: block; margin-bottom: 20px;"></i>
                    <h2 style="color: #666; font-size: 22px; margin-bottom: 12px;">No Orders Yet</h2>
                    <p style="color: #999; font-size: 15px; margin-bottom: 20px;">You haven't placed any orders yet. Start shopping!</p>
                    <a href="{{ route('menu') }}" style="display: inline-block; padding: 12px 28px; background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">
                        <i class="fas fa-arrow-left"></i> Browse Menu
                    </a>
                </div>
            @else
                @foreach($orders as $order)
                    <div style="background: white; border-radius: 0; margin: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-top: 1px solid #f0f0f0;">
                        <div style="background: linear-gradient(90deg, #fafafa 0%, #ffffff 100%); padding: 20px; border-bottom: 1px solid #f0f0f0; display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
                            <div>
                                <span style="font-size: 11px; color: #999; font-weight: 600; text-transform: uppercase;">Order ID</span>
                                <div style="font-size: 15px; font-weight: 700; color: #352b06; margin-top: 4px;">#{{ $order->id }}</div>
                            </div>
                            <div>
                                <span style="font-size: 11px; color: #999; font-weight: 600; text-transform: uppercase;">Date</span>
                                <div style="font-size: 15px; font-weight: 700; color: #352b06; margin-top: 4px;">{{ $order->created_at->format('d M Y') }}</div>
                            </div>
                            <div>
                                <span style="font-size: 11px; color: #999; font-weight: 600; text-transform: uppercase;">Total</span>
                                <div style="font-size: 15px; font-weight: 700; color: #352b06; margin-top: 4px;">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <span style="font-size: 11px; color: #999; font-weight: 600; text-transform: uppercase;">Status</span>
                                <div style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; background: #d4edda; color: #155724; margin-top: 4px;">{{ ucfirst($order->status) }}</div>
                            </div>
                        </div>
                        <div style="padding: 20px;">
                            <div style="color: #666; margin-bottom: 12px;"><strong>Items:</strong> {{ $order->items->count() }} product(s)</div>
                            @foreach($order->items as $item)
                                <div style="padding: 8px 0; color: #666; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                                    • {{ $item->name ?? 'Product' }} × {{ $item->qty }}
                                </div>
                            @endforeach
                        </div>
                        <div style="padding: 16px 20px; background: #fafafa; border-top: 1px solid #f0f0f0; display: flex; gap: 12px; flex-wrap: wrap;">
                            <a href="{{ route('orders.show', $order->id) }}" style="flex: 1; min-width: 140px; padding: 12px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%); color: white;">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a href="https://wa.me/62882009759102?text=Hi, I'm inquiring about order #{{ $order->id }}" target="_blank" style="flex: 1; min-width: 140px; padding: 12px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #25d366; color: white;">
                                <i class="fab fa-whatsapp"></i> Contact
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- RESERVATIONS TAB -->
        <div id="reservations-content" style="display: none; background: white; border-radius: 0 0 12px 12px;">
            @if($reservations->isEmpty())
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="fas fa-calendar-times" style="font-size: 64px; color: #e0d5c7; display: block; margin-bottom: 20px;"></i>
                    <h2 style="color: #666; font-size: 22px; margin-bottom: 12px;">No Reservations Yet</h2>
                    <p style="color: #999; font-size: 15px; margin-bottom: 20px;">You haven't made any table reservations. Book a table now!</p>
                    <a href="{{ route('reservasi.create') }}" style="display: inline-block; padding: 12px 28px; background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">
                        <i class="fas fa-calendar-plus"></i> Make a Reservation
                    </a>
                </div>
            @else
                @foreach($reservations as $reservation)
                    <div style="background: white; border-radius: 0; margin: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-top: 1px solid #f0f0f0;">
                        <div style="background: linear-gradient(90deg, #fafafa 0%, #ffffff 100%); padding: 20px; border-bottom: 1px solid #f0f0f0; display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
                            <div>
                                <span style="font-size: 11px; color: #999; font-weight: 600; text-transform: uppercase;">Reservation ID</span>
                                <div style="font-size: 15px; font-weight: 700; color: #352b06; margin-top: 4px;">#{{ $reservation->id }}</div>
                            </div>
                            <div>
                                <span style="font-size: 11px; color: #999; font-weight: 600; text-transform: uppercase;">Date</span>
                                <div style="font-size: 15px; font-weight: 700; color: #352b06; margin-top: 4px;">{{ \Carbon\Carbon::parse($reservation->tanggal)->format('d M Y') }}</div>
                            </div>
                            <div>
                                <span style="font-size: 11px; color: #999; font-weight: 600; text-transform: uppercase;">Time</span>
                                <div style="font-size: 15px; font-weight: 700; color: #352b06; margin-top: 4px;">{{ $reservation->waktu }}</div>
                            </div>
                            <div>
                                <span style="font-size: 11px; color: #999; font-weight: 600; text-transform: uppercase;">Guests</span>
                                <div style="font-size: 15px; font-weight: 700; color: #352b06; margin-top: 4px;">{{ $reservation->jumlah }} people</div>
                            </div>
                        </div>
                        <div style="padding: 20px;">
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; font-size: 14px; color: #666;">
                                <div><strong>Name:</strong> {{ $reservation->nama }}</div>
                                <div><strong>Area:</strong> {{ $reservation->area }}</div>
                                <div><strong>Phone:</strong> {{ $reservation->no_hp }}</div>
                                <div><strong>Status:</strong> <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; background: #d4edda; color: #155724;">{{ ucfirst($reservation->status ?? 'confirmed') }}</span></div>
                            </div>
                        </div>
                        <div style="padding: 16px 20px; background: #fafafa; border-top: 1px solid #f0f0f0; display: flex; gap: 12px; flex-wrap: wrap;">
                            <a href="{{ route('reservasi.confirmation', $reservation->id) }}" style="flex: 1; min-width: 140px; padding: 12px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%); color: white;">
                                <i class="fas fa-qrcode"></i> View QR
                            </a>
                            <a href="https://wa.me/62882009759102?text=Hi, I have a reservation for {{ $reservation->nama }} on {{ \Carbon\Carbon::parse($reservation->tanggal)->format('d M Y') }} at {{ $reservation->waktu }}" target="_blank" style="flex: 1; min-width: 140px; padding: 12px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #25d366; color: white;">
                                <i class="fab fa-whatsapp"></i> Contact
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </div>
</div>

<script>
    function showTab(tab) {
        const ordersContent = document.getElementById('orders-content');
        const reservationsContent = document.getElementById('reservations-content');
        const ordersTab = document.getElementById('orders-tab');
        const reservationsTab = document.getElementById('reservations-tab');
        
        if (tab === 'orders') {
            ordersContent.style.display = 'block';
            reservationsContent.style.display = 'none';
            ordersTab.style.color = '#d4a574';
            ordersTab.style.borderBottomColor = '#d4a574';
            reservationsTab.style.color = '#999';
            reservationsTab.style.borderBottomColor = 'transparent';
        } else {
            ordersContent.style.display = 'none';
            reservationsContent.style.display = 'block';
            ordersTab.style.color = '#999';
            ordersTab.style.borderBottomColor = 'transparent';
            reservationsTab.style.color = '#d4a574';
            reservationsTab.style.borderBottomColor = '#d4a574';
        }
    }
</script>

@endsection
