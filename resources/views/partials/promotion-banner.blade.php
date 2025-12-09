@php
    $promotions = App\Models\Promotion::getActive();
@endphp

@if($promotions->count())
    <div class="bg-amber-50 py-4">
        <div class="container mx-auto px-4 flex gap-4 overflow-x-auto">
            @foreach($promotions as $promo)
                <div class="min-w-[280px] bg-white rounded-lg shadow p-4 flex items-center gap-4">
                    @if($promo->banner_image)
                        @php
                            $banner = asset('img/placeholder.png');
                            if (!empty($promo->banner_image)) {
                                if (\Illuminate\Support\Str::startsWith($promo->banner_image, 'img/') || file_exists(public_path($promo->banner_image))) {
                                    $banner = asset($promo->banner_image);
                                } else {
                                    $banner = asset('storage/' . $promo->banner_image);
                                }
                            }
                        @endphp
                        <img src="{{ $banner }}" class="w-20 h-20 object-cover rounded" alt="{{ $promo->title }}">
                    @endif
                    <div>
                        <div class="text-sm text-amber-700 font-bold">{{ $promo->title }}</div>
                        <div class="text-sm text-gray-600">{{ Str::limit($promo->description, 60) }}</div>
                        <div class="text-xs text-gray-500 mt-1">Ends: {{ $promo->end_date->format('d M Y') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
