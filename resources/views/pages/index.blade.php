<x-app-layout>
    <x-slot name="header">
        Pages of {{ $book->name }}
    </x-slot>

    <div class="page-stack">
        @if (session('success'))
            <div class="ui-alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="ui-alert-danger">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="data-card">
            <div class="data-card-header">
                <div>
                    <h2 class="section-title">Question Pages</h2>
                    <p class="section-copy mt-2">Manage the question list, jump into edits, and keep section metadata organized.</p>
                </div>
            </div>
            <div class="divide-y divide-slate-200">
                @forelse($pages as $page)
                    <div class="flex flex-col gap-4 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 font-semibold text-blue-700">{{ $loop->iteration }}</div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Page {{ $page->pageno }}</p>
                                <p class="mt-1 text-sm leading-6 text-slate-600">{{ $page->question ?? 'No Question' }}</p>
                                @if($page->questionType)
                                    <span class="ui-badge ui-badge-brand mt-3">{{ $page->questionType->type }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('pages.edit', $page) }}" class="ui-button-secondary px-4 py-2 text-xs">Edit</a>
                            <form action="{{ route('pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete it?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ui-button-danger px-4 py-2 text-xs">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-slate-500">No pages found.</div>
                @endforelse
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <section class="form-card">
                <h2 class="section-title">Add New Question</h2>
                <p class="section-copy mt-2">Create a question with answers, question type, and optional media while keeping the current submission flow unchanged.</p>
                <form action="{{ route('pages.store') }}" method="POST" enctype="multipart/form-data" id="questionForm" class="mt-6 space-y-5">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}"/>

                    <div>
                        <label for="question" class="ui-label">Question</label>
                        <textarea name="question" id="question" rows="3" required class="ui-textarea" placeholder="Enter your question here...">{{ old('question') }}</textarea>
                    </div>

                    <div class="form-grid">
                        <div>
                            <label for="option1" class="ui-label">Option 1</label>
                            <input type="text" name="option1" id="option1" required value="{{ old('option1') }}" class="ui-input" placeholder="Enter option 1" />
                        </div>
                        <div>
                            <label for="option2" class="ui-label">Option 2</label>
                            <input type="text" name="option2" id="option2" required value="{{ old('option2') }}" class="ui-input" placeholder="Enter option 2" />
                        </div>
                        <div>
                            <label for="option3" class="ui-label">Option 3</label>
                            <input type="text" name="option3" id="option3" required value="{{ old('option3') }}" class="ui-input" placeholder="Enter option 3" />
                        </div>
                        <div>
                            <label for="option4" class="ui-label">Option 4</label>
                            <input type="text" name="option4" id="option4" required value="{{ old('option4') }}" class="ui-input" placeholder="Enter option 4" />
                        </div>
                    </div>

                    <div class="form-grid">
                        <div>
                            <label for="correct_answer" class="ui-label">Correct Answer</label>
                            <select name="correct_answer" id="correct_answer" required class="ui-select">
                                <option value="">Select correct answer</option>
                                <option value="1" {{ old('correct_answer') == '1' ? 'selected' : '' }}>Option 1</option>
                                <option value="2" {{ old('correct_answer') == '2' ? 'selected' : '' }}>Option 2</option>
                                <option value="3" {{ old('correct_answer') == '3' ? 'selected' : '' }}>Option 3</option>
                                <option value="4" {{ old('correct_answer') == '4' ? 'selected' : '' }}>Option 4</option>
                            </select>
                        </div>
                        <div>
                            <label for="question_type_category_id" class="ui-label">Category Filter</label>
                            <select id="question_type_category_id" class="ui-select">
                                <option value="">All categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-category-name="{{ strtolower($category->name) }}" {{ $book->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="ui-help">Only filters the question type list. It is not saved.</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div>
                            <label for="type_id" class="ui-label">Question Type</label>
                            <select name="type_id" id="type_id" class="ui-select">
                                <option value="">Select question type (Optional)</option>
                                @foreach($questionTypes as $questionType)
                                    <option value="{{ $questionType->id }}" data-search-text="{{ strtolower($questionType->type . ' ' . $questionType->typecode) }}" data-category-id="{{ $questionType->category_id ?? '' }}" {{ old('type_id') == $questionType->id ? 'selected' : '' }}>
                                        {{ $questionType->type }} ({{ $questionType->typecode }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="sub-section-container" style="display: none;">
                            <label for="sub_section_id" class="ui-label">Sub-Section</label>
                            <select name="sub_section_id" id="sub_section_id" class="ui-select">
                                <option value="">Select sub-section (Optional)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div>
                            <label for="page_image" class="ui-label">Question Image</label>
                            <input type="file" name="page_image" id="page_image" accept="image/*" class="ui-input" />
                            <div id="image-preview" class="mt-3 hidden">
                                <img id="image-preview-img" src="" alt="Preview" class="max-w-xs rounded-2xl border border-slate-200">
                            </div>
                        </div>
                        <div>
                            <label for="page_audio" class="ui-label">Question Audio</label>
                            <input type="file" name="page_audio" id="page_audio" accept="audio/*" class="ui-input" />
                            <div id="audio-preview" class="mt-3 hidden">
                                <audio id="audio-preview-player" controls class="w-full">
                                    <source id="audio-preview-source" src="" type="audio/mpeg">
                                </audio>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end border-t border-slate-200 pt-6">
                        <button type="submit" class="ui-button-primary">Add Question</button>
                    </div>
                </form>
            </section>

            <section class="surface p-6">
                <h2 class="section-title">Upload Questions Excel</h2>
                <p class="section-copy mt-2">Bulk import question rows using the existing Excel endpoint.</p>
                <form action="{{ route('pages.uploadExcel') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}"/>
                    <div>
                        <label for="excel_file" class="ui-label">Excel file</label>
                        <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls" required class="ui-input">
                        <p class="ui-help">Expected columns: question, option1, option2, option3, option4, correct_answer</p>
                    </div>
                    <button type="submit" class="ui-button-primary w-full">Upload Questions</button>
                </form>
            </section>
        </div>
    </div>

    <script>
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

        document.getElementById('questionForm')?.addEventListener('submit', function(e) {
            if (!document.getElementById('correct_answer').value) {
                e.preventDefault();
                alert('Please select the correct answer');
            }
        });

        const categoryFilterSelect = document.getElementById('question_type_category_id');
        const typeIdSelect = document.getElementById('type_id');
        const subSectionContainer = document.getElementById('sub-section-container');
        const subSectionSelect = document.getElementById('sub_section_id');
        const originalTypeOptions = Array.from(typeIdSelect?.options || []).map(option => ({
            value: option.value,
            text: option.text,
            searchText: option.dataset.searchText || '',
            categoryId: option.dataset.categoryId || ''
        }));

        function filterQuestionTypes() {
            if (!typeIdSelect || !categoryFilterSelect) return;
            const selectedCategoryId = categoryFilterSelect.options[categoryFilterSelect.selectedIndex]?.value || '';
            const currentValue = typeIdSelect.value;
            typeIdSelect.innerHTML = '';
            originalTypeOptions.forEach(optionData => {
                const shouldShow = !selectedCategoryId || optionData.value === '' || optionData.categoryId === selectedCategoryId;
                if (shouldShow) {
                    const option = document.createElement('option');
                    option.value = optionData.value;
                    option.textContent = optionData.text;
                    option.dataset.searchText = optionData.searchText;
                    option.dataset.categoryId = optionData.categoryId;
                    if (optionData.value === currentValue) option.selected = true;
                    typeIdSelect.appendChild(option);
                }
            });
            if (typeIdSelect.value !== currentValue) {
                typeIdSelect.value = '';
                subSectionSelect.innerHTML = '<option value="">Select sub-section (Optional)</option>';
                subSectionContainer.style.display = 'none';
            }
        }

        categoryFilterSelect?.addEventListener('change', filterQuestionTypes);
        typeIdSelect?.addEventListener('change', function() {
            const questionTypeId = this.value;
            subSectionSelect.innerHTML = '<option value="">Select sub-section (Optional)</option>';
            if (questionTypeId) {
                subSectionContainer.style.display = 'block';
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
                    .catch(() => {
                        subSectionSelect.innerHTML = '<option value="">Error loading sub-sections</option>';
                    });
            } else {
                subSectionContainer.style.display = 'none';
            }
        });

        filterQuestionTypes();
        if (typeIdSelect?.value) typeIdSelect.dispatchEvent(new Event('change'));
    </script>
</x-app-layout>
