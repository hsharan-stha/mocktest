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
        
        /* JFT-Basic: body */
        body { 
            font-family: "Shippori Mincho", "Yu Mincho", "YuMincho", "Hiragino Mincho ProN", "MS PMincho", "MS Mincho", serif;
            font-size: 100%;
            background-color: #E3DFE1;
            color: #333;
            line-height: 1.6;
            padding: 0;
            margin: 0;
            overflow: hidden;
            height: 100vh;
            word-break: break-all;
        }
        
        .exam-layout {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        
        /* JFT-Basic: Sidebar – match JFT proportions */
        .exam-sidebar {
            /*width: 220px;*/
            background-color: #F2F4F5;
            border-right: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            flex-shrink: 0;
        }
        
        .sidebar-header {
            display:none;
            padding: 16px 20px;
            background-color: #2d3748;
            color: #fff;
            border-bottom: none;
        }
        
        .sidebar-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        
        .sidebar-timer {
            font-size: 13px;
            opacity: 0.9;
        }
        
        .question-type-tabs {
            flex: 1;
            padding: 10px 0;
            background-color: #F2F4F5;
        }
        
        .type-tab {
            display: flex;
            align-items: center;
            width: 100%;
            min-height: 72px;
            padding: 12px 16px;
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
            background-color: #E3DFE1;
            border-left-color: #F79D52;
        }
        
        .type-tab.active {
            background-color: #FDEADA;
            border-left-color: #F79D52;
            color: #1a202c;
            font-weight: bold;
        }
        
        .type-tab:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }
        
        .type-tab > div {
            writing-mode: vertical-lr;
            text-orientation: mixed;
            margin-right: 8px;
        }
        
        .type-tab-count {
            /*display: none;*/
            background-color: #2d3748;
            color: #fff;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: normal;
            margin-left: auto;
        }
        
        .type-tab-timer {
            /*display: none;*/
            font-size: 11px;
            color: #666;
            margin-top: 4px;
            font-weight: normal;
        }
        
        .type-tab.active .type-tab-timer {
            color: #2d3748;
        }
        
        .type-tab.active .type-tab-count {
            background-color: #1a202c;
        }
        
        /* Main: JFT gray background */
        .exam-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            background-color: #E3DFE1;
        }
        
        /* JFT-Basic: Top bar – match JFT header exactly */
        .exam-header {
            background-color: #2d3748;
            color: #fff;
            padding: 12px 20px;
            flex-shrink: 0;
            border-bottom: none;
            height: 60px;
            display: flex;
            align-items: center;
        }
        
        .exam-header-content {
            /*max-width: 1024px;*/
            margin: 0 auto;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .exam-title {
            font-size: 16px;
            font-weight: bold;
            color: #fff;
        }
        
        .timer-display {
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            background-color: rgba(255,255,255,0.15);
            padding: 5px 10px;
            border-radius: 3px;
        }
        
        /* JFT-Basic: Main content area - centered 1024px */
        .exam-container {
            overflow:auto;
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            min-height: 0;
            /*max-width: 1024px;*/
            margin: 0 auto;
            width: 100%;
        }
        
        .question-container {
            background-color: #fff;
            padding: 30px 40px;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 0;
            max-height: 100%;
            border: 1px solid #ddd;
        }
        
        .question-header {
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 1px solid #ddd;
        }
        
        /* JFT: question number bg #E9F7FF */
        .question-number {
            font-size: 120%;
            font-weight: bold;
            color: #333;
            background-color: #E9F7FF;
            padding: 5px 10px;
            display: inline-block;
        }
        
        .question-text {
            font-size: 110%;
            line-height: 1.8;
            color: #333;
            margin-bottom: 24px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .question-media {
            margin: 24px 0;
            display: flex;
            flex-direction: column;
            gap: 16px;
            align-items: center;
        }
        
        .question-image-container {
            text-align: center;
            margin: 16px 0;
        }
        
        .question-image {
            max-width: 100%;
            max-height: 400px;
        }
        
        .audio-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 12px;
            background-color: #fff;
            border: 1px solid #ddd;
            margin: 16px 0;
        }
        
        .audio-play-button {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #2d3748;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        
        .audio-play-button:hover {
            background-color: #1a202c;
        }
        
        .audio-play-button.playing {
            background-color: #F79D52;
        }
        
        .audio-play-button svg {
            width: 24px;
            height: 24px;
        }
        
        .audio-label {
            font-size: 14px;
            color: #666;
        }
        
        /* JFT-Basic: options – border #000, hover #FDEADA / #F79D52 */
        .options-container {
            margin-top: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .option-item {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            background-color: #fff;
            border: solid 2px #000;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 110%;
            font-weight: bold;
        }
        
        .option-item:hover,
        .option-item.selected {
            background-color: #FDEADA;
            border: solid 2px #F79D52;
        }
        
        .option-label {
            font-weight: bold;
            color: #333;
            margin-right: 16px;
            min-width: 36px;
            font-size: 110%;
        }
        
        .option-text {
            flex: 1;
            font-size: 110%;
            color: #333;
            line-height: 1.6;
        }
        
        /* JFT-Basic: Bottom bar – full width, same as top/sidebar header */
        .navigation-section {
            background-color: #2d3748;
            color: #fff;
            padding: 12px 20px;
            flex-shrink: 0;
            margin-top: 0;
            border-top: none;
            height: 60px;
            display: flex;
            align-items: center;
        }
        
        .nav-inner {
            /*max-width: 1024px;*/
            margin: 0 auto;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .nav-button {
            padding: 10px 24px;
            font-size: 16px;
            font-weight: bold;
            border: none;
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
            background-color: #777;
            color: #fff;
        }
        
        .btn-previous:hover:not(:disabled) {
            background-color: #5a5a5a;
        }
        
        .btn-next {
            background-color: #F79D52;
            color: #fff;
        }
        
        .btn-next:hover:not(:disabled) {
            background-color: #e08b3d;
        }
        
        .btn-submit {
            background-color: #F79D52;
            color: #fff;
        }
        
        .btn-submit:hover:not(:disabled) {
            background-color: #e08b3d;
        }
        
        .question-indicator {
            font-weight: bold;
            color: #fff;
            font-size: 15px;
        }
        
        /* Result Page – JFT table style */
        #result {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        .result-container {
            background-color: #fff;
            padding: 32px 40px;
            text-align: center;
            min-height: 100%;
            border: 1px solid #ddd;
        }
        
        .result-header {
            margin-bottom: 24px;
        }
        
        .result-title {
            font-size: 28px;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 8px;
        }
        
        .result-score {
            font-size: 64px;
            font-weight: bold;
            color: #2d3748;
            margin: 16px 0;
        }
        
        .result-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin: 24px 0;
        }
        
        .stat-box {
            background-color: #F2F4F5;
            padding: 20px;
            border: 1px solid #ddd;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
        }
        
        .stat-correct .stat-value {
            color: #276749;
        }
        
        .stat-incorrect .stat-value {
            color: #c53030;
        }
        
        .result-actions {
            margin-top: 32px;
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        
        .result-actions .nav-button {
            padding: 10px 24px;
        }
        
        .incorrect-answers-section {
            margin-top: 32px;
            text-align: left;
        }
        
        .incorrect-answers-title {
            font-size: 20px;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #ddd;
        }
        
        .incorrect-answer-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-left: 4px solid #F79D52;
            padding: 16px;
            margin-bottom: 16px;
        }
        
        .incorrect-question-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .incorrect-question-number {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
        }
        
        .incorrect-question-text {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
            flex: 1;
            min-width: 200px;
        }
        
        .incorrect-answer-image {
            margin: 12px 0;
            text-align: center;
        }
        
        .incorrect-answer-image img {
            max-width: 100%;
            max-height: 300px;
            border: 1px solid #ddd;
        }
        
        .answer-comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 12px;
        }
        
        .answer-box {
            padding: 12px;
            border: 1px solid #ddd;
        }
        
        .your-answer-box {
            background-color: #FDEADA;
            border-color: #F79D52;
        }
        
        .correct-answer-box {
            background-color: #E9F7FF;
            border-color: #2d3748;
        }
        
        .answer-label {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 6px;
        }
        
        .your-answer-label {
            color: #c05621;
        }
        
        .correct-answer-label {
            color: #2d3748;
        }
        
        .answer-value {
            font-size: 15px;
            color: #333;
            font-weight: 500;
        }
        
        .no-incorrect {
            text-align: center;
            padding: 32px;
            color: #666;
            font-size: 16px;
        }
        
        .hidden {
            display: none !important;
        }
        
        .question-section-header {
            font-size: 110%;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ddd;
        }
        
        @media (max-width: 768px) {
            .exam-layout {
                flex-direction: column;
            }
            
            .exam-sidebar {
                width: 100%;
                max-height: 200px;
                border-right: none;
                border-bottom: 1px solid #ddd;
            }
            
            .sidebar-header {
                background-color: #2d3748;
            }
            
            .question-type-tabs {
                display: flex;
                overflow-x: auto;
                padding: 8px;
            }
            
            .type-tab {
                min-width: 140px;
                min-height: 60px;
            }
            .type-tab > div {
                writing-mode: vertical-lr;
            }
            
            .exam-header {
                height: auto;
                padding: 10px 15px;
            }
            
            .exam-header-content {
                max-width: 100%;
            }
            
            .exam-container {
                padding: 12px;
            }
            
            .question-container {
                padding: 20px;
            }
            
            .question-text {
                font-size: 100%;
            }
            
            .option-text {
                font-size: 100%;
            }
            
            .answer-comparison {
                grid-template-columns: 1fr;
            }
            
            .navigation-section {
                height: auto;
                padding: 10px 15px;
                flex-direction: column;
            }
            
            .nav-inner {
                max-width: 100%;
            }
            
            .nav-button {
                width: 100%;
            }
        }
        /* Examinee Info Modal */
        .examinee-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            overflow: auto;
        }
        
        .examinee-modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 30px;
            border: 1px solid #ddd;
            width: 90%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .examinee-modal-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #2d3748;
            border-bottom: 2px solid #F79D52;
            padding-bottom: 10px;
        }
        
        .examinee-form-group {
            margin-bottom: 20px;
        }
        
        .examinee-form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .examinee-form-group input,
        .examinee-form-group textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        
        .examinee-form-group input:focus,
        .examinee-form-group textarea:focus {
            outline: none;
            border-color: #F79D52;
        }
        
        .examinee-form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .examinee-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 30px;
        }
        
        .btn-start-exam {
            background-color: #F79D52;
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .btn-start-exam:hover {
            background-color: #e08b3d;
        }
        
        .btn-start-exam:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <!-- Examinee Information Modal -->
    <div id="examineeModal" class="examinee-modal">
        <div class="examinee-modal-content">
            <div class="examinee-modal-header">Examinee Information</div>
            <form id="examineeForm">
                <div class="examinee-form-group">
                    <label for="examinee_name">Name <span style="color: red;">*</span></label>
                    <input type="text" id="examinee_name" name="examinee_name" required>
                </div>
                <div class="examinee-form-group">
                    <label for="examinee_email">Email <span style="color: red;">*</span></label>
                    <input type="email" id="examinee_email" name="examinee_email" required>
                </div>
                <div class="examinee-form-group">
                    <label for="examinee_phone">Phone</label>
                    <input type="text" id="examinee_phone" name="examinee_phone">
                </div>
                <div class="examinee-form-group">
                    <label for="examinee_notes">Additional Notes</label>
                    <textarea id="examinee_notes" name="examinee_notes" placeholder="Any additional information..."></textarea>
                </div>
                <div class="examinee-modal-actions">
                    <button type="submit" class="btn-start-exam" id="btnStartExam">Start Exam</button>
                </div>
            </form>
        </div>
    </div>

    <div class="exam-layout" id="examLayout" style="display: none;">
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
            <!-- Header - JFT-Basic style -->
            <div class="exam-header">
                <div class="exam-header-content">
                    <div class="exam-title">Japanese Language Mock Test</div>
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
                                $subSection = $subSectionGroup['sub_section'];
                                $subSectionPages = $subSectionGroup['pages'];
                            @endphp
                            
                            @foreach ($subSectionPages as $page)
                                <div class="question-container quiz-question hidden"
                                     data-id="{{ $page->id }}"
                                     data-correct="{{ $page->shuffled_correct_answer ?? $page->correct_answer }}"
                                     data-index="{{ $globalIndex }}"
                                     data-type="{{ $typeGroup['type']->id }}"
                                     data-sub-section="{{ $subSection ? $subSection->id : '' }}"
                                     data-timer="{{ $typeGroup['type']->timer ?? '' }}"
                                     data-page-image="{{ $page->page_image ? asset($page->page_image) : '' }}">
                                    
                                    <!-- Question Header - JFT Style -->
                                    <div class="question-header">
                                        <div class="question-number">Question {{ $globalIndex + 1 }}</div>
                                    </div>
                                    
                                    @if($subSection)
                                        @php
                                            $isFirst = ($page === reset($subSectionPages));
                                        @endphp
                                        @if($isFirst)
                                            <div class="question-section-header">{{ $subSection->name }}</div>
                                        @endif
                                    @endif
                                    
                                    <div class="question-text">{!! $page->question !!}</div>
                                    
                                    <!-- Question Media (Image and Audio) - JFT Style -->
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
                                            <div class="audio-container">
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
                                                <div class="audio-label">音声を聞く</div>
                                                <audio id="audio-player-{{ $page->id }}" 
                                                       preload="none"
                                                       style="display: none;"
                                                       onplay="updatePlayButton('{{ $page->id }}', true)"
                                                       onpause="updatePlayButton('{{ $page->id }}', false)"
                                                       onended="resetAudioButton('{{ $page->id }}')">
                                                    <source src="{{ asset($page->page_html) }}" type="audio/mpeg">
                                                    <source src="{{ asset($page->page_html) }}" type="audio/wav">
                                                    <source src="{{ asset($page->page_html) }}" type="audio/ogg">
                                                </audio>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Options - JFT Style -->
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
                                                <div class="option-item" data-value="{{ $i+1 }}" onclick="selectAnswer({{ $globalIndex }}, {{ $i+1 }})">
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
                        @endforeach
                    @endforeach
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

            <!-- Bottom bar - JFT style (full width, same as top) -->
            <div class="navigation-section">
                <div class="nav-inner">
                   
                    <div class="question-indicator" id="question-indicator">
                        Question 1 of {{ count($pages) }}
                    </div>
                    <div>
                     <button class="nav-button btn-previous" id="btn-previous" onclick="previousQuestion()" disabled>Previous</button>
                    <button class="nav-button btn-next" id="btn-next" onclick="nextQuestion()">Next</button>
                    
                    <button class="nav-button btn-submit hidden" id="btn-submit" onclick="submitExam()">Submit Exam</button></div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const questions = document.querySelectorAll(".quiz-question");
    const timerEl = document.getElementById("timer");
    const resultEl = document.getElementById("result");

    const totalQuestions = questions.length;
    
    // State
    let current = 0;
    let score = 0;
    let answers = {}; // Store user answers: {questionIndex: selectedValue}
    let examSubmitted = false;
    let timerIntervalId = null;
    let examAttemptId = null; // Store exam attempt ID after examinee info is submitted
    let examStartTime = null; // Track when exam actually started
    let timeSpentByType = {}; // Track time spent per question type

    // Track current question type for timer switching
    let currentQuestionTypeId = null;

    // Per-question-type timer setup
    const questionTypeTimers = {};
    const questionTypeStartTimes = {};
    
    // Track question type boundaries and completed types
    const questionTypeBoundaries = {}; // {typeId: {firstIndex: number, lastIndex: number}}
    const completedQuestionTypes = new Set(); // Track which question types have been completed
    const expiredQuestionTypes = new Set(); // Track which question types timed out
    
    // Build question type boundaries
    questions.forEach((q, index) => {
        const typeId = q.dataset.type;
        if (!questionTypeBoundaries[typeId]) {
            questionTypeBoundaries[typeId] = {
                firstIndex: index,
                lastIndex: index
            };
        } else {
            questionTypeBoundaries[typeId].lastIndex = index;
        }
    });
    
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
        // Track time spent on previous question type
        if (currentQuestionTypeId && typeStartTimes[currentQuestionTypeId]) {
            const timeSpent = Date.now() - typeStartTimes[currentQuestionTypeId];
            if (!timeSpentByType[currentQuestionTypeId]) {
                timeSpentByType[currentQuestionTypeId] = 0;
            }
            timeSpentByType[currentQuestionTypeId] += timeSpent;
        }
        
        // Hide all questions
        questions.forEach((q, i) => {
            q.classList.toggle("hidden", i !== index);
        });
        
        // Show current question
        if (questions[index]) {
            const questionType = questions[index].dataset.type;
            
            // Check if we're switching to a different question type
            if (currentQuestionTypeId !== questionType) {
                // Start tracking time for new question type
                typeStartTimes[questionType] = Date.now();
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
        if (examSubmitted) return;
        const timerMinutes = questionTypeTimers[typeId] ? questionTypeTimers[typeId] / 60000 : null;
        
        if (timerMinutes) {
            // Use per-question-type timer
            const startTime = parseInt(localStorage.getItem(`quizStartTime_${typeId}`)) || questionTypeStartTimes[typeId];
            const elapsed = Date.now() - startTime;
            const remaining = questionTypeTimers[typeId] - elapsed;
            
            if (remaining <= 0) {
                handleQuestionTypeTimeout(typeId);
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
                if (examSubmitted) return;
                if (timerIntervalId) clearInterval(timerIntervalId);
                submitExam(true);
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
            const tabTypeId = tab.dataset.type;
            
            // Disable tabs for completed question types
            if (completedQuestionTypes.has(tabTypeId) || expiredQuestionTypes.has(tabTypeId)) {
                tab.style.opacity = '0.5';
                tab.style.cursor = 'not-allowed';
                tab.disabled = true;
            } else {
                tab.style.opacity = '1';
                tab.style.cursor = 'pointer';
                tab.disabled = false;
            }
            
            if (tabTypeId == typeId) {
                tab.classList.add('active');
            }
        });
    }
    
    function switchToQuestionType(typeId) {
        // Prevent going back to completed question types
        if (completedQuestionTypes.has(typeId) || expiredQuestionTypes.has(typeId)) {
            alert("You cannot go back to a completed question type. You can only navigate within the current question type.");
            return;
        }
        
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

    function getFirstQuestionIndexForType(typeId) {
        return questionTypeBoundaries[typeId] ? questionTypeBoundaries[typeId].firstIndex : -1;
    }

    function getNextAvailableQuestionTypeId(currentTypeId) {
        const typeIds = Object.keys(questionTypeBoundaries);
        const currentTypePosition = typeIds.indexOf(String(currentTypeId));

        for (let i = currentTypePosition + 1; i < typeIds.length; i++) {
            const nextTypeId = typeIds[i];
            if (!completedQuestionTypes.has(nextTypeId) && !expiredQuestionTypes.has(nextTypeId)) {
                return nextTypeId;
            }
        }

        for (let i = 0; i < typeIds.length; i++) {
            const nextTypeId = typeIds[i];
            if (!completedQuestionTypes.has(nextTypeId) && !expiredQuestionTypes.has(nextTypeId)) {
                return nextTypeId;
            }
        }

        return null;
    }

    function handleQuestionTypeTimeout(typeId) {
        if (examSubmitted || expiredQuestionTypes.has(typeId)) {
            return;
        }

        expiredQuestionTypes.add(String(typeId));
        completedQuestionTypes.add(String(typeId));

        const typeTimerEl = document.getElementById(`type-timer-${typeId}`);
        if (typeTimerEl) {
            typeTimerEl.textContent = 'Timer: 0:00';
        }

        const nextTypeId = getNextAvailableQuestionTypeId(typeId);

        if (nextTypeId) {
            const nextIndex = getFirstQuestionIndexForType(nextTypeId);
            if (nextIndex !== -1) {
                current = nextIndex;
                currentQuestionTypeId = null;
                showQuestion(current);
                return;
            }
        }

        if (timerIntervalId) clearInterval(timerIntervalId);
        submitExam(true);
    }
    
    function updateNavigationButtons(index) {
        const btnPrevious = document.getElementById("btn-previous");
        const btnNext = document.getElementById("btn-next");
        const btnSubmit = document.getElementById("btn-submit");
        
        // Get current question type
        const currentTypeId = questions[index].dataset.type;
        const currentTypeBoundary = questionTypeBoundaries[currentTypeId];
        
        // Check if previous question is in the same type
        let canGoPrevious = false;
        if (index > 0) {
            const previousTypeId = questions[index - 1].dataset.type;
            // Can only go previous if previous question is in the same type
            canGoPrevious = (previousTypeId === currentTypeId);
        }
        
        btnPrevious.disabled = !canGoPrevious || index === 0;
        
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
    
    window.selectAnswer = function(questionIndex, value) {
        answers[questionIndex] = value;
        localStorage.setItem("answers", JSON.stringify(answers));
        
        // Update visual selection
        const question = questions[questionIndex];
        if (question) {
            question.querySelectorAll(".option-item").forEach(item => {
                item.classList.toggle("selected", item.dataset.value == value);
            });
        }
    };
    
    window.previousQuestion = function() {
        if (current > 0) {
            const currentTypeId = questions[current].dataset.type;
            const previousTypeId = questions[current - 1].dataset.type;
            
            // Only allow going to previous question if it's in the same question type
            if (previousTypeId === currentTypeId) {
                current--;
                showQuestion(current);
            } else {
                // Prevent navigation to different question type
                alert("You cannot go back to previous question types. You can only navigate within the current question type.");
            }
        }
    };
    
    window.nextQuestion = function() {
        if (current < totalQuestions - 1) {
            const currentTypeId = questions[current].dataset.type;
            const currentTypeBoundary = questionTypeBoundaries[currentTypeId];
            const nextTypeId = questions[current + 1].dataset.type;
            
            // Check if we're moving from the last question of current type to a different type
            if (current === currentTypeBoundary.lastIndex && nextTypeId !== currentTypeId) {
                // Mark this question type as completed before moving to next type
                completedQuestionTypes.add(currentTypeId);
                updateActiveTab(nextTypeId); // Update tabs to disable completed type
            }
            
            current++;
            showQuestion(current);
        }
    };
    
    window.submitExam = async function(skipConfirm) {
        if (examSubmitted) return;
        if (!skipConfirm && !confirm("Are you sure you want to submit the exam? You cannot change your answers after submission.")) {
            return;
        }
        examSubmitted = true;
        if (timerIntervalId) clearInterval(timerIntervalId);
        
        // Track final time spent on current question type
        if (currentQuestionTypeId && typeStartTimes[currentQuestionTypeId]) {
            const timeSpent = Date.now() - typeStartTimes[currentQuestionTypeId];
            if (!timeSpentByType[currentQuestionTypeId]) {
                timeSpentByType[currentQuestionTypeId] = 0;
            }
            timeSpentByType[currentQuestionTypeId] += timeSpent;
        }
        
        // Calculate score
        score = 0;
        const examAnswers = [];
        
        questions.forEach((q, index) => {
            const correctAnswer = String(q.dataset.correct || '').trim();
            const userAnswer = answers[index] ? String(answers[index]).trim() : null;
            // Compare as strings to handle both integer and string values
            const isCorrect = userAnswer && userAnswer === correctAnswer;
            
            if (isCorrect) {
                score++;
            }
            
            // Collect answer data for submission
            const questionText = q.querySelector('.question-text') ? q.querySelector('.question-text').textContent.trim() : '';
            const option1 = q.querySelector('[data-value="1"] .option-text') ? q.querySelector('[data-value="1"] .option-text').textContent.trim() : '';
            const option2 = q.querySelector('[data-value="2"] .option-text') ? q.querySelector('[data-value="2"] .option-text').textContent.trim() : '';
            const option3 = q.querySelector('[data-value="3"] .option-text') ? q.querySelector('[data-value="3"] .option-text').textContent.trim() : '';
            const option4 = q.querySelector('[data-value="4"] .option-text') ? q.querySelector('[data-value="4"] .option-text').textContent.trim() : '';
            
            examAnswers.push({
                page_id: parseInt(q.dataset.id),
                question_type_id: parseInt(q.dataset.type),
                sub_section_id: q.dataset.subSection ? parseInt(q.dataset.subSection) : null,
                question_text: questionText,
                selected_answer: userAnswer,
                correct_answer: correctAnswer,
                is_correct: isCorrect,
                option1: option1,
                option2: option2,
                option3: option3,
                option4: option4,
                question_number: index + 1,
            });
        });
        
        const totalQuestions = questions.length;
        const incorrect = totalQuestions - score;
        const percentage = (score / totalQuestions * 100).toFixed(2);
        
        // Submit exam results to server
        if (examAttemptId) {
            try {
                const response = await fetch('/exam/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        exam_attempt_id: examAttemptId,
                        answers: examAnswers,
                        total_questions: totalQuestions,
                        correct_answers: score,
                        incorrect_answers: incorrect,
                        score_percentage: percentage,
                        time_spent_by_type: timeSpentByType,
                    })
                });
                
                const data = await response.json();
                if (!data.success) {
                    console.error('Failed to save exam results:', data.message);
                }
            } catch (error) {
                console.error('Error submitting exam:', error);
            }
        }
        
        showResult();
    };
    
    function getOptionText(question, optionValue) {
        // Convert to string for comparison
        const valueStr = String(optionValue || '').trim();
        const optionItem = question.querySelector(`[data-value="${valueStr}"]`);
        if (optionItem) {
            return optionItem.querySelector('.option-text').textContent.trim();
        }
        return '';
    }
    
    function getOptionLabel(optionValue) {
        // Convert to integer for label calculation (A, B, C, D)
        const valueInt = parseInt(optionValue) || 0;
        return String.fromCharCode(64 + valueInt);
    }

    function showResult() {
        // Hide quiz, top bar, bottom bar (JFT result = content only)
        document.querySelector("#quiz").classList.add("hidden");
        document.querySelector(".exam-header").classList.add("hidden");
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
        questions.forEach((q, index) => {
            const correctAnswer = String(q.dataset.correct || '').trim();
            const userAnswer = answers[index] ? String(answers[index]).trim() : null;
            const questionTextEl = q.querySelector('.question-text');
            const questionText = questionTextEl ? questionTextEl.textContent.trim() : 'Question ' + (index + 1);
            const questionNumber = index + 1;
            
            // Check if answer is incorrect or not answered (compare as strings)
            if (!userAnswer || userAnswer !== correctAnswer) {
                const userAnswerText = userAnswer ? getOptionText(q, userAnswer) : 'Not answered';
                const correctAnswerText = getOptionText(q, correctAnswer);
                const userAnswerLabel = userAnswer ? getOptionLabel(userAnswer) : 'N/A';
                const correctAnswerLabel = getOptionLabel(correctAnswer);
                const questionImageUrl = q.dataset.pageImage || '';
                
                incorrectAnswers.push({
                    questionNumber: questionNumber,
                    questionText: questionText,
                    questionImageUrl: questionImageUrl,
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
            const imageBlock = item.questionImageUrl
                ? `<div class="incorrect-answer-image"><img src="${escapeHtml(item.questionImageUrl)}" alt="Question image" onerror="this.style.display='none';"></div>`
                : '';
            answerItem.innerHTML = `
                <div class="incorrect-question-header">
                    <div class="incorrect-question-number">Question ${item.questionNumber}</div>
                </div>
                <div class="incorrect-question-text">${escapeHtml(item.questionText)}</div>
                ${imageBlock}
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
        if (examSubmitted) return;
        if (questions[current]) {
            const currentTypeId = questions[current].dataset.type;
            updateTimerForQuestionType(currentTypeId);
        } else {
            // Fallback to global timer
            const elapsed = Date.now() - startTime;
            const remaining = totalTime - elapsed;
            if (remaining <= 0) {
                if (examSubmitted) return;
                if (timerIntervalId) clearInterval(timerIntervalId);
                submitExam(true);
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
    
    // Track time spent per question type
    let typeStartTimes = {};
    
    function startExam() {
        examStartTime = Date.now();
        document.getElementById('examineeModal').style.display = 'none';
        document.getElementById('examLayout').style.display = 'flex';
        
        // Initialize - set current question type and start timer
        if (questions[0]) {
            currentQuestionTypeId = questions[0].dataset.type;
            typeStartTimes[currentQuestionTypeId] = Date.now();
            const initialTimerMinutes = parseInt(questions[0].dataset.timer) || null;
            if (initialTimerMinutes && questionTypeTimers[currentQuestionTypeId]) {
                questionTypeStartTimes[currentQuestionTypeId] = Date.now();
                localStorage.setItem(`quizStartTime_${currentQuestionTypeId}`, questionTypeStartTimes[currentQuestionTypeId]);
            }
        }
        
        showQuestion(0);
        timerIntervalId = setInterval(updateTimer, 1000);
    }
    
    // Handle examinee form submission
    document.getElementById('examineeForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = {
            examinee_name: document.getElementById('examinee_name').value,
            examinee_email: document.getElementById('examinee_email').value,
            examinee_phone: document.getElementById('examinee_phone').value,
            examinee_notes: document.getElementById('examinee_notes').value,
            book_id: {{ $book_id }},
        };
        
        try {
            const response = await fetch('/exam/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            if (data.success && data.exam_attempt_id) {
                examAttemptId = data.exam_attempt_id;
                startExam();
            } else {
                alert('Failed to start exam. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    });
    
    // Show examinee modal on page load
    document.getElementById('examineeModal').style.display = 'block';
    
    // Warn user before refreshing or leaving the page
    window.addEventListener('beforeunload', function(e) {
        // Only show warning if exam is not submitted
        if (!examSubmitted) {
            // Standard way to show browser's default warning dialog
            e.preventDefault();
            // For modern browsers
            e.returnValue = '';
            // Custom message (some browsers may not show this, but it's good to have)
            return 'If you refresh, your count or examination will decrease. Do not refresh unless needed.';
        }
    });
});
</script>

</body>
</html>
