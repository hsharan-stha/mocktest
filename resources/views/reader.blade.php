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
        
        /* Modern color scheme */
        :root {
            --primary-color: #0066cc;
            --primary-dark: #0052a3;
            --secondary-color: #00a8e8;
            --accent-color: #ff6b35;
            --success-color: #28a745;
            --error-color: #dc3545;
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-tertiary: #e9ecef;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --border-color: #dee2e6;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        body { 
            font-family: Shippori Mincho, Yu Mincho, YuMincho, Hiragino Mincho ProN, MS PMincho, MS Mincho, serif;
            font-size: 16px;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            line-height: 1.6;
            padding: 0;
            margin: 0;
            overflow: hidden;
            height: 100vh;
            word-break: break-word;
        }
        
        .exam-layout {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        
        /* Modern Sidebar */
        .exam-sidebar {
            background-color: var(--bg-primary);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            flex-shrink: 0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.08);
        }
        
        .sidebar-header {
            display: none;
            padding: 16px 20px;
            background-color: var(--primary-color);
            color: #fff;
            border-bottom: none;
        }
        
        .sidebar-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .sidebar-timer {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .question-type-tabs {
            flex: 1;
            padding: 8px 0;
            background-color: var(--bg-primary);
        }
        
        .type-tab {
            display: flex;
            align-items: center;
            width: 100%;
            min-height: 68px;
            padding: 12px 16px;
            background: none;
            border: none;
            border-left: 3px solid transparent;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
        }
        
        .type-tab:hover:not(:disabled) {
            background-color: var(--bg-secondary);
            border-left-color: var(--secondary-color);
            color: var(--text-primary);
        }
        
        .type-tab.active {
            background: linear-gradient(90deg, rgba(0, 102, 204, 0.1) 0%, transparent 100%);
            border-left-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .type-tab:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }
        
        .type-tab > div {
            writing-mode: vertical-lr;
            text-orientation: mixed;
            margin-right: 8px;
            font-size: 13px;
        }
        
        .type-tab-count {
            background-color: var(--primary-color);
            color: #fff;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            margin-left: auto;
        }
        
        .type-tab-timer {
            font-size: 11px;
            color: var(--text-secondary);
            margin-top: 4px;
            font-weight: 500;
        }
        
        .type-tab.active .type-tab-timer {
            color: var(--primary-color);
        }
        
        .type-tab.active .type-tab-count {
            background-color: var(--primary-dark);
        }
        
        /* Main Content Area */
        .exam-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            background-color: var(--bg-secondary);
        }
        
        /* Modern Header */
        .exam-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: var(--shadow-md);
            color: #fff;
            padding: 14px 24px;
            flex-shrink: 0;
            border-bottom: none;
            height: 64px;
            display: flex;
            align-items: center;
        }
        
        .exam-header-content {
            margin: 0 auto;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .exam-title {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.3px;
        }
        
        .timer-display {
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Main Content Area */
        .exam-container {
            overflow: auto;
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 24px;
            min-height: 0;
            margin: 0 auto;
            width: 100%;
        }
        
        /* Question Container - Modern Card */
        .question-container {
            background-color: var(--bg-primary);
            padding: 40px;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 0;
            max-height: 100%;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.3s ease;
        }
        
        .question-container:hover {
            box-shadow: var(--shadow-md);
        }
        
        .question-header {
            margin-bottom: 28px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--border-color);
        }
        
        .question-number {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 8px 14px;
            display: inline-block;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }
        
        .question-text {
            font-size: 28px;
            line-height: 1.8;
            color: var(--text-primary);
            margin-bottom: 28px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            font-weight: 500;
        }
        
        .question-media {
            margin: 28px 0;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
            justify-content: center;
        }
        
        .question-image-container {
            text-align: center;
            margin: 20px 0;
            width: 500px;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
        }
        
        .question-image {
            width: 500px;
            height: 500px;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 6px;
        }
        
        .audio-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            padding: 20px;
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin: 20px 0;
            min-height: 500px;
        }
        
        .audio-play-button {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            flex-shrink: 0;
            box-shadow: var(--shadow-md);
        }
        
        .audio-play-button:hover {
            transform: scale(1.08);
            box-shadow: var(--shadow-lg);
        }
        
        .audio-play-button:active {
            transform: scale(0.95);
        }
        
        .audio-play-button.playing {
            background: linear-gradient(135deg, var(--accent-color) 0%, #ff8c5a 100%);
        }
        
        .audio-play-button svg {
            width: 28px;
            height: 28px;
        }
        
        .audio-label {
            font-size: 15px;
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        /* Modern Options */
        .options-container {
            margin-top: 28px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        
        .option-item {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            background-color: var(--bg-primary);
            border: 2px solid var(--border-color);
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 28px;
            font-weight: 500;
            border-radius: 8px;
        }
        
        .option-item:hover {
            border-color: var(--primary-color);
            background-color: var(--bg-secondary);
            transform: translateX(4px);
            box-shadow: var(--shadow-sm);
        }
        
        .option-item.selected {
            background: linear-gradient(135deg, rgba(0, 102, 204, 0.1) 0%, rgba(0, 168, 232, 0.05) 100%);
            border-color: var(--primary-color);
            box-shadow: inset 0 0 0 1px var(--primary-color);
        }
        
        .option-label {
            font-weight: 700;
            color: var(--primary-color);
            margin-right: 20px;
            min-width: 44px;
            font-size: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background-color: var(--bg-secondary);
            border-radius: 6px;
        }
        
        .option-item.selected .option-label {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: #fff;
        }

        .option-text {
            flex: 1;
            font-size: 28px;
            color: var(--text-primary);
            line-height: 1.6;
        }
        
        /* Modern Bottom Navigation */
        .navigation-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
            color: #fff;
            padding: 14px 24px;
            flex-shrink: 0;
            margin-top: 0;
            border-top: none;
            height: 68px;
            display: flex;
            align-items: center;
        }
        
        .nav-inner {
            margin: 0 auto;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .nav-button {
            padding: 10px 24px;
            font-size: 15px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-block;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }
        
        .nav-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .btn-previous {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-previous:hover:not(:disabled) {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateX(-2px);
        }
        
        .btn-next {
            background-color: rgba(255, 255, 255, 0.9);
            color: var(--primary-color);
            font-weight: 700;
        }
        
        .btn-next:hover:not(:disabled) {
            background-color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .btn-submit {
            background-color: var(--accent-color);
            color: #fff;
            font-weight: 700;
        }
        
        .btn-submit:hover:not(:disabled) {
            background-color: #ff5520;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }
        
        .question-indicator {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
        }
        
        /* Modern Result Page */
        #result {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        .result-container {
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
            padding: 48px;
            text-align: center;
            min-height: 100%;
            border-radius: 12px;
        }
        
        .result-header {
            margin-bottom: 32px;
        }
        
        .result-title {
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
        }
        
        .result-score {
            font-size: 72px;
            font-weight: 800;
            color: var(--primary-color);
            margin: 24px 0;
            text-shadow: 0 2px 4px rgba(0, 102, 204, 0.2);
        }
        
        .result-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 32px 0;
        }
        
        .stat-box {
            background-color: var(--bg-primary);
            padding: 28px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }
        
        .stat-box:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        .stat-correct .stat-value {
            color: var(--success-color);
        }
        
        .stat-incorrect .stat-value {
            color: var(--error-color);
        }
        
        .result-actions {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        
        .result-actions .nav-button {
            padding: 12px 32px;
            font-size: 15px;
            background-color: var(--primary-color);
            color: #fff;
        }
        
        .result-actions .nav-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .incorrect-answers-section {
            margin-top: 40px;
            text-align: left;
        }
        
        .incorrect-answers-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .incorrect-answer-item {
            background-color: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-left: 4px solid var(--accent-color);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }
        
        .incorrect-answer-item:hover {
            box-shadow: var(--shadow-md);
        }
        
        .incorrect-question-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .incorrect-question-number {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .incorrect-question-text {
            font-size: 16px;
            color: var(--text-primary);
            line-height: 1.6;
            flex: 1;
            min-width: 200px;
        }
        
        .incorrect-answer-image {
            margin: 16px 0;
            text-align: center;
        }
        
        .incorrect-answer-image img {
            max-width: 100%;
            max-height: 300px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
        }
        
        .answer-comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 16px;
        }
        
        .answer-box {
            padding: 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--bg-secondary);
        }
        
        .your-answer-box {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.08) 0%, rgba(255, 107, 53, 0.05) 100%);
            border-color: var(--error-color);
        }
        
        .correct-answer-box {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.08) 0%, rgba(0, 168, 232, 0.05) 100%);
            border-color: var(--success-color);
        }
        
        .answer-label {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .your-answer-label {
            color: var(--error-color);
        }
        
        .correct-answer-label {
            color: var(--success-color);
        }
        
        .answer-value {
            font-size: 15px;
            color: var(--text-primary);
            font-weight: 600;
        }
        
        .no-incorrect {
            text-align: center;
            padding: 32px;
            color: var(--text-secondary);
            font-size: 16px;
        }
        
        .hidden {
            display: none !important;
        }
        
        .question-section-header {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--primary-color);
        }
        
        /* Responsive Media Queries */
        @media (max-width: 1024px) {
            .question-text {
                font-size: 26px;
            }
            
            .option-item {
                font-size: 26px;
            }
            
            .question-image-container,
            .question-image {
                width: 400px;
                height: 400px;
            }
        }
        
        @media (max-width: 768px) {
            .exam-layout {
                flex-direction: column;
            }
            
            .exam-sidebar {
                width: 100%;
                max-height: 140px;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }
            
            .question-type-tabs {
                display: flex;
                overflow-x: auto;
                padding: 8px;
                scroll-behavior: smooth;
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
                padding: 12px 16px;
            }
            
            .exam-header-content {
                max-width: 100%;
                flex-direction: column;
                gap: 12px;
            }
            
            .exam-title {
                font-size: 16px;
            }
            
            .exam-container {
                padding: 16px;
            }
            
            .question-container {
                padding: 24px;
            }
            
            .question-text,
            .option-item,
            .option-label,
            .option-text {
                font-size: 24px;
            }
            
            .question-media {
                flex-direction: column;
            }
            
            .question-image-container,
            .question-image {
                width: min(100%, 400px);
                height: auto;
            }
            
            .audio-container {
                min-height: auto;
                width: 100%;
            }
            
            .answer-comparison {
                grid-template-columns: 1fr;
            }
            
            .navigation-section {
                height: auto;
                padding: 12px 16px;
                flex-direction: column;
            }
            
            .nav-inner {
                max-width: 100%;
                flex-direction: column;
            }
            
            .nav-button {
                width: 100%;
                text-align: center;
            }
            
            .result-container {
                padding: 32px 16px;
            }
            
            .result-stats {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .question-text {
                font-size: 20px;
            }
            
            .option-item {
                font-size: 20px;
                padding: 12px 14px;
            }
            
            .option-label {
                margin-right: 12px;
                font-size: 20px;
                width: 36px;
                height: 36px;
            }
            
            .question-container {
                padding: 16px;
            }
            
            .exam-title {
                font-size: 14px;
            }
            
            .result-title {
                font-size: 24px;
            }
            
            .result-score {
                font-size: 48px;
            }
        }
        
        /* Modal Styling */
        .examinee-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            overflow: auto;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .examinee-modal-content {
            background-color: var(--bg-primary);
            margin: 8vh auto;
            padding: 40px;
            border: none;
            width: 90%;
            max-width: 500px;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .examinee-modal-header {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 24px;
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 12px;
        }
        
        .examinee-form-group {
            margin-bottom: 20px;
        }
        
        .examinee-form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .examinee-form-group input,
        .examinee-form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.25s ease;
            font-family: inherit;
        }
        
        .examinee-form-group input:focus,
        .examinee-form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }
        
        .examinee-form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .examinee-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 32px;
        }
        
        .btn-start-exam {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: #fff;
            padding: 12px 32px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-start-exam:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-start-exam:disabled {
            background-color: var(--border-color);
            cursor: not-allowed;
            transform: none;
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
