@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Add Grades</h1>
                    <a href="{{ route('admin.grades.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to Grades
                    </a>
                </div>

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Exam Selection Form -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="exam_id" class="block text-sm font-medium text-gray-700">Select Exam</label>
                            <select id="exam_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Select Exam</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}">
                                        {{ $exam->name }} - {{ $exam->subject->name }} ({{ $exam->schoolClass->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="button" id="load-students" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Load Students
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Grades Form -->
                <div id="grades-form" style="display: none;">
                    <form action="{{ route('admin.grades.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="exam_id" id="form_exam_id">

                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">Student Grades</h3>
                                <div id="exam-info" class="text-sm text-gray-600"></div>
                            </div>
                            
                            <div id="students-list" class="space-y-4">
                                <!-- Students will be loaded here -->
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="document.getElementById('grades-form').style.display='none'" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </button>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Save Grades
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('load-students').addEventListener('click', function() {
    const examId = document.getElementById('exam_id').value;

    if (!examId) {
        alert('Please select an exam');
        return;
    }

    // Set form hidden field
    document.getElementById('form_exam_id').value = examId;

    // Fetch students and exam info
    fetch(`{{ route('admin.grades.students-by-exam') }}?exam_id=${examId}`)
        .then(response => response.json())
        .then(data => {
            const studentsList = document.getElementById('students-list');
            const examInfo = document.getElementById('exam-info');
            
            studentsList.innerHTML = '';
            examInfo.innerHTML = `Total Marks: ${data.exam.total_marks} | Passing Marks: ${data.exam.passing_marks}`;

            data.students.forEach(student => {
                const existingGrade = data.existing_grades[student.id] || '';
                
                const studentDiv = document.createElement('div');
                studentDiv.className = 'grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg';
                studentDiv.innerHTML = `
                    <div class="flex items-center">
                        <div>
                            <p class="font-medium">${student.user.name}</p>
                            <p class="text-sm text-gray-500">${student.student_id}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Marks Obtained</label>
                        <input type="number" name="grades[${student.id}][student_id]" value="${student.id}" hidden>
                        <input type="number" name="grades[${student.id}][marks_obtained]" 
                               value="${existingGrade}" 
                               min="0" max="${data.exam.total_marks}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Remarks (Optional)</label>
                        <input type="text" name="grades[${student.id}][remarks]" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-end">
                        <div class="text-sm text-gray-600">
                            <p>Max: ${data.exam.total_marks}</p>
                            <p>Pass: ${data.exam.passing_marks}</p>
                        </div>
                    </div>
                `;
                studentsList.appendChild(studentDiv);
            });

            document.getElementById('grades-form').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading students');
        });
});
</script>
@endsection