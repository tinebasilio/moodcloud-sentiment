@extends('layouts.app')

@section('title', 'Analyze - Sentirex')
@section('header', 'Sentiment Analysis')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Input Section -->
        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Text Analysis</h2>
                    <div class="badge badge-primary gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Sentirex
                    </div>
                </div>
                
                <form id="analyzeForm" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    <!-- Text Input -->
                    <div class="form-control">
                        <div class="relative">
                            <textarea 
                                name="sentiment_input" 
                                id="sentiment_input"
                                class="textarea textarea-bordered h-32 w-full bg-base-100 focus:border-primary transition-all pr-12"
                                placeholder="Type or paste your text here for analysis..."
                            ></textarea>
                            <button type="button" id="clearText" class="absolute top-2 right-2 btn btn-ghost btn-sm btn-circle">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <label class="label">
                            <span class="label-text-alt">Character count: <span id="charCount">0</span></span>
                            <span class="label-text-alt">Word count: <span id="wordCount">0</span></span>
                        </label>
                    </div>

                    <!-- File Upload -->
                    <div class="form-control">
                        <label class="w-full h-32 flex flex-col items-center justify-center border-2 border-dashed rounded-lg cursor-pointer hover:bg-base-100 transition-all duration-300 group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-base-content opacity-70 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="mb-2 text-sm"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs opacity-60">.txt, .docx, .pdf up to 10MB</p>
                            </div>
                            <input type="file" name="fileInput" id="fileInput" class="hidden" accept=".txt,.docx,.pdf"/>
                        </label>
                    </div>

                    <!-- File Preview -->
                    <div id="filePreview" class="alert alert-info hidden">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span id="fileName"></span>
                            <button type="button" id="removeFile" class="btn btn-ghost btn-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Analysis Options -->
                    <div class="flex flex-wrap gap-4">
                        <label class="label cursor-pointer space-x-2">
                            <span class="label-text">Detailed Analysis</span>
                            <input type="checkbox" class="toggle toggle-primary" checked />
                        </label>
                        <label class="label cursor-pointer space-x-2">
                            <span class="label-text">Auto-Report</span>
                            <input type="checkbox" class="toggle toggle-primary" />
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full">
                        <span id="analyzeButtonText">Analyze Text</span>
                        <span id="loadingSpinner" class="loading loading-spinner loading-sm hidden"></span>
                    </button>
                </form>

                <!-- Quick Tips -->
                <div class="mt-6">
                    <div class="collapse collapse-arrow bg-base-100">
                        <input type="checkbox" /> 
                        <div class="collapse-title font-medium">
                            Tips for better analysis
                        </div>
                        <div class="collapse-content">
                            <ul class="list-disc list-inside space-y-2 text-sm opacity-70">
                                <li>Use clear and concise language</li>
                                <li>Include context when possible</li>
                                <li>Avoid excessive special characters</li>
                                <li>For best results, use complete sentences</li>
                                <li>Keep text length between 10-1000 words</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div id="resultsSection" class="card bg-base-200 shadow-xl hidden">
            <div class="card-body">
                <h2 class="text-2xl font-bold mb-6 flex items-center justify-between">
                    Analysis Results
                    <button id="refreshAnalysis" class="btn btn-ghost btn-sm btn-circle">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </h2>

                <!-- Sentiment Overview -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-base-100 rounded-lg p-4 relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-sm font-medium opacity-70 mb-1">Overall Sentiment</h3>
                            <p id="overallSentiment" class="text-2xl font-bold"></p>
                        </div>
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="bg-base-100 rounded-lg p-4 relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-sm font-medium opacity-70 mb-1">Detected Emotion</h3>
                            <p id="emotionResult" class="text-2xl font-bold"></p>
                        </div>
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Sentiment Stats -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="stats shadow bg-base-100">
                        <div class="stat">
                            <div class="stat-title">Positive</div>
                            <div id="positiveCount" class="stat-value text-success text-2xl">0%</div>
                        </div>
                    </div>
                    <div class="stats shadow bg-base-100">
                        <div class="stat">
                            <div class="stat-title">Neutral</div>
                            <div id="neutralCount" class="stat-value text-info text-2xl">0%</div>
                        </div>
                    </div>
                    <div class="stats shadow bg-base-100">
                        <div class="stat">
                            <div class="stat-title">Negative</div>
                            <div id="negativeCount" class="stat-value text-error text-2xl">0%</div>
                        </div>
                    </div>
                </div>

                <!-- Analyzed Text -->
                <div class="mb-6">
                    <h3 class="font-medium mb-2">Analyzed Text</h3>
                    <div id="highlightedText" class="bg-base-100 rounded-lg p-4 min-h-[100px] leading-relaxed"></div>
                </div>

                <!-- Chart -->
                <div class="bg-base-100 rounded-lg p-4">
                    <h3 class="font-medium mb-4">Sentiment Distribution</h3>
                    <canvas id="sentimentPieChart" class="max-h-64"></canvas>
                </div>

                <!-- Word Cloud -->
                <div class="bg-base-100 rounded-lg p-4 mt-6">
                    <h3 class="font-medium mb-4">Key Terms</h3>
                    <div id="wordCloud" class="h-48 w-full flex items-center justify-center"></div>
                </div>

                <!-- Export Options -->
                <div class="flex justify-end gap-2 mt-6">
                    <button class="btn btn-outline btn-sm" id="copyResults">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                        </svg>
                        Copy Text
                    </button>
                    <button class="btn btn-primary btn-sm" id="generatePDF">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let sentimentChart = null;
    let wordCloudChart = null;

    // Character and word count
    $('#sentiment_input').on('input', function() {
        const text = $(this).val();
        $('#charCount').text(text.length);
        $('#wordCount').text(text.split(/\s+/).filter(word => word.length > 0).length);
    });

    // Clear text button
    $('#clearText').click(function() {
        $('#sentiment_input').val('').focus();
        $('#charCount').text('0');
        $('#wordCount').text('0');
    });

    // File upload handling
    $('#fileInput').on('change', function(e) {
        handleFileSelect(e.target.files[0]);
    });

    function handleFileSelect(file) {
    if (file) {
        if (file.size > 10 * 1024 * 1024) { // 10MB limit
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Please select a file under 10MB.',
                background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1f2937' : '#ffffff',
                color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#ffffff' : '#000000' // Explicit text color
            });
            return;
        }

        // Show file preview with animation
        const filePreview = $('#filePreview');
        const uploadZone = $('.border-dashed');

        // Update upload zone to show success state
        uploadZone.removeClass('border-dashed').addClass('border-success bg-success/10');
        uploadZone.find('svg').removeClass('text-base-content').addClass('text-success');
        uploadZone.find('p').first().html(
            `<span class="font-semibold text-success">File uploaded:</span> ${file.name}`
        );
        uploadZone.find('p').last().html(
            `<span class="text-success">${(file.size / 1024).toFixed(1)} KB</span>`
        );

        // Show the file preview alert
        gsap.to(filePreview, {
            height: 'auto',
            opacity: 1,
            duration: 0.3,
            display: 'flex',
            onComplete: () => {
                $('#fileName').text(file.name);
                $('#sentiment_input').attr('disabled', true);
            }
        });

        // Show success toast
        Swal.fire({
            icon: 'success',
            title: 'File Uploaded!',
            text: `${file.name} has been successfully uploaded.`,
            timer: 2000,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1f2937' : '#ffffff',
            color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#ffffff' : '#000000'
        });
    }
}

$('#removeFile').click(function() {
    const filePreview = $('#filePreview');
    const uploadZone = $('.border-dashed');
    
    // Reset upload zone
    uploadZone.addClass('border-dashed')
        .removeClass('border-success bg-success/10');
    uploadZone.find('svg').addClass('text-base-content').removeClass('text-success');
    uploadZone.find('p').first().html(`
        <span class="font-semibold">Click to upload</span> or drag and drop
    `);
    uploadZone.find('p').last().html(`
        <span class="opacity-60">.txt, .docx, .pdf up to 10MB</span>
    `);

    // Hide file preview
    gsap.to(filePreview, {
        height: 0,
        opacity: 0,
        duration: 0.3,
        display: 'none',
        onComplete: () => {
            $('#fileInput').val('');
            $('#fileName').text('');
            $('#sentiment_input').attr('disabled', false);
        }
    });
});

    // Form submission
    $('#analyzeForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Validate input
        const text = formData.get('sentiment_input');
        const file = formData.get('fileInput');
        
        if (!text && !file.size) {
            Swal.fire({
                icon: 'warning',
                title: 'No Input',
                text: 'Please enter text or upload a file to analyze.',
                background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1f2937' : '#ffffff'
            });
            return;
        }

        // Show loading state
        $('#analyzeButtonText').addClass('hidden');
        $('#loadingSpinner').removeClass('hidden');
        
        // Show progress
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        Toast.fire({
            icon: 'info',
            title: 'Analyzing text...'
        });
        
        $.ajax({
            url: "{{ route('store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Show results section with animation
                if ($('#resultsSection').hasClass('hidden')) {
                    $('#resultsSection').removeClass('hidden');
                    gsap.from('#resultsSection', {
                        y: 20,
                        opacity: 0,
                        duration: 0.5,
                        ease: 'power2.out'
                    });
                }
                
                // Update overview with animations
                updateOverview(response);
                
                // Update stats with animations
                updateStats(response);
                
                // Update text highlighting with animations
                updateHighlightedText(response);

                // Update charts with animations
                updateCharts(response);


                // Success notification
                Toast.fire({
                    icon: 'success',
                    title: 'Analysis completed!'
                });

                // Smooth scroll to results
                $('#resultsSection')[0].scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Analysis Failed',
                    text: xhr.responseJSON?.message || 'An error occurred during analysis.',
                    background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1f2937' : '#ffffff'
                });
            },
            complete: function() {
                // Reset loading state
                $('#analyzeButtonText').removeClass('hidden');
                $('#loadingSpinner').addClass('hidden');
            }
        });
    });

        function updateQuickStats(stats) {
        // Update Today's Analysis count with animation
        gsap.to('.stat-value.text-primary', {
            innerText: stats.count,
            duration: 1,
            snap: { innerText: 1 }
        });

        // Update Positive Rate with animation
        gsap.to('.stat-value.text-success', {
            innerText: stats.positive_rate,
            duration: 1,
            snap: { innerText: 1 },
            suffix: '%'
        });

        // Optional: Add a highlight effect
        const statBoxes = document.querySelectorAll('.stat');
        statBoxes.forEach(box => {
            gsap.fromTo(box,
                { backgroundColor: 'rgba(var(--primary), 0.1)' },
                { 
                    backgroundColor: 'transparent',
                    duration: 1,
                    ease: 'power2.out'
                }
            );
        });
    }

    function updateOverview(response) {
        // Animate sentiment result
        const sentimentEl = $('#overallSentiment');
        gsap.to(sentimentEl, {
            opacity: 0,
            duration: 0.2,
            onComplete: () => {
                sentimentEl
                    .text(response.sentiment_result)
                    .removeClass('text-success text-error text-info')
                    .addClass(response.sentiment_result === 'Positive' ? 'text-success' : 
                             response.sentiment_result === 'Negative' ? 'text-error' : 'text-info');
                gsap.to(sentimentEl, {
                    opacity: 1,
                    duration: 0.2
                });
            }
        });

        // Animate emotion result
        const emotionEl = $('#emotionResult');
        gsap.to(emotionEl, {
            opacity: 0,
            duration: 0.2,
            onComplete: () => {
                emotionEl.text(response.sentiment_emotion);
                gsap.to(emotionEl, {
                    opacity: 1,
                    duration: 0.2
                });
            }
        });
    }

    function updateStats(response) {
        const total = response.positive_count + response.negative_count + 1;
        const positivePercent = ((response.positive_count / total) * 100).toFixed(1) + '%';
        const negativePercent = ((response.negative_count / total) * 100).toFixed(1) + '%';
        const neutralPercent = (100 - parseFloat(positivePercent) - parseFloat(negativePercent)).toFixed(1) + '%';
        
        // Animate percentages
        gsap.to('#positiveCount', {
            innerText: positivePercent,
            duration: 1,
            snap: { innerText: 1 }
        });
        gsap.to('#negativeCount', {
            innerText: negativePercent,
            duration: 1,
            snap: { innerText: 1 }
        });
        gsap.to('#neutralCount', {
            innerText: neutralPercent,
            duration: 1,
            snap: { innerText: 1 }
        });
    }

    function updateHighlightedText(response) {
    const container = $('#highlightedText');
    // Change from split(/\b/) to split(/\s+/) to properly handle word boundaries
    const words = response.sentiment_input.split(/\s+/);
    container.empty();
    
    words.forEach((word, index) => {
        // Only create span for non-empty words
        if (word.trim()) {
            const span = $('<span>').text(word);
            const cleanWord = word.toLowerCase();
            
            if (response.positive_matches.includes(cleanWord)) {
                span.addClass('text-success bg-success/10 px-1 rounded');
            } else if (response.negative_matches.includes(cleanWord)) {
                span.addClass('text-error bg-error/10 px-1 rounded');
            }
            
            gsap.from(span[0], {
                opacity: 0,
                y: 10,
                duration: 0.3,
                delay: index * 0.02
            });
            
            container.append(span);
        }
        
        if (index < words.length - 1) {
            container.append(document.createTextNode(' '));
        }
    });
}

function updateCharts(response) {
   // Pie chart code remains the same
   const total = response.positive_count + response.negative_count + 1;
   const positivePercent = ((response.positive_count / total) * 100).toFixed(1);
   const negativePercent = ((response.negative_count / total) * 100).toFixed(1);
   const neutralPercent = (100 - positivePercent - negativePercent).toFixed(1);

   if (sentimentChart) {
       sentimentChart.destroy();
   }

   const ctx = document.getElementById('sentimentPieChart').getContext('2d');
   sentimentChart = new Chart(ctx, {
       type: 'doughnut',
       data: {
           labels: ['Positive', 'Negative', 'Neutral'],
           datasets: [{
               data: [positivePercent, negativePercent, neutralPercent],
               backgroundColor: [
                   '#10B981', // success
                   '#EF4444', // error
                   '#6B7280'  // neutral
               ],
               borderWidth: 0
           }]
       },
       options: {
           responsive: true,
           maintainAspectRatio: false,
           cutout: '70%',
           plugins: {
               legend: {
                   position: 'top',
                   labels: {
                       font: {
                           family: "'Plus Jakarta Sans', sans-serif",
                           size: 12
                       },
                       color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#e5e7eb' : '#374151'
                   }
               },
               tooltip: {
                   callbacks: {
                       label: function(tooltipItem) {
                           return `${tooltipItem.label}: ${tooltipItem.raw}%`;
                       }
                   }
               }
           },
           animation: {
               animateScale: true,
               animateRotate: true
           }
       }
   });

   // Word cloud code
   const wordCloudDiv = document.getElementById('wordCloud');
   d3.select(wordCloudDiv).selectAll("*").remove();

   const words = response.sentiment_input.toLowerCase()
       .split(/\s+/)
       .filter(word => word.length > 3)
       .reduce((acc, word) => {
           word = word.replace(/[.,!?]/g, '');
           acc[word] = (acc[word] || 0) + 1;
           return acc;
       }, {});

   const wordCloudData = Object.entries(words)
       .filter(([word]) => word.length > 0)
       .map(([text, value]) => ({
           text,
           size: Math.min(50, value * 20),
           color: response.positive_matches.includes(text) ? '#10B981' : 
                  response.negative_matches.includes(text) ? '#EF4444' : '#6B7280'
       }))
       .sort((a, b) => b.size - a.size)
       .slice(0, 20);

   const width = wordCloudDiv.offsetWidth;
   const height = wordCloudDiv.offsetHeight;

   if (wordCloudData.length > 0) {
       d3.layout.cloud()
           .size([width, height])
           .words(wordCloudData)
           .padding(5)
           .rotate(() => 0)
           .fontSize(d => d.size)
           .on("end", words => {
               const svg = d3.select(wordCloudDiv)
                   .append("svg")
                   .attr("width", width)
                   .attr("height", height);

               svg.append("g")
                   .attr("transform", `translate(${width/2},${height/2})`)
                   .selectAll("text")
                   .data(words)
                   .enter()
                   .append("text")
                   .style("font-size", d => `${d.size}px`)
                   .style("fill", d => d.color)
                   .attr("text-anchor", "middle")
                   .attr("transform", d => `translate(${d.x},${d.y})`)
                   .text(d => d.text);
           })
           .start();
   }
}

    // Export functionality
    $('#copyResults').click(function() {
        const text = $('#highlightedText').text();
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Text has been copied to clipboard',
                showConfirmButton: false,
                timer: 1500,
                background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1f2937' : '#ffffff'
            });
        });
    });

    $('#generatePDF').click(function() {
        Swal.fire({
            title: 'Generating PDF...',
            text: 'Please wait while we prepare your report',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
            background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1f2937' : '#ffffff'
        });

        // Add PDF generation logic here
    });

    // Drag and drop handling
    const dropZone = document.querySelector('label.border-dashed');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        gsap.to(dropZone, {
            scale: 1.02,
            duration: 0.2
        });
        dropZone.classList.add('border-primary', 'bg-primary/5');
    }

    function unhighlight() {
        gsap.to(dropZone, {
            scale: 1,
            duration: 0.2
        });
        dropZone.classList.remove('border-primary', 'bg-primary/5');
    }

    dropZone.addEventListener('drop', function(e) {
        const file = e.dataTransfer.files[0];
        handleFileSelect(file);
        
        const dt = e.dataTransfer;
        const files = dt.files;
        document.querySelector('#fileInput').files = files;
    });

    // Initialize tooltips
    tippy('[data-tippy-content]');
});
</script>
@endpush
@endsection