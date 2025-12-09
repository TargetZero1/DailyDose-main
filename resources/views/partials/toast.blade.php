<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<style>
    .toast {
        min-width: 300px;
        max-width: 400px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        padding: 16px;
        display: flex;
        align-items: start;
        gap: 12px;
        animation: slideIn 0.3s ease-out;
        border-left: 4px solid;
    }

    .toast.success {
        border-left-color: #10b981;
    }

    .toast.error {
        border-left-color: #ef4444;
    }

    .toast.warning {
        border-left-color: #f59e0b;
    }

    .toast.info {
        border-left-color: #3b82f6;
    }

    .toast-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .toast.success .toast-icon {
        background: #d1fae5;
        color: #10b981;
    }

    .toast.error .toast-icon {
        background: #fee2e2;
        color: #ef4444;
    }

    .toast.warning .toast-icon {
        background: #fef3c7;
        color: #f59e0b;
    }

    .toast.info .toast-icon {
        background: #dbeafe;
        color: #3b82f6;
    }

    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 600;
        font-size: 14px;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .toast-message {
        font-size: 13px;
        color: #6b7280;
        line-height: 1.4;
    }

    .toast-close {
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .toast-close:hover {
        background: #f3f4f6;
        color: #4b5563;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .toast.removing {
        animation: slideOut 0.3s ease-in;
    }

    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: currentColor;
        opacity: 0.3;
        border-radius: 0 0 8px 8px;
        animation: progress linear;
    }

    @keyframes progress {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }
</style>

<script>
    /**
     * Toast Notification System
     */
    window.Toast = {
        /**
         * Show a toast notification
         * @param {string} type - success, error, warning, info
         * @param {string} title - Toast title
         * @param {string} message - Toast message
         * @param {number} duration - Duration in milliseconds (default: 5000)
         */
        show(type, title, message, duration = 5000) {
            const container = document.getElementById('toast-container');
            if (!container) {
                console.error('Toast container not found');
                return;
            }

            const toastId = 'toast-' + Date.now() + Math.random();
            
            const icons = {
                success: '✓',
                error: '✕',
                warning: '⚠',
                info: 'ℹ'
            };

            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <div class="toast-icon">${icons[type] || '•'}</div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="Toast.close('${toastId}')">&times;</button>
                ${duration > 0 ? `<div class="toast-progress" style="animation-duration: ${duration}ms;"></div>` : ''}
            `;

            container.appendChild(toast);

            if (duration > 0) {
                setTimeout(() => {
                    this.close(toastId);
                }, duration);
            }

            return toastId;
        },

        success(title, message, duration) {
            return this.show('success', title, message, duration);
        },

        error(title, message, duration) {
            return this.show('error', title, message, duration);
        },

        warning(title, message, duration) {
            return this.show('warning', title, message, duration);
        },

        info(title, message, duration) {
            return this.show('info', title, message, duration);
        },

        close(toastId) {
            const toast = document.getElementById(toastId);
            if (!toast) return;

            toast.classList.add('removing');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    };

    // Auto-initialize from Laravel flash messages
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Toast.success('Success', '{{ session('success') }}');
        @endif

        @if(session('error'))
            Toast.error('Error', '{{ session('error') }}');
        @endif

        @if(session('warning'))
            Toast.warning('Warning', '{{ session('warning') }}');
        @endif

        @if(session('info'))
            Toast.info('Info', '{{ session('info') }}');
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                Toast.error('Validation Error', '{{ $error }}', 7000);
            @endforeach
        @endif
    });

    // Helper function for easier access
    window.createToast = function(type, title, message, duration) {
        return Toast.show(type, title, message, duration);
    };
</script>
