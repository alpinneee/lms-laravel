/**
 * Toast Notification System
 * 
 * A simple toast notification system for displaying success, error, warning, and info messages.
 * 
 * Usage:
 * - Import this file in your JS: import './toast-notification';
 * - Call window.showToast('Your message', 'success|error|warning|info');
 * - Or use the global function: showToast('Your message', 'success|error|warning|info');
 */

// Initialize the toast system when the DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Make sure we have a container
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 right-4 z-50 max-w-md space-y-2';
        document.body.appendChild(container);
    }
});

/**
 * Show a toast notification
 * 
 * @param {string} message - The message to display
 * @param {string} type - The type of notification (success, error, warning, info)
 */
window.showToast = function(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    // Truncate message if too long
    const maxLength = 150;
    const displayMessage = message.length > maxLength ? message.substring(0, maxLength) + '...' : message;
    
    const toast = document.createElement('div');
    toast.className = `toast animate-slide-down mb-2 max-w-md w-full bg-white shadow-lg rounded-lg pointer-events-auto border-l-4 overflow-hidden ${
        type === 'success' ? 'border-green-400' : 
        type === 'error' ? 'border-red-400' : 
        type === 'warning' ? 'border-yellow-400' : 'border-blue-400'
    }`;
    
    let icon = '';
    if (type === 'success') {
        icon = '<svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>';
    } else if (type === 'error') {
        icon = '<svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
    } else if (type === 'warning') {
        icon = '<svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>';
    } else {
        icon = '<svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>';
    }
    
    toast.innerHTML = `
        <div class="p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    ${icon}
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 break-words leading-relaxed">${displayMessage}</p>
                    ${message.length > maxLength ? `<button class="text-xs text-blue-600 hover:text-blue-800 mt-1 show-full-message">Lihat selengkapnya</button>` : ''}
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button class="close-toast bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.classList.add('animate-fade-out');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 300);
        }
    }, 5000);
    
    // Close button handler
    toast.querySelector('.close-toast').addEventListener('click', () => {
        toast.classList.add('animate-fade-out');
        setTimeout(() => {
            toast.remove();
        }, 300);
    });
    
    // Show full message handler
    const showFullBtn = toast.querySelector('.show-full-message');
    if (showFullBtn) {
        showFullBtn.addEventListener('click', () => {
            const messageEl = toast.querySelector('p');
            messageEl.textContent = message;
            showFullBtn.remove();
        });
    }
};

