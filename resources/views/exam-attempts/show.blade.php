<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Exam Attempt Details') }} #{{ $examAttempt->id }}
            </h2>
            <a href="{{ route('exam-attempts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Examinee Information -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Examinee Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Name:</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $examAttempt->examinee_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $examAttempt->examinee_email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone:</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $examAttempt->examinee_phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Book:</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $examAttempt->book->title ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Started At:</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $examAttempt->started_at ? $examAttempt->started_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed At:</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $examAttempt->completed_at ? $examAttempt->completed_at->format('Y-m-d H:i:s') : 'In Progress' }}</p>
                    </div>
                    @if($examAttempt->examinee_notes)
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes:</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $examAttempt->examinee_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Exam Results Summary -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Exam Results</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Questions</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $examAttempt->total_questions }}</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900 p-4 rounded">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Correct Answers</div>
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $examAttempt->correct_answers }}</div>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900 p-4 rounded">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Incorrect Answers</div>
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $examAttempt->incorrect_answers }}</div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Score Percentage</div>
                        <div class="text-2xl font-bold {{ $examAttempt->score_percentage >= 70 ? 'text-green-600 dark:text-green-400' : ($examAttempt->score_percentage >= 50 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                            {{ number_format($examAttempt->score_percentage, 2) }}%
                        </div>
                    </div>
                </div>
            </div>

            <!-- Answers by Question Type -->
            @foreach($answersByType as $typeId => $answers)
                @php
                    $questionType = $answers->first()->questionType;
                    $typeName = $questionType ? $questionType->type : 'Unknown Type';
                    $typeCorrect = $answers->where('is_correct', true)->count();
                    $typeTotal = $answers->count();
                @endphp
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">
                        {{ $typeName }} 
                        <span class="text-sm font-normal text-gray-500">
                            ({{ $typeCorrect }}/{{ $typeTotal }} correct)
                        </span>
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($answers as $answer)
                        <div class="border rounded-lg p-4 {{ $answer->is_correct ? 'bg-green-50 dark:bg-green-900 border-green-200' : 'bg-red-50 dark:bg-red-900 border-red-200' }}">
                            <div class="flex justify-between items-start mb-2">
                                <div class="font-bold text-gray-900 dark:text-gray-100">
                                    Question #{{ $answer->question_number }}
                                </div>
                                <div class="text-sm">
                                    @if($answer->is_correct)
                                        <span class="bg-green-500 text-white px-2 py-1 rounded">Correct</span>
                                    @else
                                        <span class="bg-red-500 text-white px-2 py-1 rounded">Incorrect</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Question:</div>
                                <div class="text-gray-900 dark:text-gray-100">{{ $answer->question_text }}</div>
                            </div>
                            
                            @if($answer->page && $answer->page->page_image)
                                <div class="mb-3 text-center">
                                    <img src="{{ asset($answer->page->page_image) }}" alt="Question image" class="max-w-full max-h-80 mx-auto border border-gray-300 dark:border-gray-600 rounded">
                                </div>
                            @endif
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Options:</div>
                                    <div class="space-y-1">
                                        @if($answer->option1)
                                            <div class="text-sm {{ $answer->correct_answer == '1' ? 'font-bold text-green-600 dark:text-green-400' : '' }}">
                                                A. {{ $answer->option1 }}
                                                @if($answer->correct_answer == '1') ✓ @endif
                                            </div>
                                        @endif
                                        @if($answer->option2)
                                            <div class="text-sm {{ $answer->correct_answer == '2' ? 'font-bold text-green-600 dark:text-green-400' : '' }}">
                                                B. {{ $answer->option2 }}
                                                @if($answer->correct_answer == '2') ✓ @endif
                                            </div>
                                        @endif
                                        @if($answer->option3)
                                            <div class="text-sm {{ $answer->correct_answer == '3' ? 'font-bold text-green-600 dark:text-green-400' : '' }}">
                                                C. {{ $answer->option3 }}
                                                @if($answer->correct_answer == '3') ✓ @endif
                                            </div>
                                        @endif
                                        @if($answer->option4)
                                            <div class="text-sm {{ $answer->correct_answer == '4' ? 'font-bold text-green-600 dark:text-green-400' : '' }}">
                                                D. {{ $answer->option4 }}
                                                @if($answer->correct_answer == '4') ✓ @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Answer:</div>
                                    <div class="space-y-2">
                                        <div>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Selected:</span>
                                            <span class="ml-2 font-bold {{ $answer->is_correct ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $answer->selected_answer ? chr(64 + (int)$answer->selected_answer) . ' (' . $answer->selected_answer . ')' : 'Not Answered' }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Correct:</span>
                                            <span class="ml-2 font-bold text-green-600 dark:text-green-400">
                                                {{ chr(64 + (int)$answer->correct_answer) }} ({{ $answer->correct_answer }})
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
