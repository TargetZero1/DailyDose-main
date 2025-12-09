@extends('layouts.app')

@section('content')
<style>
    .reservation-container {
        background: linear-gradient(135deg, #f5f3f0 0%, #eae6e0 100%);
        min-height: calc(100vh - 100px);
    }
    
    .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .form-input {
        border: 2px solid #e0d5c7;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 16px;
        transition: all 0.3s ease;
    }
    
    .form-input:focus {
        border-color: #d4a574;
        background-color: #fffbf7;
        box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
        outline: none;
    }
    
    .form-label {
        color: #352b06;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 14px 32px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(212, 165, 116, 0.3);
    }

    .btn-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .error-message {
        color: #dc2626;
        font-size: 14px;
        margin-top: 6px;
    }
    
    .section-header {
        color: #352b06;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e0d5c7;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .info-badge {
        background: #fef3f0;
        border-left: 4px solid #d4a574;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        color: #5a4a2a;
    }
    
    .info-badge i {
        color: #d4a574;
        margin-right: 8px;
    }

    .recommended-times {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 8px;
        margin-bottom: 16px;
    }

    .time-slot {
        padding: 8px 12px;
        border: 2px solid #e0d5c7;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 13px;
        font-weight: 600;
        color: #352b06;
        background: white;
    }

    .time-slot:hover:not(:disabled) {
        border-color: #d4a574;
        background: #fffbf7;
    }

    .time-slot.recommended {
        border-color: #d4a574;
        background: linear-gradient(135deg, #fffbf7 0%, #fef3f0 100%);
        color: #d4a574;
    }

    .time-slot:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #fee2e2;
        border-color: #fca5a5;
        color: #991b1b;
    }

    .time-slot:disabled:hover {
        border-color: #fca5a5;
        background: #fee2e2;
    }

    .availability-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 8px;
    }

    .availability-high {
        background: #d1fae5;
        color: #065f46;
    }

    .availability-medium {
        background: #fef3c7;
        color: #92400e;
    }

    .availability-low {
        background: #fee2e2;
        color: #991b1b;
    }

    .success-banner {
        animation: slideDown 0.5s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .area-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
    }

    .area-selector label {
        position: relative;
        display: block;
        cursor: pointer;
    }

    .area-selector input[type="radio"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .area-option {
        display: block;
        padding: 16px 12px;
        border: 2px solid #e0d5c7;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        color: #352b06;
        font-weight: 600;
        font-size: 14px;
    }

    .area-option i {
        display: block;
        font-size: 28px;
        margin-bottom: 8px;
        color: #d4a574;
    }

    .area-selector label:hover .area-option {
        border-color: #d4a574;
        background: #fffbf7;
    }

    .area-selector input[type="radio"]:checked + .area-option {
        border-color: #d4a574;
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
    }

    .area-selector input[type="radio"]:checked + .area-option i {
        color: white;
    }
</style>

<div class="reservation-container py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12 bg-gradient-to-r from-[#d4a574] to-[#8b6f47] text-white rounded-lg py-12 px-6">
            <h1 class="text-5xl font-black text-white mb-4">Reserve Your Table</h1>
            <p class="text-xl text-white">Book a cozy spot at DailyDose and enjoy the finest coffee and pastries</p>
        </div>

        <!-- Info Badges -->
        <div class="grid md:grid-cols-3 gap-4 mb-12">
            <div class="info-badge">
                <i class="fas fa-clock"></i>
                <div>
                    <div class="font-bold">Opening Hours</div>
                    <div class="text-sm">7:00 AM - 9:00 PM</div>
                </div>
            </div>
            <div class="info-badge">
                <i class="fas fa-phone"></i>
                <div>
                    <div class="font-bold">Call Us</div>
                    <div class="text-sm">+62-882-0097-102</div>
                </div>
            </div>
            <div class="info-badge">
                <i class="fas fa-users"></i>
                <div>
                    <div class="font-bold">Group Bookings</div>
                    <div class="text-sm">Max 50 guests</div>
                </div>
            </div>
        </div>

        <!-- Reservation Form -->
        <div class="form-card p-8 md:p-12">
            <form action="{{ route('reservasi.store') }}" method="POST">
                @csrf

                <!-- Display Capacity Error -->
                @if ($errors->has('capacity'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                        <div class="flex">
                            <i class="fas fa-exclamation-circle text-red-500 mr-3 mt-1"></i>
                            <div>
                                <h3 class="font-bold text-red-900">Capacity Exceeded</h3>
                                <p class="text-red-700 text-sm">{{ $errors->first('capacity') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Personal Information Section -->
                <div class="mb-8">
                    <div class="section-header">
                        <i class="fas fa-user"></i> Personal Information
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                name="nama" 
                                class="form-input w-full @error('nama') border-red-500 @enderror"
                                placeholder="John Doe"
                                value="{{ old('nama') }}"
                                required>
                            @error('nama')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="form-label">Phone Number <span class="text-red-500">*</span></label>
                            <input 
                                type="tel" 
                                name="no_hp" 
                                class="form-input w-full @error('no_hp') border-red-500 @enderror"
                                placeholder="08xxxxxxxxxx"
                                value="{{ old('no_hp') }}"
                                required>
                            @error('no_hp')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Reservation Details Section -->
                <div class="mb-8">
                    <div class="section-header">
                        <i class="fas fa-calendar-alt"></i> Reservation Details
                        <span class="availability-status availability-high" id="availabilityStatus">High Availability</span>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="form-label">Date <span class="text-red-500">*</span></label>
                            <input 
                                type="date" 
                                name="tanggal" 
                                class="form-input w-full @error('tanggal') border-red-500 @enderror"
                                value="{{ old('tanggal') }}"
                                min="{{ date('Y-m-d') }}"
                                required>
                            @error('tanggal')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="form-label">Time <span class="text-red-500">*</span></label>
                            <input 
                                type="time" 
                                name="waktu" 
                                class="form-input w-full @error('waktu') border-red-500 @enderror"
                                value="{{ old('waktu') }}"
                                required>
                            @error('waktu')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Recommended Times -->
                    <div class="mb-6">
                        <label class="form-label text-sm text-gray-600"><i class="fas fa-lightbulb"></i> Recommended Times (Click to select)</label>
                        <div class="recommended-times" id="timeSlots">
                            <button type="button" class="time-slot recommended" data-time="09:00" onclick="setTime('09:00')">09:00 AM</button>
                            <button type="button" class="time-slot recommended" data-time="11:30" onclick="setTime('11:30')">11:30 AM</button>
                            <button type="button" class="time-slot" data-time="14:00" onclick="setTime('14:00')">02:00 PM</button>
                            <button type="button" class="time-slot recommended" data-time="16:30" onclick="setTime('16:30')">04:30 PM</button>
                            <button type="button" class="time-slot recommended" data-time="18:00" onclick="setTime('18:00')">06:00 PM</button>
                            <button type="button" class="time-slot" data-time="19:30" onclick="setTime('19:30')">07:30 PM</button>
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="form-label">Number of Guests <span class="text-red-500">*</span></label>
                            <input 
                                type="number" 
                                name="jumlah" 
                                class="form-input w-full @error('jumlah') border-red-500 @enderror"
                                placeholder="2"
                                min="1"
                                max="50"
                                value="{{ old('jumlah', 2) }}"
                                required>
                            @error('jumlah')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="form-label">Seating Area <span class="text-red-500">*</span></label>
                            <div class="area-selector">
                                <label>
                                    <input type="radio" name="area" value="Indoor" {{ old('area') === 'Indoor' || !old('area') ? 'checked' : '' }}>
                                    <span class="area-option">
                                        <i class="fas fa-home"></i>
                                        <small>Indoor</small>
                                    </span>
                                </label>
                                <label>
                                    <input type="radio" name="area" value="Outdoor" {{ old('area') === 'Outdoor' ? 'checked' : '' }}>
                                    <span class="area-option">
                                        <i class="fas fa-tree"></i>
                                        <small>Outdoor</small>
                                    </span>
                                </label>
                                <label>
                                    <input type="radio" name="area" value="VIP Room" {{ old('area') === 'VIP Room' ? 'checked' : '' }}>
                                    <span class="area-option">
                                        <i class="fas fa-crown"></i>
                                        <small>VIP Room</small>
                                    </span>
                                </label>
                            </div>
                            @error('area')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Special Requests -->
                <div class="mb-8">
                    <div class="section-header">
                        <i class="fas fa-comments"></i> Special Requests (Optional)
                    </div>
                    <textarea 
                        name="notes" 
                        rows="4"
                        class="form-input w-full @error('notes') border-red-500 @enderror"
                        placeholder="Let us know about any special dietary requirements, celebrations, or preferences..."
                        style="resize: vertical;">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit mb-4">
                    <i class="fas fa-check-circle mr-2"></i> Confirm Reservation
                </button>

                <p class="text-center text-gray-600 text-sm">
                    <i class="fas fa-info-circle mr-1"></i> We'll send you a confirmation via phone number. Reservations are held for 15 minutes past the booked time.
                </p>
            </form>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mt-8 p-6 bg-green-50 border-l-4 border-green-500 rounded success-banner">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-2xl mr-4"></i>
                    <div>
                        <h3 class="font-bold text-green-900">Reservation Confirmed!</h3>
                        <p class="text-green-700 text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function setTime(time) {
        document.querySelector('input[name="waktu"]').value = time;
        // Update visual feedback
        document.querySelectorAll('.time-slot').forEach(btn => {
            btn.classList.remove('recommended');
        });
        event.target.classList.add('recommended');
    }

    // Check availability when date, area, or guest count changes
    async function checkAvailability() {
        const dateInput = document.querySelector('input[name="tanggal"]');
        const areaInput = document.querySelector('input[name="area"]:checked');
        const guestInput = document.querySelector('input[name="jumlah"]');

        if (!dateInput.value || !areaInput || !guestInput.value) {
            return; // Not ready to check
        }

        try {
            const response = await fetch('/api/reservasi/check-availability?' + new URLSearchParams({
                tanggal: dateInput.value,
                area: areaInput.value,
                jumlah: guestInput.value
            }));

            if (!response.ok) {
                console.error('Failed to check availability');
                return;
            }

            const availability = await response.json();

            // Update time slot buttons
            document.querySelectorAll('[data-time]').forEach(btn => {
                const time = btn.dataset.time;
                const isAvailable = availability[time]?.available ?? false;

                if (isAvailable) {
                    btn.disabled = false;
                    btn.title = `Available (${availability[time].remaining} seats)`;
                } else {
                    btn.disabled = true;
                    btn.title = `Not available - at capacity`;
                }
            });
        } catch (error) {
            console.error('Error checking availability:', error);
        }
    }

    // Listen for changes
    document.querySelector('input[name="tanggal"]').addEventListener('change', checkAvailability);
    document.querySelector('input[name="jumlah"]').addEventListener('change', checkAvailability);
    document.querySelectorAll('input[name="area"]').forEach(radio => {
        radio.addEventListener('change', checkAvailability);
    });

    // Initial check on page load
    window.addEventListener('load', checkAvailability);

    // Update availability status
    document.querySelector('input[name="jumlah"]').addEventListener('change', function() {
        const status = document.getElementById('availabilityStatus');
        const guests = parseInt(this.value);
        if (status) {
            if (guests > 30) {
                status.textContent = 'Limited Availability';
                status.className = 'availability-status availability-low';
            } else if (guests > 15) {
                status.textContent = 'Medium Availability';
                status.className = 'availability-status availability-medium';
            } else {
                status.textContent = 'High Availability';
                status.className = 'availability-status availability-high';
            }
        }
    });
</script>

@endsection