<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pages of') }} {{ $book->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 text-green-600 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 text-red-600 font-medium">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 mb-5">
                <ul class="space-y-3 mt-6">
                    @forelse($pages as $page)
                        <li class="flex justify-between items-center border-b pb-2">
                            <span class="text-white">{{ $loop->iteration }}</span>
                            <div class="flex-1 ml-4">
                                <span class="text-blue-600 hover:underline dark:text-blue-400 cursor-pointer">
                                    Page {{ $page->pageno }}: {{ $page->question ?? 'No Question' }}
                                </span>
                                @if($page->questionType)
                                    <span class="ml-2 text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                        {{ $page->questionType->type }}
                                    </span>
                                @endif
                            </div>
                            <div class="space-x-2 flex items-center">
                                <a href="{{ route('pages.edit', $page) }}" 
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400" 
                                   title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete it?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-7" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>                                
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500">No pages found.</li>
                    @endforelse
                </ul>
            </div>

            <!-- Add Question Form -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 mb-5">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Add New Question</h3>
                <form action="{{ route('pages.store') }}" method="POST" enctype="multipart/form-data" id="questionForm">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}"/>

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
                            >{{ old('question') }}</textarea>
                        </div>

                        <!-- Options Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                            <div>
                                <label for="option1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Option 1 <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="option1"
                                    id="option1"
                                    required
                                    value="{{ old('option1') }}"
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
                                    value="{{ old('option2') }}"
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
                                    value="{{ old('option3') }}"
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
                                    value="{{ old('option4') }}"
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
                                <option value="1" {{ old('correct_answer') == '1' ? 'selected' : '' }}>Option 1</option>
                                <option value="2" {{ old('correct_answer') == '2' ? 'selected' : '' }}>Option 2</option>
                                <option value="3" {{ old('correct_answer') == '3' ? 'selected' : '' }}>Option 3</option>
                                <option value="4" {{ old('correct_answer') == '4' ? 'selected' : '' }}>Option 4</option>
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
                                    <option value="{{ $questionType->id }}" {{ old('type_id') == $questionType->id ? 'selected' : '' }}>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Image Upload -->
                            <div>
                                <label for="page_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Question Image (Optional)
                                </label>
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
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Supported formats: JPG, PNG, GIF</p>
                                <div id="image-preview" class="mt-2 hidden">
                                    <img id="image-preview-img" src="" alt="Preview" class="max-w-xs rounded-md border border-gray-300 dark:border-gray-600">
                                </div>
                            </div>

                            <!-- Audio Upload -->
                            <div>
                                <label for="page_audio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Question Audio (Optional)
                                </label>
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
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Supported formats: MP3, WAV, OGG</p>
                                <div id="audio-preview" class="mt-2 hidden">
                                    <audio id="audio-preview-player" controls class="w-full">
                                        <source id="audio-preview-source" src="" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all"
                            >
                                Add Question
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Upload Questions Excel Form -->
            <div class="bg-white dark:bg-gray-600 shadow overflow-hidden sm:rounded-lg p-6 max-w-md mx-auto">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Upload Questions Excel</h3>
                <form action="{{ route('pages.uploadExcel') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}"/>

                    <label for="excel_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Select Excel file (.xlsx or .xls) with columns: <br>
                        <small>question, option1, option2, option3, option4, correct_answer</small>
                    </label>

                    <input
                        type="file"
                        name="excel_file"
                        id="excel_file"
                        accept=".xlsx,.xls"
                        required
                        class="block w-full text-sm text-gray-900 dark:text-gray-300
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-full file:border-0
                               file:text-sm file:font-semibold
                               file:bg-blue-50 file:text-blue-700
                               hover:file:bg-blue-100
                               mb-4"
                    >

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Upload Questions
                    </button>
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
        document.getElementById('questionForm')?.addEventListener('submit', function(e) {
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

        typeIdSelect?.addEventListener('change', function() {
            const questionTypeId = this.value;
            
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
        });

        // Trigger change event on page load if type_id is already selected
        if (typeIdSelect?.value) {
            typeIdSelect.dispatchEvent(new Event('change'));
        }
    </script>
</x-app-layout>
