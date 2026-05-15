<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Question') }} - {{ $book->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Edit Question Form -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 mb-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Edit Question #{{ $page->pageno }}</h3>
                    <a href="{{ route('books.pages.index', $book) }}" 
                       class="text-gray-600 hover:text-gray-800 dark:text-gray-400">
                        ← Back to Questions
                    </a>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        @if($previousPage)
                            <a href="{{ route('pages.edit', $previousPage) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600  font-medium rounded-lg transition-all">
                                ← Previous (Page #{{ $previousPage->pageno }})
                            </a>
                        @else
                            <span class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 font-medium rounded-lg cursor-not-allowed">
                                ← Previous
                            </span>
                        @endif
                    </div>
                    <div>
                        @if($nextPage)
                            <a href="{{ route('pages.edit', $nextPage) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 font-medium rounded-lg transition-all">
                                Next (Page #{{ $nextPage->pageno }}) →
                            </a>
                        @else
                            <span class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 font-medium rounded-lg cursor-not-allowed">
                                Next →
                            </span>
                        @endif
                    </div>
                </div>

                <form action="{{ route('pages.update', $page) }}" method="POST" enctype="multipart/form-data" id="editQuestionForm">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <!-- Question Field -->
                        <div>
                            <label for="question" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Question <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                name="question"
                                id="question"
                                rows="3"
                                required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                                placeholder="Enter your question here..."
                            >{{ old('question', $page->question) }}</textarea>
                        </div>

                        <!-- Options -->
                        <div class="space-y-4">
                            <div>
                                <label for="option1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Option 1 <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="option1"
                                    id="option1"
                                    required
                                    value="{{ old('option1', $page->option1) }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                                    placeholder="Enter option 1"
                                />
                            </div>

                            <div>
                                <label for="option2" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Option 2 <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="option2"
                                    id="option2"
                                    required
                                    value="{{ old('option2', $page->option2) }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                                    placeholder="Enter option 2"
                                />
                            </div>

                            <div>
                                <label for="option3" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Option 3 <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="option3"
                                    id="option3"
                                    required
                                    value="{{ old('option3', $page->option3) }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                                    placeholder="Enter option 3"
                                />
                            </div>

                            <div>
                                <label for="option4" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Option 4 <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="option4"
                                    id="option4"
                                    required
                                    value="{{ old('option4', $page->option4) }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                                    placeholder="Enter option 4"
                                />
                            </div>
                        </div>

                        <!-- Correct Answer -->
                        <div>
                            <label for="correct_answer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Correct Answer <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="correct_answer"
                                id="correct_answer"
                                required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                            >
                                <option value="">Select correct answer</option>
                                <option value="1" {{ old('correct_answer', $page->correct_answer) == '1' ? 'selected' : '' }}>Option 1</option>
                                <option value="2" {{ old('correct_answer', $page->correct_answer) == '2' ? 'selected' : '' }}>Option 2</option>
                                <option value="3" {{ old('correct_answer', $page->correct_answer) == '3' ? 'selected' : '' }}>Option 3</option>
                                <option value="4" {{ old('correct_answer', $page->correct_answer) == '4' ? 'selected' : '' }}>Option 4</option>
                            </select>
                        </div>

                        <!-- Type Field -->
                        <div>
                            <label for="type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Question Type (Optional)
                            </label>
                            <select
                                name="type_id"
                                id="type_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                            >
                                <option value="">Select question type (Optional)</option>
                                @foreach($questionTypes as $questionType)
                                    <option value="{{ $questionType->id }}" {{ old('type_id', $page->type_id) == $questionType->id ? 'selected' : '' }}>
                                        {{ $questionType->type }} ({{ $questionType->typecode }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Optional: Categorize the question type</p>
                        </div>

                        <!-- Sub-Section Field (Dynamic) -->
                        <div id="sub-section-container" style="display: none;">
                            <label for="sub_section_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Sub-Section (Optional)
                            </label>
                            <select
                                name="sub_section_id"
                                id="sub_section_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                            >
                                <option value="">Select sub-section (Optional)</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Optional: Select a sub-section for this question type</p>
                        </div>

                        <!-- File Uploads (Optional) -->
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                            <!-- Image Upload -->
                            <div>
                                <label for="page_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Question Image (Optional)
                                </label>
                                
                                @if($page->page_image)
                                    <div class="mb-2">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current Image:</p>
                                        <img src="{{ asset($page->page_image) }}" alt="Current image" class="max-w-xs rounded-md border border-gray-300 dark:border-gray-600 mb-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="remove_image" value="1" class="mr-2">
                                            <span class="text-sm text-red-600 dark:text-red-400">Remove current image</span>
                                        </label>
                                    </div>
                                @endif

                                <input
                                    type="file"
                                    name="page_image"
                                    id="page_image"
                                    accept="image/*"
                                    class="block w-full text-sm text-gray-900 dark:text-gray-300
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-full file:border-0
                                           file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700
                                           hover:file:bg-blue-100
                                           dark:file:bg-gray-600 dark:file:text-gray-200"
                                />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload new image to replace current one</p>
                                <div id="image-preview" class="mt-2 hidden">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">New Image Preview:</p>
                                    <img id="image-preview-img" src="" alt="Preview" class="max-w-xs rounded-md border border-gray-300 dark:border-gray-600">
                                </div>
                            </div>

                            <!-- Audio Upload -->
                            <div>
                                <label for="page_audio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Question Audio (Optional)
                                </label>
                                
                                @if($page->page_html)
                                    <div class="mb-2">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current Audio:</p>
                                        <audio controls class="w-full mb-2">
                                            <source src="{{ asset($page->page_html) }}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="remove_audio" value="1" class="mr-2">
                                            <span class="text-sm text-red-600 dark:text-red-400">Remove current audio</span>
                                        </label>
                                    </div>
                                @endif

                                <input
                                    type="file"
                                    name="page_audio"
                                    id="page_audio"
                                    accept="audio/*"
                                    class="block w-full text-sm text-gray-900 dark:text-gray-300
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-full file:border-0
                                           file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700
                                           hover:file:bg-blue-100
                                           dark:file:bg-gray-600 dark:file:text-gray-200"
                                />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload new audio to replace current one</p>
                                <div id="audio-preview" class="mt-2 hidden">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">New Audio Preview:</p>
                                    <audio id="audio-preview-player" controls class="w-full">
                                        <source id="audio-preview-source" src="" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('books.pages.index', $book) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg transition-all">
                                Cancel
                            </a>
                            <button
                                type="submit"
                                class="bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all"
                            >
                                Update Question
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        // Image preview
        document.getElementById('page_image')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('image-preview-img');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });

        // Audio preview
        document.getElementById('page_audio')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('audio-preview');
            const previewPlayer = document.getElementById('audio-preview-player');
            const previewSource = document.getElementById('audio-preview-source');
            
            if (file) {
                const url = URL.createObjectURL(file);
                previewSource.src = url;
                previewPlayer.load();
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        });

        // Form validation
        document.getElementById('editQuestionForm')?.addEventListener('submit', function(e) {
            const correctAnswer = document.getElementById('correct_answer').value;
            if (!correctAnswer) {
                e.preventDefault();
                alert('Please select the correct answer');
                return false;
            }
        });

        // Dynamic sub-section loading
        const typeIdSelect = document.getElementById('type_id');
        const subSectionContainer = document.getElementById('sub-section-container');
        const subSectionSelect = document.getElementById('sub_section_id');
        const currentSubSectionId = {{ $page->sub_section_id ?? 'null' }};

        function loadSubSections(questionTypeId) {
            // Clear sub-section select
            subSectionSelect.innerHTML = '<option value="">Select sub-section (Optional)</option>';
            
            if (questionTypeId) {
                // Show sub-section container
                subSectionContainer.style.display = 'block';
                
                // Fetch sub-sections via AJAX
                fetch(`{{ route('pages.get-sub-sections') }}?question_type_id=${questionTypeId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(subSection => {
                                const option = document.createElement('option');
                                option.value = subSection.id;
                                option.textContent = subSection.name;
                                if (currentSubSectionId && currentSubSectionId == subSection.id) {
                                    option.selected = true;
                                }
                                subSectionSelect.appendChild(option);
                            });
                        } else {
                            subSectionSelect.innerHTML = '<option value="">No sub-sections available</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching sub-sections:', error);
                        subSectionSelect.innerHTML = '<option value="">Error loading sub-sections</option>';
                    });
            } else {
                // Hide sub-section container if no question type selected
                subSectionContainer.style.display = 'none';
            }
        }

        typeIdSelect?.addEventListener('change', function() {
            loadSubSections(this.value);
        });

        // Load sub-sections on page load if type_id is already selected
        if (typeIdSelect?.value) {
            loadSubSections(typeIdSelect.value);
        }
    </script>
</x-app-layout>

