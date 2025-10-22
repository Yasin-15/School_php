@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Mark Attendance</h1>
                    <a href="{{ route('admin.attendance.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to Attendance
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

                <!-- Class Selection Form -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                            <select id="class_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="section_id" class="block text-sm font-medium text-gray-700">Section (Optional)</label>
                            <select id="section_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">All Sections</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="attendance_date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" id="attendance_date" value="{{ date('Y-m-d') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="button" id="load-students" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Load Students
                        </button>
                    </div>
                </div>

                <!-- Attendance Form -->
                <div id="attendance-form" style="display: none;">
                    <form action="{{ route('admin.attendance.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="class_id" id="form_class_id">
                        <input type="hidden" name="section_id" id="form_section_id">
                        <input type="hidden" name="date" id="form_date">

                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">Students List</h3>
                                <div class="space-x-2">
                                    <button type="button" id="mark-all-present" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">
                                        Mark All Present
                                    </button>
                                    <button type="button" id="mark-all-absent" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                        Mark All Absent
                                    </button>
                                </div>
                            </div>
                            
                            <div id="students-list" class="space-y-2">
                                <!-- Students will be loaded here -->
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="document.getElementById('attendance-form').style.display='none'" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </button>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Save Attendance
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
    const classId = document.getElementById('class_id').value;
    const sectionId = document.getElementById('section_id').value;
    const date = document.getElementById('attendance_date').value;

    if (!classId || !date) {
        alert('Please select class and date');
        return;
    }

    // Set form hidden fields
    document.getElementById('form_class_id').value = classId;
    document.getElementById('form_section_id').value = sectionId;
    document.getElementById('form_date').value = date;

    // Fetch students
    fetch(`{{ route('admin.attendance.students') }}?class_id=${classId}&section_id=${sectionId}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            const studentsList = document.getElementById('students-list');
            studentsList.innerHTML = '';

            data.students.forEach(student => {
                const existingStatus = data.existing_attendance[student.id] || 'present';
                
                const studentDiv = document.createElement('div');
                studentDiv.className = 'flex items-center justify-between p-3 bg-gray-50 rounded';
                studentDiv.innerHTML = `
                    <div>
                        <span class="font-medium">${student.user.name}</span>
                        <span class="text-sm text-gray-500 ml-2">(${student.student_id})</span>
                    </div>
                    <div class="flex space-x-2">
                        <label class="flex items-center">
                            <input type="radio" name="attendance[${student.id}]" value="present" 
                                   ${existingStatus === 'present' ? 'checked' : ''} class="mr-1">
                            <span class="text-green-600">Present</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="attendance[${student.id}]" value="absent" 
                                   ${existingStatus === 'absent' ? 'checked' : ''} class="mr-1">
                            <span class="text-red-600">Absent</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="attendance[${student.id}]" value="late" 
                                   ${existingStatus === 'late' ? 'checked' : ''} class="mr-1">
                            <span class="text-yellow-600">Late</span>
                        </label>
                    </div>
                `;
                studentsList.appendChild(studentDiv);
            });

            document.getElementById('attendance-form').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading students');
        });
});

// Mark all present
document.getElementById('mark-all-present').addEventListener('click', function() {
    document.querySelectorAll('input[type="radio"][value="present"]').forEach(radio => {
        radio.checked = true;
    });
});

// Mark all absent
document.getElementById('mark-all-absent').addEventListener('click', function() {
    document.querySelectorAll('input[type="radio"][value="absent"]').forEach(radio => {
        radio.checked = true;
    });
});
</script>
@endsection