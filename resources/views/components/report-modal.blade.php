<?php
    $colors = [
        'Positive' => 'success',
        'Negative' => 'error',
        'Neutral' => 'info'
    ];
?>

<dialog id="reportModal" class="modal">
    <div class="modal-box max-w-2xl bg-[#1a1b26]"> <!-- Reduced max width -->
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-xl">Sentiment Analysis Report</h3>
            <button onclick="closeReportModal()" class="btn btn-ghost btn-sm btn-circle">×</button>
        </div>
        
        <!-- Report Content Container -->
        <div id="reportContent" class="min-h-[300px] max-h-[70vh] overflow-y-auto pr-2">
            <!-- Loading State -->
            <div class="flex justify-center items-center p-8">
                <span class="loading loading-spinner loading-lg"></span>
            </div>
        </div>

        <!-- Modal Actions -->
        <div class="modal-action mt-4 border-t border-base-300 pt-4">
            <button onclick="downloadReport()" class="btn btn-primary btn-sm gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download PDF
            </button>
            <button class="btn btn-sm" onclick="closeReportModal()">Close</button>
        </div>
    </div>
</dialog>


<script>
let currentReportId = null;

function showReport(id) {
    if (!id) return;
    
    currentReportId = id;
    const modal = document.getElementById('reportModal');
    const reportContent = document.getElementById('reportContent');
    
    // Show loading state
    reportContent.innerHTML = `
        <div class="flex justify-center items-center p-8">
            <span class="loading loading-spinner loading-lg"></span>
        </div>
    `;
    
    modal.showModal();

    // Fetch report
    fetch(`/report/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to load report');
        return response.text();
    })
    .then(html => {
        reportContent.innerHTML = html;
    })
    .catch(error => {
        console.error('Error:', error);
        reportContent.innerHTML = `
            <div class="alert alert-error flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Failed to load report: ${error.message}</span>
            </div>
        `;
    });
}

function downloadReport() {
    if (!currentReportId) return;

    const downloadUrl = `/report/${currentReportId}/download`;
    
    // Show loading state
    Swal.fire({
        title: 'Generating PDF...',
        text: 'Please wait while we prepare your report',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
            window.location.href = downloadUrl;
            setTimeout(() => Swal.close(), 1500);
        }
    });
}

function closeReportModal() {
    const modal = document.getElementById('reportModal');
    if (modal) {
        modal.close();
        currentReportId = null;
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('reportModal');
    
    // Close modal when clicking outside
    modal?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeReportModal();
        }
    });

    // Close modal when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal) {
            closeReportModal();
        }
    });

    // Disable scrolling on body when modal is open
    modal?.addEventListener('show', function() {
        document.body.style.overflow = 'hidden';
    });

    modal?.addEventListener('close', function() {
        document.body.style.overflow = '';
    });
});
</script>

<style>
    .modal-box {
        width: 600px !important;
        padding: 1.5rem;
    }

    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.7) !important;
        backdrop-filter: blur(4px);
    }

    #reportContent {
        scrollbar-width: thin;
        scrollbar-color: hsl(var(--p)) transparent;
    }

    #reportContent::-webkit-scrollbar {
        width: 4px;
    }

    #reportContent::-webkit-scrollbar-track {
        background: transparent;
    }

    #reportContent::-webkit-scrollbar-thumb {
        background-color: hsl(var(--p));
        border-radius: 4px;
    }

    .loading {
        color: hsl(var(--p));
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal[open] {
        animation: fadeIn 0.3s ease-out;
    }
</style>