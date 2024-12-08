@extends('layouts.app')

@section('title', 'History')
@section('header', 'Analysis History')

@section('content')
<div class="p-6">
    <!-- Your existing table code -->
    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Input</th>
                    <th>Result</th>
                    <th>Emotion</th>
                    <th>Features</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sentiments as $sentiment)
                <tr>
                    <td>{{ Str::limit($sentiment->sentiment_input, 50) }}</td>
                    <td>
                        <span class="badge badge-{{ $sentiment->sentiment_result === 'Positive' ? 'success' : ($sentiment->sentiment_result === 'Negative' ? 'error' : 'info') }}">
                            {{ $sentiment->sentiment_result }}
                        </span>
                    </td>
                    <td>
                        <span class="flex items-center">
                            {{ $sentiment->sentiment_emotion }}
                            <span class="ml-2">
                                {{ $sentiment->sentiment_emotion === 'Happy' ? '😊' : ($sentiment->sentiment_emotion === 'Sad' ? '😢' : '😐') }}
                            </span>
                        </span>
                    </td>
                    <td>{{ Str::limit($sentiment->text_features, 30) }}</td>
                    <td>{{ $sentiment->created_at->diffForHumans() }}</td>
                    <td>
                        <div class="flex gap-2">
                            <button class="btn btn-ghost btn-sm" onclick="showReport('{{ $sentiment->id }}')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </button>
                            <form action="{{ route('softDelete', $sentiment->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn btn-ghost btn-sm text-error">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Report Modal -->
<dialog id="reportModal" class="modal">
    <div class="modal-box max-w-4xl bg-base-200">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Sentiment Analysis Report</h3>
            <button onclick="closeReportModal()" class="btn btn-ghost btn-sm btn-circle">×</button>
        </div>
        
        <div id="reportContent" class="min-h-[200px] max-h-[600px] overflow-y-auto">
            <!-- Report content will be loaded here -->
        </div>
        
        <div class="modal-action mt-6">
            <button onclick="downloadReport()" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download PDF
            </button>
            <button class="btn" onclick="closeReportModal()">Close</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button onclick="closeReportModal()">close</button>
    </form>
</dialog>

@push('scripts')
<script>
    let currentReportId = null;
    
    function showReport(id) {
        currentReportId = id;
        const modal = document.getElementById('reportModal');
        const reportContent = document.getElementById('reportContent');
    
        // Show loading state
        reportContent.innerHTML = `
            <div class="flex justify-center items-center p-8">
                <span class="loading loading-spinner loading-lg"></span>
            </div>
        `;
    
        // Show modal
        modal.showModal();
    
        // Fetch report content with proper headers
        fetch(`/report/${id}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'text/html',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed to load report');
            return response.text();
        })
        .then(html => {
            reportContent.innerHTML = html;
            
            // Re-initialize any components/scripts if needed
            if (typeof initializeReportComponents === 'function') {
                initializeReportComponents();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            reportContent.innerHTML = `
                <div class="alert alert-error flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Failed to load report: ${error.message}</span>
                </div>
            `;
        });
    }
    
    function downloadReport() {
        if (!currentReportId) return;
        window.location.href = `/report/${currentReportId}/download`;
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
    });
    </script>
@endpush
@endsection