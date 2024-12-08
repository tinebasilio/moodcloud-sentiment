<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        /* Base Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            background-color: #1a1b26;
            color: #a9b1d6;
            line-height: 1.6;
        }

        /* Header */
        .header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #7928ca, #ff0080);
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .header p {
            margin: 0.5rem 0 0;
            opacity: 0.9;
        }

        /* Content Sections */
        .content {
            padding: 1.5rem;
        }

        .section {
            background: #1f2335;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #2d2e3b;
        }

        .section-title {
            color: #7aa2f7;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #2d2e3b;
        }

        /* Analysis Overview */
        .percentage-bar {
            margin: 1.5rem 0;
        }

        .bar-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .bar-container {
            width: 100%;
            height: 1.25rem;
            background: #24283b;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .bar {
            height: 100%;
            transition: width 0.5s ease;
            border-radius: 0.5rem;
        }

        .bar.positive { background: #9ece6a; }
        .bar.neutral { background: #7aa2f7; }
        .bar.negative { background: #f7768e; }

        .percentage {
            font-weight: 600;
        }

        /* Result & Emotion */
        .result-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }

        .result-box {
            background: #24283b;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #2d2e3b;
        }

        .result-label {
            font-size: 0.875rem;
            color: #7aa2f7;
            margin-bottom: 0.5rem;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .badge.positive { background: rgba(158, 206, 106, 0.2); color: #9ece6a; }
        .badge.negative { background: rgba(247, 118, 142, 0.2); color: #f7768e; }
        .badge.neutral { background: rgba(122, 162, 247, 0.2); color: #7aa2f7; }

        /* Analyzed Text */
        .text-box {
            background: #24283b;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #2d2e3b;
            font-size: 0.875rem;
            line-height: 1.7;
            margin-top: 1rem;
        }

        /* Features List */
        .features-list {
            list-style: none;
            padding: 0;
            margin: 0.5rem 0;
        }

        .features-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #2d2e3b;
            font-size: 0.875rem;
        }

        .features-list li:last-child {
            border-bottom: none;
        }

        /* Footer */
        .footer {
            text-align: center;
            color: #565f89;
            font-size: 0.75rem;
            margin-top: 2rem;
            padding: 1rem;
            border-top: 1px solid #2d2e3b;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <h1>Sentirex</h1>
        <p>Generated on {{ Carbon\Carbon::parse($date)->timezone('Asia/Manila')->format('F j, Y \a\t h:i A') }}</p>
    </div>

    <div class="content">
        <!-- Analysis Overview Section -->
        <div class="section">
            <h2 class="section-title">Analysis Overview</h2>
            
            <div class="percentage-bar">
                <div class="bar-label">
                    <span>Positive</span>
                    <span class="percentage" style="color: #9ece6a">{{ number_format($scores['positive'], 1) }}%</span>
                </div>
                <div class="bar-container">
                    <div class="bar positive" style="width: {{ $scores['positive'] }}%"></div>
                </div>
            </div>

            <div class="percentage-bar">
                <div class="bar-label">
                    <span>Neutral</span>
                    <span class="percentage" style="color: #7aa2f7">{{ number_format($scores['neutral'], 1) }}%</span>
                </div>
                <div class="bar-container">
                    <div class="bar neutral" style="width: {{ $scores['neutral'] }}%"></div>
                </div>
            </div>

            <div class="percentage-bar">
                <div class="bar-label">
                    <span>Negative</span>
                    <span class="percentage" style="color: #f7768e">{{ number_format($scores['negative'], 1) }}%</span>
                </div>
                <div class="bar-container">
                    <div class="bar negative" style="width: {{ $scores['negative'] }}%"></div>
                </div>
            </div>
        </div>

        <!-- Result & Emotion Section -->
        <div class="section">
            <h2 class="section-title">Result & Emotion</h2>
            <div class="result-grid">
                <div class="result-box">
                    <div class="result-label">Overall Sentiment</div>
                    <div class="badge {{ strtolower($sentiment->sentiment_result) }}">
                        {{ $sentiment->sentiment_result }}
                    </div>
                </div>
                <div class="result-box">
                    <div class="result-label">Detected Emotion</div>
                    <div class="badge neutral">
                        {{ $sentiment->sentiment_emotion }}
                        <span style="margin-left: 0.5rem">
                            {{ $sentiment->sentiment_emotion === 'Happy' ? '😊' : ($sentiment->sentiment_emotion === 'Sad' ? '😢' : '😐') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analyzed Text Section -->
        <div class="section">
            <h2 class="section-title">Analyzed Text</h2>
            <div class="text-box">
                {{ $sentiment->sentiment_input }}
            </div>
        </div>

        <!-- Text Features Section -->
        <div class="section">
            <h2 class="section-title">Text Features</h2>
            <ul class="features-list">
                @foreach(explode(';', $sentiment->text_features) as $feature)
                    <li>{{ trim($feature) }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} Sentirex. All rights reserved.</p>

        </div>
    </div>
</body>
</html>