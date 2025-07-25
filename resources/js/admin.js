// ANAKT Admin Dashboard JavaScript
import './bootstrap';
import Alpine from 'alpinejs';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Admin Dashboard Enhancements
class AnaktAdmin {
    constructor() {
        this.init();
    }

    init() {
        this.setupNotifications();
        this.setupRealTimeUpdates();
        this.setupKeyboardShortcuts();
        this.setupAnimations();
        this.setupTableEnhancements();
        this.setupFormValidation();
        this.setupImagePreview();
        this.setupTooltips();
    }

    // Notification System
    setupNotifications() {
        const showNotification = (message, type = 'info', duration = 3000) => {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 ${this.getNotificationClasses(type)}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${this.getNotificationIcon(type)} mr-3"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto-remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, duration);
        };

        window.showNotification = showNotification;
    }

    getNotificationClasses(type) {
        const classes = {
            success: 'bg-green-600 text-white',
            error: 'bg-red-600 text-white',
            warning: 'bg-amber-600 text-white',
            info: 'bg-blue-600 text-white'
        };
        return classes[type] || classes.info;
    }

    getNotificationIcon(type) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-triangle',
            warning: 'fa-exclamation-circle',
            info: 'fa-info-circle'
        };
        return icons[type] || icons.info;
    }

    // Real-time Updates
    setupRealTimeUpdates() {
        if (window.location.pathname.includes('/admin')) {
            // Check for new pending posts every 30 seconds
            setInterval(() => {
                this.checkForUpdates();
            }, 30000);
        }
    }

    async checkForUpdates() {
        try {
            const response = await fetch('/admin/api/pending-count');
            const data = await response.json();
            
            const pendingElements = document.querySelectorAll('[data-pending-count]');
            pendingElements.forEach(el => {
                const currentCount = parseInt(el.textContent);
                if (data.count !== currentCount) {
                    el.textContent = data.count;
                    if (data.count > currentCount) {
                        // Flash animation for new posts
                        el.parentElement.classList.add('animate-pulse');
                        setTimeout(() => {
                            el.parentElement.classList.remove('animate-pulse');
                        }, 2000);
                    }
                }
            });
        } catch (error) {
            console.log('Failed to check for updates:', error);
        }
    }

    // Keyboard Shortcuts
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + K for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('#global-search');
                if (searchInput) {
                    searchInput.focus();
                }
            }

            // Escape to close modals
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('.modal-overlay:not(.hidden)');
                modals.forEach(modal => {
                    modal.classList.add('hidden');
                });
            }

            // Quick navigation
            if (e.altKey) {
                switch (e.key) {
                    case 'd':
                        e.preventDefault();
                        window.location.href = '/admin/dashboard';
                        break;
                    case 'p':
                        e.preventDefault();
                        window.location.href = '/admin/posts';
                        break;
                    case 'u':
                        e.preventDefault();
                        window.location.href = '/admin/users';
                        break;
                }
            }
        });
    }

    // Enhanced Animations
    setupAnimations() {
        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        // Observe cards and tables
        document.querySelectorAll('.anakt-card, .table-responsive').forEach(el => {
            observer.observe(el);
        });

        // Add CSS for fade-in animation
        if (!document.querySelector('#anakt-animations')) {
            const style = document.createElement('style');
            style.id = 'anakt-animations';
            style.textContent = `
                .animate-fade-in {
                    animation: fadeIn 0.6s ease-out;
                }
                
                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                .loading-skeleton {
                    background: linear-gradient(90deg, #334155 25%, #475569 50%, #334155 75%);
                    background-size: 200% 100%;
                    animation: loading 1.5s infinite;
                }
                
                @keyframes loading {
                    0% { background-position: 200% 0; }
                    100% { background-position: -200% 0; }
                }
            `;
            document.head.appendChild(style);
        }
    }

    // Table Enhancements
    setupTableEnhancements() {
        // Add sorting functionality
        document.querySelectorAll('th[data-sortable]').forEach(th => {
            th.style.cursor = 'pointer';
            th.addEventListener('click', () => {
                this.sortTable(th);
            });
            
            // Add sort indicator
            th.innerHTML += ' <i class="fas fa-sort text-gray-500 ml-1"></i>';
        });

        // Add row selection
        document.querySelectorAll('input[type="checkbox"][data-row-select]').forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const row = checkbox.closest('tr');
                if (checkbox.checked) {
                    row.classList.add('bg-blue-900/20');
                } else {
                    row.classList.remove('bg-blue-900/20');
                }
                this.updateBulkActions();
            });
        });
    }

    sortTable(th) {
        const table = th.closest('table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const index = Array.from(th.parentNode.children).indexOf(th);
        const isAscending = th.classList.contains('sort-asc');

        rows.sort((a, b) => {
            const aValue = a.children[index].textContent.trim();
            const bValue = b.children[index].textContent.trim();
            
            if (isAscending) {
                return bValue.localeCompare(aValue);
            } else {
                return aValue.localeCompare(bValue);
            }
        });

        // Update sort indicators
        table.querySelectorAll('th i').forEach(icon => {
            icon.className = 'fas fa-sort text-gray-500 ml-1';
        });

        const icon = th.querySelector('i');
        if (isAscending) {
            icon.className = 'fas fa-sort-down text-blue-400 ml-1';
            th.classList.remove('sort-asc');
            th.classList.add('sort-desc');
        } else {
            icon.className = 'fas fa-sort-up text-blue-400 ml-1';
            th.classList.remove('sort-desc');
            th.classList.add('sort-asc');
        }

        // Reorder rows
        rows.forEach(row => tbody.appendChild(row));
    }

    updateBulkActions() {
        const selectedRows = document.querySelectorAll('input[data-row-select]:checked').length;
        const bulkActions = document.querySelector('#bulk-actions');
        
        if (bulkActions) {
            if (selectedRows > 0) {
                bulkActions.classList.remove('hidden');
                bulkActions.querySelector('#selected-count').textContent = selectedRows;
            } else {
                bulkActions.classList.add('hidden');
            }
        }
    }

    // Form Validation
    setupFormValidation() {
        document.querySelectorAll('form[data-validate]').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });

            // Real-time validation
            form.querySelectorAll('input, textarea, select').forEach(field => {
                field.addEventListener('blur', () => {
                    this.validateField(field);
                });
            });
        });
    }

    validateForm(form) {
        let isValid = true;
        const fields = form.querySelectorAll('input[required], textarea[required], select[required]');
        
        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        const isRequired = field.hasAttribute('required');
        let isValid = true;
        let message = '';

        // Remove existing error
        const existingError = field.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        field.classList.remove('border-red-500');

        // Required validation
        if (isRequired && !value) {
            isValid = false;
            message = 'This field is required';
        }

        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                message = 'Please enter a valid email address';
            }
        }

        // Show error if invalid
        if (!isValid) {
            field.classList.add('border-red-500');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-red-400 text-sm mt-1';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
        }

        return isValid;
    }

    // Image Preview
    setupImagePreview() {
        document.querySelectorAll('input[type="file"][accept*="image"]').forEach(input => {
            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        let preview = input.parentNode.querySelector('.image-preview');
                        if (!preview) {
                            preview = document.createElement('div');
                            preview.className = 'image-preview mt-3';
                            input.parentNode.appendChild(preview);
                        }
                        preview.innerHTML = `
                            <img src="${e.target.result}" class="max-w-xs rounded-lg border border-gray-600" alt="Preview">
                            <button type="button" onclick="this.parentElement.remove()" class="mt-2 text-red-400 hover:text-red-300 text-sm">
                                <i class="fas fa-trash mr-1"></i>Remove
                            </button>
                        `;
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    }

    // Tooltips
    setupTooltips() {
        // Initialize tooltips for elements with title attribute
        document.querySelectorAll('[title]').forEach(element => {
            element.addEventListener('mouseenter', (e) => {
                if (element.querySelector('.tooltip-content')) return;

                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip-content absolute z-50 px-2 py-1 text-xs text-white bg-gray-900 rounded shadow-lg pointer-events-none';
                tooltip.textContent = element.getAttribute('title');
                
                element.style.position = 'relative';
                element.appendChild(tooltip);
                
                // Position tooltip
                const rect = element.getBoundingClientRect();
                tooltip.style.bottom = '100%';
                tooltip.style.left = '50%';
                tooltip.style.transform = 'translateX(-50%)';
                tooltip.style.marginBottom = '5px';
            });

            element.addEventListener('mouseleave', () => {
                const tooltip = element.querySelector('.tooltip-content');
                if (tooltip) {
                    tooltip.remove();
                }
            });
        });
    }

    // Utility Methods
    showLoading(element) {
        const original = element.innerHTML;
        element.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
        element.disabled = true;
        return () => {
            element.innerHTML = original;
            element.disabled = false;
        };
    }

    async makeRequest(url, options = {}) {
        const defaultOptions = {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Content-Type': 'application/json',
            },
        };

        try {
            const response = await fetch(url, { ...defaultOptions, ...options });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Request failed:', error);
            window.showNotification('An error occurred. Please try again.', 'error');
            throw error;
        }
    }
}

// Initialize admin dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.anaktAdmin = new AnaktAdmin();
});

// Export for use in other modules
export default AnaktAdmin;