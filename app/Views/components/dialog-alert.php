<?php
/**
 * Global Dialog/Alert Component
 * Used with Alpine.js to show alerts programmatically without browser alert()
 */
?>
<div x-data="dialogComponent()" 
     @show-dialog.window="show($event.detail)"
     class="relative z-[100]" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true"
     x-show="isOpen"
     style="display: none;">
     
    <!-- Background backdrop -->
    <div x-show="isOpen" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal panel -->
            <div x-show="isOpen" 
                 @click.away="closeIfAllowed()"
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative transform overflow-hidden rounded-xl bg-surface text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                
                <div class="bg-surface px-6 pb-6 pt-6 sm:p-8 sm:pb-6">
                    <div class="sm:flex sm:items-start">
                        <!-- Icon container -->
                        <div class="mx-auto flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-12 sm:w-12"
                             :class="{
                                 'bg-green-100': type === 'success',
                                 'bg-red-100': type === 'error',
                                 'bg-amber-100': type === 'warning',
                                 'bg-blue-100': type === 'info'
                             }">
                             
                            <!-- Success Icon -->
                            <svg x-show="type === 'success'" class="h-7 w-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            
                            <!-- Error Icon -->
                            <svg x-show="type === 'error'" class="h-7 w-7 text-danger" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>

                            <!-- Warning Icon -->
                            <svg x-show="type === 'warning'" class="h-7 w-7 text-warning" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            
                            <!-- Info Icon -->
                            <svg x-show="type === 'info'" class="h-7 w-7 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                        </div>
                        
                        <!-- Content -->
                        <div class="mt-4 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-xl font-bold leading-6 text-textPrimary" id="modal-title" x-text="title"></h3>
                            <div class="mt-3">
                                <p class="text-base text-textSecondary leading-relaxed" x-text="message"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer actions -->
                <div class="bg-background px-6 py-4 sm:flex sm:flex-row-reverse">
                    <!-- Confirm Button (Always visible) -->
                    <button type="button" 
                            @click="confirm()"
                            class="inline-flex w-full justify-center items-center rounded-lg px-6 py-2.5 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto transition-all duration-200 cursor-pointer"
                            :class="{
                                'bg-primary hover:bg-primary-hover hover:-translate-y-px': type === 'success' || type === 'info',
                                'bg-danger hover:bg-red-700 hover:-translate-y-px': type === 'error' || type === 'warning'
                            }"
                            x-text="confirmText">
                    </button>
                    
                    <!-- Cancel Button (Only visible if showCancel is true) -->
                    <button type="button" 
                            x-show="showCancel"
                            @click="cancel()"
                            class="mt-3 inline-flex w-full justify-center items-center rounded-lg bg-white px-6 py-2.5 text-sm font-semibold text-textPrimary shadow-sm ring-1 ring-inset ring-border hover:bg-gray-50 sm:mt-0 sm:w-auto transition-all duration-200 cursor-pointer"
                            x-text="cancelText">
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Global helper to trigger dialogs
 * 
 * Usage:
 * showDialog('success', 'Berhasil', 'Data disimpan.');
 * 
 * // With confirmation callback:
 * showDialog('warning', 'Hapus Data?', 'Yakin ingin menghapus?', true, () => {
 *      // do something on confirm
 * });
 */
function showDialog(type, title, message, showCancel = false, onConfirm = null, onCancel = null) {
    window.dispatchEvent(new CustomEvent('show-dialog', {
        detail: {
            type, title, message, showCancel, onConfirm, onCancel
        }
    }));
}

document.addEventListener('alpine:init', () => {
    Alpine.data('dialogComponent', () => ({
        isOpen: false,
        type: 'info', // success, error, warning, info
        title: '',
        message: '',
        showCancel: false,
        confirmText: 'OK',
        cancelText: 'Batal',
        onConfirmCallback: null,
        onCancelCallback: null,
        
        show(data) {
            this.type = data.type || 'info';
            this.title = data.title || '';
            this.message = data.message || '';
            this.showCancel = data.showCancel || false;
            
            this.confirmText = data.confirmText || (this.showCancel ? 'Ya' : 'OK');
            this.cancelText = data.cancelText || 'Batal';
            
            this.onConfirmCallback = data.onConfirm || null;
            this.onCancelCallback = data.onCancel || null;
            
            this.isOpen = true;
            
            // Auto close success dialogs after 3 seconds if no cancel button
            if (this.type === 'success' && !this.showCancel) {
                setTimeout(() => {
                    if (this.isOpen) this.confirm();
                }, 3000);
            }
        },
        
        confirm() {
            this.isOpen = false;
            if (typeof this.onConfirmCallback === 'function') {
                this.onConfirmCallback();
            }
        },
        
        cancel() {
            this.isOpen = false;
            if (typeof this.onCancelCallback === 'function') {
                this.onCancelCallback();
            }
        },
        
        closeIfAllowed() {
            // Don't close if it's a confirmation dialog (force user to click a button)
            if (!this.showCancel) {
                this.isOpen = false;
            }
        }
    }));
});
</script>