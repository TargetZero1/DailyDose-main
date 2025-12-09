<!-- Cart Sync on Login -->
<script>
    // On page load, if user is authenticated, sync guest localStorage cart to server
    document.addEventListener('DOMContentLoaded', () => {
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        if (isAuthenticated) {
            const guestCart = JSON.parse(localStorage.getItem('cart') || '[]');
            if (guestCart.length > 0) {
                fetch('{{ route('cart.sync') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ items: guestCart })
                }).then(r => {
                    if (r.ok) {
                        // Clear localStorage cart after successful sync
                        localStorage.setItem('cart', JSON.stringify([]));
                    }
                }).catch(err => console.warn('Cart sync failed:', err));
            }
        }
    });
</script>
