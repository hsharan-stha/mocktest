<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japanese Mock Test - Exam</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: "Hiragino Kaku Gothic ProN", "Hiragino Sans", "Meiryo", "MS PGothic", "Yu Gothic", "YuGothic", "Noto Sans JP", "Segoe UI", Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
            padding: 0;
            margin: 0;
            overflow: hidden;
            height: 100vh;
        }
        
        /* Layout Container */
        .exam-layout {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        
        /* Sidebar */
        .exam-sidebar {
            width: 250px;
            background-color: #fff;
            border-right: 2px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            box-shadow: 2px 0 4px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 2px solid #0066cc;
            background: linear-gradient(135deg, #0066cc 0%, #0088ff 100%);
            color: white;
        }
        
        .sidebar-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .sidebar-timer {
            display:none;
            font-size: 14px;
            opacity: 0.9;
        }
        
        .question-type-tabs {
            flex: 1;
            padding: 10px 0;
        }
        
        .type-tab {
            display: block;
            width: 100%;
            padding: 15px 20px;
            text-align: left;
            background: none;
            border: none;
            border-left: 4px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }
        
        .type-tab:hover {
            background-color: #f0f7ff;
            border-left-color: #0066cc;
        }
        
        .type-tab.active {
            background-color: #e3f2fd;
            border-left-color: #0066cc;
            color: #0066cc;
            font-weight: bold;
        }
        
        .type-tab-count {
            float: right;
            background-color: #0066cc;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: normal;
        }
        
        .type-tab-timer {
            display: block;
            font-size: 11px;
            color: #666;
            margin-top: 4px;
            font-weight: normal;
        }
        
        .type-tab.active .type-tab-timer {
            color: #0066cc;
        }
        
        .type-tab.active .type-tab-count {
            background-color: #0052a3;
        }
        
        /* Main Content Area */
        .exam-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        /* Header */
        .exam-header {
            background-color: #fff;
            border-bottom: 3px solid #0066cc;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }
        
        .exam-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .exam-title {
            font-size: 20px;
            font-weight: bold;
            color: #0066cc;
        }
        
        .timer-display {
            font-size: 18px;
            font-weight: bold;
            color: #d32f2f;
            background-color: #fff3cd;
            padding: 8px 16px;
            border-radius: 5px;
            border: 2px solid #ffc107;
        }
        
        /* Main Container */
        .exam-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            min-height: 0;
        }
        
        
        /* Question Container */
        .question-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 0;
            max-height: 100%;
        }
        
        .sub-section-group {
            margin-bottom: 30px;
        }
        
        .question-item {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .question-item:last-child {
            border-bottom: none;
        }
        
        #quiz {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }
        
        .question-number {
            font-size: 18px;
            font-weight: bold;
            color: #0066cc;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .question-text {
            font-size: 18px;
            line-height: 1.6;
            color: #333;
            margin-bottom: 20px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        /* Question Media */
        .question-media {
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            gap: 20px;
            flex-shrink: 0;
        }
        
        .question-image-container {
            text-align: center;
            margin: 20px 0;
        }
        
        .question-image {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 2px solid #e0e0e0;
        }
        
        .question-audio-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            background-color: #f9f9f9;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .audio-player-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
            width: 100%;
            max-width: 600px;
        }
        
        .audio-play-button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0066cc 0%, #0088ff 100%);
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 102, 204, 0.3);
        }
        
        .audio-play-button:hover {
            background: linear-gradient(135deg, #0052a3 0%, #0066cc 100%);
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 102, 204, 0.4);
        }
        
        .audio-play-button.playing {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        
        .audio-play-button svg {
            width: 24px;
            height: 24px;
        }
        
        .audio-player {
            flex: 1;
            height: 40px;
        }
        
        .audio-player audio {
            width: 100%;
            height: 40px;
        }
        
        .audio-label {
            font-size: 14px;
            color: #666;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        /* Options */
        .options-container {
            margin-top: 15px;
            flex-shrink: 0;
        }
        
        .option-item {
            display: flex;
            align-items: flex-start;
            padding: 12px 15px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .option-item:hover {
            background-color: #f0f7ff;
            border-color: #0066cc;
        }
        
        .option-item.selected {
            background-color: #e3f2fd;
            border-color: #0066cc;
            border-width: 3px;
        }
        
        .option-label {
            font-weight: bold;
            color: #0066cc;
            margin-right: 15px;
            min-width: 30px;
            font-size: 18px;
        }
        
        .option-text {
            flex: 1;
            font-size: 18px;
            color: #333;
            line-height: 1.6;
        }
        
        /* Navigation */
        .navigation-section {
            background-color: #fff;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            flex-shrink: 0;
            margin-top: 15px;
            position: sticky;
            bottom: 0;
            z-index: 10;
        }
        
        .nav-button {
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .nav-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .btn-previous {
            background-color: #6c757d;
            color: #fff;
        }
        
        .btn-previous:hover:not(:disabled) {
            background-color: #5a6268;
        }
        
        .btn-next {
            background-color: #0066cc;
            color: #fff;
        }
        
        .btn-next:hover:not(:disabled) {
            background-color: #0052a3;
        }
        
        .btn-submit {
            background-color: #28a745;
            color: #fff;
        }
        
        .btn-submit:hover:not(:disabled) {
            background-color: #218838;
        }
        
        /* Result Page */
        /* #result {
            display: none;
        } */
        
        #result {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        .result-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            text-align: center;
            min-height: 100%;
        }
        
        .result-header {
            margin-bottom: 30px;
        }
        
        .result-title {
            font-size: 32px;
            font-weight: bold;
            color: #0066cc;
            margin-bottom: 10px;
        }
        
        .result-score {
            font-size: 72px;
            font-weight: bold;
            color: #0066cc;
            margin: 20px 0;
        }
        
        .result-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .stat-box {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            border: 2px solid #e0e0e0;
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: bold;
            color: #0066cc;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
        }
        
        .stat-correct .stat-value {
            color: #28a745;
        }
        
        .stat-incorrect .stat-value {
            color: #dc3545;
        }
        
        .result-actions {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        /* Incorrect Answers Section */
        .incorrect-answers-section {
            margin-top: 40px;
            text-align: left;
        }
        
        .incorrect-answers-title {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #dc3545;
        }
        
        .incorrect-answer-item {
            background-color: #fff5f5;
            border: 2px solid #fecaca;
            border-left: 5px solid #dc3545;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .incorrect-question-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .incorrect-question-number {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
        }
        
        .incorrect-question-text {
            font-size: 18px;
            color: #333;
            line-height: 1.6;
            flex: 1;
            min-width: 200px;
        }
        
        .answer-comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }
        
        .answer-box {
            padding: 15px;
            border-radius: 5px;
        }
        
        .your-answer-box {
            background-color: #fee2e2;
            border: 2px solid #dc3545;
        }
        
        .correct-answer-box {
            background-color: #d1fae5;
            border: 2px solid #10b981;
        }
        
        .answer-label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .your-answer-label {
            color: #dc3545;
        }
        
        .correct-answer-label {
            color: #10b981;
        }
        
        .answer-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }
        
        .no-incorrect {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }
        
        .hidden {
            display: none !important;
        }
        
        @media (max-width: 768px) {
            .answer-comparison {
                grid-template-columns: 1fr;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .exam-layout {
                flex-direction: column;
            }
            
            .exam-sidebar {
                width: 100%;
                max-height: 200px;
                border-right: none;
                border-bottom: 2px solid #e0e0e0;
            }
            
            .question-type-tabs {
                display: flex;
                overflow-x: auto;
                padding: 10px;
            }
            
            .type-tab {
                min-width: 150px;
                white-space: nowrap;
            }
            
            .exam-header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .question-container {
                padding: 15px;
            }
            
            .question-text {
                font-size: 16px;
            }
            
            .option-text {
                font-size: 14px;
            }
            
            .navigation-section {
                flex-direction: column;
            }
            
            .nav-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="exam-layout">
        <!-- Sidebar -->
        <div class="exam-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">Question Types</div>
                <div class="sidebar-timer" id="sidebar-timer">Time Left: 20:00</div>
            </div>
            <div class="question-type-tabs" id="questionTypeTabs">
                @foreach ($questionsByType as $index => $typeGroup)
                    <button class="type-tab {{ $index === 0 ? 'active' : '' }}" 
                            data-type="{{ $typeGroup['type']->id }}"
                            data-timer="{{ $typeGroup['type']->timer ?? '' }}"
                            onclick="switchToQuestionType({{ $typeGroup['type']->id }})">
                        <div>
                            {{ $typeGroup['type']->type }}
                           
                        </div>
                        <span class="type-tab-count">{{ count($typeGroup['pages']) }}</span>
                        @if($typeGroup['type']->timer)
                            <span class="type-tab-timer" id="type-timer-{{ $typeGroup['type']->id }}">Timer: {{ $typeGroup['type']->timer }}:00</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Main Content -->
        <div class="exam-main">
            <!-- Header -->
            <div class="exam-header">
                <div class="exam-header-content">
                    <div class="exam-title">Japanese Mock Test - Exam</div>
                    <div class="timer-display" id="timer">Time Left: 20:00</div>
                </div>
            </div>

            <!-- Main Container -->
            <div class="exam-container">
        <!-- Question Container -->
        <div id="quiz">
            @php
                $globalIndex = 0;
            @endphp
            @foreach ($questionsByType as $typeGroup)
                @foreach ($typeGroup['sub_sections'] as $subSectionGroup)
                    @php
                        // For SSW category: Always show one question per page
                        // Determine if all questions in this sub-section should be on same page
                        $subSection = $subSectionGroup['sub_section'];
                        $subSectionPages = $subSectionGroup['pages'];
                        $shouldGroupOnSamePage = false;
                        
                        // SSW category always shows one question per page
                        // Only group if explicitly needed (but for SSW, we'll force individual)
                        // This logic is kept for potential future use but disabled for SSW
                    @endphp
                    
                    @if(false && $shouldGroupOnSamePage)
                        {{-- Display all questions from this sub-section on the same page --}}
                        <div class="question-container quiz-question hidden sub-section-group"
                             data-type="{{ $typeGroup['type']->id }}"
                             data-timer="{{ $typeGroup['type']->timer ?? '' }}"
                             data-sub-section="{{ $subSection ? $subSection->id : 'none' }}">
                            @if($subSection)
                                <div class="question-section-header">{{ $subSection->name }}</div>
                            @endif
                            
                            @foreach ($subSectionPages as $page)
                                <div class="question-item"
                                     data-id="{{ $page->id }}"
                                     data-correct="{{ $page->shuffled_correct_answer ?? $page->correct_answer }}"
                                     data-index="{{ $globalIndex }}">
                                    <div class="question-number">Question {{ $globalIndex + 1 }}</div>
                                    <div class="question-text">{{ $page->question }}</div>
                                    
                                    <!-- Question Media (Image and Audio) -->
                                    <div class="question-media">
                                        @if($page->page_image)
                                            <div class="question-image-container">
                                                <img src="{{ asset($page->page_image) }}" 
                                                     alt="Question Image" 
                                                     class="question-image"
                                                     onerror="this.style.display='none';">
                                            </div>
                                        @endif
                                        
                                        @if($page->page_html)
                                            <div class="question-audio-container">
                                                <div class="audio-player-wrapper">
                                                    <button type="button" 
                                                            class="audio-play-button" 
                                                            onclick="toggleAudio('audio-player-{{ $page->id }}', this)"
                                                            aria-label="Play audio"
                                                            id="audio-btn-{{ $page->id }}">
                                                        <svg id="play-icon-{{ $page->id }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <svg id="pause-icon-{{ $page->id }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </button>
                                                    <div style="flex: 1;">
                                                        <div class="audio-label">Listen to the audio</div>
                                                        <div class="audio-player">
                                                            <audio id="audio-player-{{ $page->id }}" 
                                                                   controls
                                                                   preload="none"
                                                                   style="width: 100%; height: 40px;"
                                                                   onplay="updatePlayButton('{{ $page->id }}', true)"
                                                                   onpause="updatePlayButton('{{ $page->id }}', false)"
                                                                   onended="resetAudioButton('{{ $page->id }}')">
                                                                <source src="{{ asset($page->page_html) }}" type="audio/mpeg">
                                                                <source src="{{ asset($page->page_html) }}" type="audio/wav">
                                                                <source src="{{ asset($page->page_html) }}" type="audio/ogg">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="options-container">
                                        @php
                                            $shuffledOptions = [];
                                            if (!empty(trim($page->shuffled_option1 ?? ''))) $shuffledOptions[] = trim($page->shuffled_option1);
                                            if (!empty(trim($page->shuffled_option2 ?? ''))) $shuffledOptions[] = trim($page->shuffled_option2);
                                            if (!empty(trim($page->shuffled_option3 ?? ''))) $shuffledOptions[] = trim($page->shuffled_option3);
                                            if (!empty(trim($page->shuffled_option4 ?? ''))) $shuffledOptions[] = trim($page->shuffled_option4);
                                        @endphp
                                        @if(count($shuffledOptions) > 0)
                                            @foreach ($shuffledOptions as $i => $optionText)
                                                <div class="option-item" data-value="{{ $i+1 }}">
                                                    <span class="option-label">{{ chr(65 + $i) }}.</span>
                                                    <span class="option-text">{{ $optionText }}</span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                @php
                                    $globalIndex++;
                                @endphp
                            @endforeach
                        </div>
                    @else
                        {{-- Display questions individually (one per page) --}}
                        @foreach ($subSectionPages as $page)
                            <div class="question-container quiz-question hidden"
                                 data-id="{{ $page->id }}"
                                 data-correct="{{ $page->shuffled_correct_answer ?? $page->correct_answer }}"
                                 data-index="{{ $globalIndex }}"
                                 data-type="{{ $typeGroup['type']->id }}"
                                 data-timer="{{ $typeGroup['type']->timer ?? '' }}">
                                @if($subSection)
                                    @php
                                        $isFirst = ($page === reset($subSectionPages));
                                    @endphp
                                    @if($isFirst)
                                        <div class="question-section-header">{{ $subSection->name }}</div>
                                    @endif
                                @endif
                                <div class="question-number">Question {{ $globalIndex + 1 }}</div>
                                <div class="question-text">{{ $page->question }}</div>
                                
                                <!-- Question Media (Image and Audio) -->
                                <div class="question-media">
                                    @if($page->page_image)
                                        <div class="question-image-container">
                                            <img src="{{ asset($page->page_image) }}" 
                                                 alt="Question Image" 
                                                 class="question-image"
                                                 onerror="this.style.display='none';">
                                        </div>
                                    @endif
                                    
                                    @if($page->page_html)
                                        <div class="question-audio-container">
                                            <div class="audio-player-wrapper">
                                                <button type="button" 
                                                        class="audio-play-button" 
                                                        onclick="toggleAudio('audio-player-{{ $page->id }}', this)"
                                                        aria-label="Play audio"
                                                        id="audio-btn-{{ $page->id }}">
                                                    <svg id="play-icon-{{ $page->id }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <svg id="pause-icon-{{ $page->id }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                        </button>
                                                <div style="flex: 1;">
                                                    <div class="audio-label">Listen to the audio</div>
                                                    <div class="audio-player">
                                                        <audio id="audio-player-{{ $page->id }}" 
                                                               controls
                                                               preload="none"
                                                               style="width: 100%; height: 40px;"
                                                               onplay="updatePlayButton('{{ $page->id }}', true)"
                                                               onpause="updatePlayButton('{{ $page->id }}', false)"
                                                               onended="resetAudioButton('{{ $page->id }}')">
                                                            <source src="{{ asset($page->page_html) }}" type="audio/mpeg">
                                                            <source src="{{ asset($page->page_html) }}" type="audio/wav">
                                                            <source src="{{ asset($page->page_html) }}" type="audio/ogg">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="options-container">
                                    @php
                                        $shuffledOptions = [];
                                        if (!empty(trim($page->shuffled_option1 ?? ''))) $shuffledOptions[] = trim($page->shuffled_option1);
                                        if (!empty(trim($page->shuffled_option2 ?? ''))) $shuffledOptions[] = trim($page->shuffled_option2);
                                        if (!empty(trim($page->shuffled_option3 ?? ''))) $shuffledOptions[] = trim($page->shuffled_option3);
                                        if (!empty(trim($page->shuffled_option4 ?? ''))) $shuffledOptions[] = trim($page->shuffled_option4);
                                    @endphp
                                    @if(count($shuffledOptions) > 0)
                                        @foreach ($shuffledOptions as $i => $optionText)
                                            <div class="option-item" data-value="{{ $i+1 }}">
                                                <span class="option-label">{{ chr(65 + $i) }}.</span>
                                                <span class="option-text">{{ $optionText }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @php
                                $globalIndex++;
                            @endphp
                        @endforeach
                    @endif
                @endforeach
            @endforeach
        </div>

        <!-- Navigation Section -->
        <div class="navigation-section">
            <button class="nav-button btn-previous" id="btn-previous" onclick="previousQuestion()" disabled>Previous</button>
            <div id="question-indicator" style="font-weight: bold; color: #666;">
                Question 1 of {{ count($pages) }}
            </div>
            <button class="nav-button btn-next" id="btn-next" onclick="nextQuestion()">Next</button>
            <button class="nav-button btn-submit hidden" id="btn-submit" onclick="submitExam()">Submit Exam</button>
        </div>

        <!-- Result Section -->
        <div id="result" class="hidden">
            <div class="result-container">
                <div class="result-header">
                    <div class="result-title">Exam Completed!</div>
                </div>
                <div class="result-score" id="result-score">0%</div>
                <div class="result-stats">
                    <div class="stat-box">
                        <div class="stat-value" id="stat-total">0</div>
                        <div class="stat-label">Total Questions</div>
                    </div>
                    <div class="stat-box stat-correct">
                        <div class="stat-value" id="stat-correct">0</div>
                        <div class="stat-label">Correct</div>
                    </div>
                    <div class="stat-box stat-incorrect">
                        <div class="stat-value" id="stat-incorrect">0</div>
                        <div class="stat-label">Incorrect</div>
                    </div>
                </div>
                
                <!-- Incorrect Answers Section -->
                <div class="incorrect-answers-section" id="incorrect-answers-section" style="display: none;">
                    <div class="incorrect-answers-title">Review Incorrect Answers</div>
                    <div id="incorrect-answers-list"></div>
                </div>
                
                <div class="result-actions">
                    <a href="/library" class="nav-button btn-next">Back to Library</a>
                    <button onclick="location.reload()" class="nav-button btn-submit">Retry Exam</button>
                </div>
            </div>
        </div>
    </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const questions = document.querySelectorAll(".quiz-question");
    const timerEl = document.getElementById("timer");
    const resultEl = document.getElementById("result");

    // Get all individual question items (including those in grouped containers)
    const allQuestionItems = [];
    questions.forEach((q) => {
        // Check if this is a grouped container
        if (q.classList.contains('sub-section-group')) {
            // Get all question items within this group
            const items = q.querySelectorAll('.question-item');
            items.forEach(item => allQuestionItems.push(item));
        } else {
            // Standalone question container
            allQuestionItems.push(q);
        }
    });
    const totalQuestions = allQuestionItems.length;
    
    // State
    let current = 0;
    let score = 0;
    let answers = {}; // Store user answers: {questionIndex: selectedValue}

    // Track current question type for timer switching
    let currentQuestionTypeId = null;

    // Per-question-type timer setup
    const questionTypeTimers = {};
    const questionTypeStartTimes = {};
    
    // Initialize timers for each question type
    questions.forEach((q) => {
        const typeId = q.dataset.type;
        const timerMinutes = parseInt(q.dataset.timer) || null;
        
        if (timerMinutes && !questionTypeTimers[typeId]) {
            questionTypeTimers[typeId] = timerMinutes * 60 * 1000; // Convert to milliseconds
            questionTypeStartTimes[typeId] = Date.now();
            localStorage.setItem(`quizStartTime_${typeId}`, questionTypeStartTimes[typeId]);
        }
    });
    
    // Global timer fallback (20 mins) - fresh start each time
    const totalTime = 20 * 60 * 1000;
    let startTime = Date.now();
    localStorage.setItem("quizStartTime", startTime);
    
    // Reset answers on fresh start
    localStorage.removeItem("answers");
    answers = {};

    function showQuestion(index) {
        // Hide all questions
        questions.forEach((q, i) => {
            q.classList.toggle("hidden", i !== index);
        });
        
        // Show current question
        if (questions[index]) {
            const questionType = questions[index].dataset.type;
            
            // Check if we're switching to a different question type
            if (currentQuestionTypeId !== questionType) {
                currentQuestionTypeId = questionType;
                
                // Get timer for this question type
                const typeTimerMinutes = parseInt(questions[index].dataset.timer) || null;
                
                if (typeTimerMinutes) {
                    // Initialize or reset timer for this question type
                    if (!questionTypeTimers[questionType]) {
                        questionTypeTimers[questionType] = typeTimerMinutes * 60 * 1000;
                    }
                    // Reset timer start time when switching to this question type
                    questionTypeStartTimes[questionType] = Date.now();
                    localStorage.setItem(`quizStartTime_${questionType}`, questionTypeStartTimes[questionType]);
                }
            }
            
            // Update active tab in sidebar
            updateActiveTab(questionType);
            
            // Update timer display for current question type
            updateTimerForQuestionType(questionType);
        }
        
        // Update question indicator
        updateQuestionIndicator(index);
        
        // Update navigation buttons
        updateNavigationButtons(index);
        
        // Restore selected answer if exists
        restoreSelectedAnswer(index);
    }
    
    function updateTimerForQuestionType(typeId) {
        const timerMinutes = questionTypeTimers[typeId] ? questionTypeTimers[typeId] / 60000 : null;
        
        if (timerMinutes) {
            // Use per-question-type timer
            const startTime = parseInt(localStorage.getItem(`quizStartTime_${typeId}`)) || questionTypeStartTimes[typeId];
            const elapsed = Date.now() - startTime;
            const remaining = questionTypeTimers[typeId] - elapsed;
            
            if (remaining <= 0) {
                // Timer expired for this question type
                submitExam();
                return;
            }
            
            const timeStr = formatTime(remaining);
            timerEl.textContent = `Time Left: ${timeStr}`;
            
            // Update sidebar timer for this type
            const typeTimerEl = document.getElementById(`type-timer-${typeId}`);
            if (typeTimerEl) {
                typeTimerEl.textContent = `Timer: ${timeStr}`;
            }
        } else {
            // Use global timer
            const elapsed = Date.now() - startTime;
            const remaining = totalTime - elapsed;
            
            if (remaining <= 0) {
                submitExam();
                return;
            }
            
            const timeStr = formatTime(remaining);
            timerEl.textContent = `Time Left: ${timeStr}`;
        }
        
        // Update sidebar timer
        const sidebarTimer = document.getElementById('sidebar-timer');
        if (sidebarTimer) {
            sidebarTimer.textContent = timerEl.textContent;
        }
    }
    
    function updateQuestionIndicator(index) {
        document.getElementById("question-indicator").textContent = `Question ${index + 1} of ${totalQuestions}`;
    }
    
    function updateActiveTab(typeId) {
        document.querySelectorAll('.type-tab').forEach(tab => {
            tab.classList.remove('active');
            if (tab.dataset.type == typeId) {
                tab.classList.add('active');
            }
        });
    }
    
    function switchToQuestionType(typeId) {
        // Reset timer when switching to a different question type
        if (currentQuestionTypeId !== typeId) {
            currentQuestionTypeId = typeId;
            
            // Get timer for this question type
            const questionEl = Array.from(questions).find(q => q.dataset.type == typeId);
            const typeTimerMinutes = questionEl ? parseInt(questionEl.dataset.timer) || null : null;
            
            if (typeTimerMinutes) {
                // Initialize or reset timer for this question type
                if (!questionTypeTimers[typeId]) {
                    questionTypeTimers[typeId] = typeTimerMinutes * 60 * 1000;
                }
                // Reset timer start time when switching to this question type
                questionTypeStartTimes[typeId] = Date.now();
                localStorage.setItem(`quizStartTime_${typeId}`, questionTypeStartTimes[typeId]);
            }
        }
        
        // Find first question of this type
        let foundIndex = -1;
        questions.forEach((q, index) => {
            if (q.dataset.type == typeId && foundIndex === -1) {
                foundIndex = index;
            }
        });
        
        if (foundIndex !== -1) {
            // Navigate to first question of this type
            current = foundIndex;
            showQuestion(foundIndex);
            
            // Scroll to the question
            setTimeout(() => {
                const questionElement = questions[foundIndex];
                if (questionElement) {
                    questionElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        }
    }
    
    function updateNavigationButtons(index) {
        const btnPrevious = document.getElementById("btn-previous");
        const btnNext = document.getElementById("btn-next");
        const btnSubmit = document.getElementById("btn-submit");
        
        btnPrevious.disabled = index === 0;
        
        if (index === totalQuestions - 1) {
            btnNext.classList.add("hidden");
            btnSubmit.classList.remove("hidden");
        } else {
            btnNext.classList.remove("hidden");
            btnSubmit.classList.add("hidden");
        }
    }
    
    function restoreSelectedAnswer(index) {
        const question = questions[index];
        const selectedValue = answers[index];
        
        // Clear all selections
        question.querySelectorAll(".option-item").forEach(item => {
            item.classList.remove("selected");
        });
        
        // Restore selection if exists
        if (selectedValue) {
            const selectedOption = question.querySelector(`[data-value="${selectedValue}"]`);
            if (selectedOption) {
                selectedOption.classList.add("selected");
            }
        }
    }
    
    function selectAnswer(questionIndex, value) {
        answers[questionIndex] = value;
        localStorage.setItem("answers", JSON.stringify(answers));
        
        // Update visual selection - use allQuestionItems instead of questions
        const questionItem = allQuestionItems[questionIndex];
        if (questionItem) {
            questionItem.querySelectorAll(".option-item").forEach(item => {
                item.classList.toggle("selected", item.dataset.value === value);
            });
        }
    }
    
    function previousQuestion() {
        if (current > 0) {
            current--;
            showQuestion(current);
        }
    }
    
    function nextQuestion() {
        if (current < totalQuestions - 1) {
            current++;
            showQuestion(current);
        }
    }
    
    function submitExam() {
        if (!confirm("Are you sure you want to submit the exam? You cannot change your answers after submission.")) {
            return;
        }
        
        // Calculate score
        score = 0;
        questions.forEach((q, index) => {
            const correctAnswer = q.dataset.correct;
            const userAnswer = answers[index];
            if (userAnswer && userAnswer === correctAnswer) {
                score++;
            }
        });
        
        showResult();
    }
    
    function getOptionText(question, optionValue) {
        const optionItem = question.querySelector(`[data-value="${optionValue}"]`);
        if (optionItem) {
            return optionItem.querySelector('.option-text').textContent.trim();
        }
        return '';
    }
    
    function getOptionLabel(optionValue) {
        return String.fromCharCode(64 + parseInt(optionValue)); // A, B, C, D
    }

    function showResult() {
        // Hide quiz
        document.querySelector("#quiz").classList.add("hidden");
        document.querySelector(".navigation-section").classList.add("hidden");
        timerEl.classList.add("hidden");
        
        // Show result
        resultEl.classList.remove("hidden");
        
        const percentage = (score / totalQuestions * 100).toFixed(1);
        const incorrect = totalQuestions - score;
        
        document.getElementById("result-score").textContent = percentage + "%";
        document.getElementById("stat-total").textContent = totalQuestions;
        document.getElementById("stat-correct").textContent = score;
        document.getElementById("stat-incorrect").textContent = incorrect;
        
        // Collect incorrect answers
        const incorrectAnswers = [];
        allQuestionItems.forEach((q, index) => {
            const correctAnswer = q.dataset.correct;
            const userAnswer = answers[index];
            const questionTextEl = q.querySelector('.question-text');
            const questionText = questionTextEl ? questionTextEl.textContent.trim() : 'Question ' + (index + 1);
            const questionNumber = index + 1;
            
            // Check if answer is incorrect or not answered
            if (!userAnswer || userAnswer !== correctAnswer) {
                const userAnswerText = userAnswer ? getOptionText(q, userAnswer) : 'Not answered';
                const correctAnswerText = getOptionText(q, correctAnswer);
                const userAnswerLabel = userAnswer ? getOptionLabel(userAnswer) : 'N/A';
                const correctAnswerLabel = getOptionLabel(correctAnswer);
                
                incorrectAnswers.push({
                    questionNumber: questionNumber,
                    questionText: questionText,
                    userAnswer: userAnswer || '0',
                    userAnswerText: userAnswerText,
                    userAnswerLabel: userAnswerLabel,
                    correctAnswer: correctAnswer,
                    correctAnswerText: correctAnswerText,
                    correctAnswerLabel: correctAnswerLabel
                });
            }
        });
        
        // Display incorrect answers
        console.log('Incorrect answers found:', incorrectAnswers.length);
        displayIncorrectAnswers(incorrectAnswers);
        
        // Clear localStorage
        localStorage.removeItem("currentQ");
        localStorage.removeItem("score");
        localStorage.removeItem("quizStartTime");
        localStorage.removeItem("answers");
    }
    
    function displayIncorrectAnswers(incorrectAnswers) {
        const section = document.getElementById("incorrect-answers-section");
        const list = document.getElementById("incorrect-answers-list");
        
        if (!section || !list) {
            console.error('Incorrect answers section elements not found');
            return;
        }
        
        // Clear previous content
        list.innerHTML = '';
        
        if (incorrectAnswers.length === 0) {
            // Show message if all answers are correct
            section.style.display = 'block';
            list.innerHTML = `
                <div class="no-incorrect">
                    <p style="font-size: 20px; color: #28a745; font-weight: bold; margin-bottom: 10px;">🎉 Perfect Score!</p>
                    <p>You answered all questions correctly. Excellent work!</p>
                </div>
            `;
            return;
        }
        
        // Show section and display incorrect answers
        section.style.display = 'block';
        
        incorrectAnswers.forEach(item => {
            const answerItem = document.createElement('div');
            answerItem.className = 'incorrect-answer-item';
            answerItem.innerHTML = `
                <div class="incorrect-question-header">
                    <div class="incorrect-question-number">Question ${item.questionNumber}</div>
                </div>
                <div class="incorrect-question-text">${escapeHtml(item.questionText)}</div>
                <div class="answer-comparison">
                    <div class="answer-box your-answer-box">
                        <div class="answer-label your-answer-label">Your Answer</div>
                        <div class="answer-value">
                            <strong>${item.userAnswerLabel}.</strong> ${escapeHtml(item.userAnswerText)}
                        </div>
                    </div>
                    <div class="answer-box correct-answer-box">
                        <div class="answer-label correct-answer-label">Correct Answer</div>
                        <div class="answer-value">
                            <strong>${item.correctAnswerLabel}.</strong> ${escapeHtml(item.correctAnswerText)}
                        </div>
                    </div>
                </div>
            `;
            list.appendChild(answerItem);
        });
    }
    
    // Helper function to escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatTime(ms) {
        const m = Math.floor(ms / 60000);
        const s = Math.floor((ms % 60000) / 1000);
        return `${m}:${s < 10 ? "0" : ""}${s}`;
    }

    function updateTimer() {
        if (questions[current]) {
            const currentTypeId = questions[current].dataset.type;
            updateTimerForQuestionType(currentTypeId);
        } else {
            // Fallback to global timer
        const elapsed = Date.now() - startTime;
        const remaining = totalTime - elapsed;
        if (remaining <= 0) {
                submitExam();
            return;
            }
            const timeStr = formatTime(remaining);
            timerEl.textContent = `Time Left: ${timeStr}`;
            const sidebarTimer = document.getElementById('sidebar-timer');
            if (sidebarTimer) {
                sidebarTimer.textContent = `Time Left: ${timeStr}`;
            }
        }
    }

    // Handle option clicks
    allQuestionItems.forEach((q, index) => {
        q.querySelectorAll(".option-item").forEach(item => {
            item.addEventListener("click", () => {
                selectAnswer(index, item.dataset.value);
            });
        });
    });

    // Initialize - set current question type and start timer
    if (questions[0]) {
        currentQuestionTypeId = questions[0].dataset.type;
        const initialTimerMinutes = parseInt(questions[0].dataset.timer) || null;
        if (initialTimerMinutes && questionTypeTimers[currentQuestionTypeId]) {
            questionTypeStartTimes[currentQuestionTypeId] = Date.now();
            localStorage.setItem(`quizStartTime_${currentQuestionTypeId}`, questionTypeStartTimes[currentQuestionTypeId]);
        }
    }
    
    showQuestion(0);
    setInterval(updateTimer, 1000);
    
    // Audio control functions
    window.toggleAudio = function(audioId, button) {
        const audioElement = document.getElementById(audioId);
        if (!audioElement) return;
        
        if (audioElement.paused) {
            // Stop all other audios
            document.querySelectorAll('audio').forEach(a => {
                if (a !== audioElement && !a.paused) {
                    a.pause();
                    a.currentTime = 0;
                    // Reset all buttons
                    const pageId = a.id.replace('audio-player-', '');
                    resetAudioButton(pageId);
                }
            });
            
            audioElement.play();
            const pageId = audioId.replace('audio-player-', '');
            updatePlayButton(pageId, true);
        } else {
            audioElement.pause();
            const pageId = audioId.replace('audio-player-', '');
            updatePlayButton(pageId, false);
        }
    };
    
    window.updatePlayButton = function(pageId, isPlaying) {
        const button = document.getElementById('audio-btn-' + pageId);
        const playIcon = document.getElementById('play-icon-' + pageId);
        const pauseIcon = document.getElementById('pause-icon-' + pageId);
        
        if (!button || !playIcon || !pauseIcon) return;
        
        if (isPlaying) {
            button.classList.add('playing');
            playIcon.style.display = 'none';
            pauseIcon.style.display = 'block';
    } else {
            button.classList.remove('playing');
            playIcon.style.display = 'block';
            pauseIcon.style.display = 'none';
        }
    };
    
    window.resetAudioButton = function(pageId) {
        const button = document.getElementById('audio-btn-' + pageId);
        const playIcon = document.getElementById('play-icon-' + pageId);
        const pauseIcon = document.getElementById('pause-icon-' + pageId);
        
        if (!button || !playIcon || !pauseIcon) return;
        
        button.classList.remove('playing');
        playIcon.style.display = 'block';
        pauseIcon.style.display = 'none';
    };
    
    // Make functions globally accessible
    window.previousQuestion = function() {
        if (current > 0) {
            current--;
            showQuestion(current);
        }
    };
    
    window.nextQuestion = function() {
        if (current < totalQuestions - 1) {
            current++;
            showQuestion(current);
        }
    };
    
    window.submitExam = function() {
        if (!confirm("Are you sure you want to submit the exam? You cannot change your answers after submission.")) {
            return;
        }
        
        // Calculate score
        score = 0;
        questions.forEach((q, index) => {
            const correctAnswer = q.dataset.correct;
            const userAnswer = answers[index];
            if (userAnswer && userAnswer === correctAnswer) {
                score++;
            }
        });
        
        showResult();
    };
});
</script>

</body>
</html>
