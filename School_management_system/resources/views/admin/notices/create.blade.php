@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Create Notice</h1>
                    <a href="{{ route('admin.notices.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to Notices
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

                <form action="{{ route('admin.notices.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700">Notice Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="notice_type" class="block text-sm font-medium text-gray-700">Notice Type</label>
                            <select name="notice_type" id="notice_type" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select Type</option>
                                <option value="general" {{ old('notice_type') == 'general' ? 'selected' : '' }}>General</option>
                                <option value="academic" {{ old('notice_type') == 'academic' ? 'selected' : '' }}>Academic</option>
                                <option value="event" {{ old('notice_type') == 'event' ? 'selected' : '' }}>Event</option>
                                <option value="holiday" {{ old('notice_type') == 'holiday' ? 'selected' : '' }}>Holiday</option>
                                <option value="urgent" {{ old('notice_type') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>

                        <div>
                            <label for="target_audience" class="block text-sm font-medium text-gray-700">Target Audience</label>
                            <select name="target_audience" id="target_audience" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select Audience</option>
                                <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>All</option>
                                <option value="students" {{ old('target_audience') == 'students' ? 'selected' : '' }}>Students</option>
                                <option value="teachers" {{ old('target_audience') == 'teachers' ? 'selected' : '' }}>Teachers</option>
                                <option value="parents" {{ old('target_audience') == 'parents' ? 'selected' : '' }}>Parents</option>
                                <option value="specific_class" {{ old('target_audience') == 'specific_class' ? 'selected' : '' }}>Specific Class</option>
                            </select>
                        </div>

                        <div id="class_selection" style="display: none;">
                            <label for="school_class_id" class="block text-sm font-medium text-gray-700">Select Class</label>
                            <select name="school_class_id" id="school_class_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('school_class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="publish_date" class="block text-sm font-medium text-gray-700">Publish Date</label>
                            <input type="date" name="publish_date" id="publish_date" value="{{ old('publish_date', date('Y-m-d')) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date (Optional)</label>
                            <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="content" class="block text-sm font-medium text-gray-700">Notice Content</label>
                        <textarea name="content" id="content" rows="6" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('content') }}</textarea>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_urgent" id="is_urgent" value="1" 
                                   {{ old('is_urgent') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <label for="is_urgent" class="ml-2 text-sm text-gray-700">Mark as Urgent</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="send_notification" id="send_notification" value="1" 
                                   {{ old('send_notification') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <label for="send_notification" class="ml-2 text-sm text-gray-700">Send Notification</label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('admin.notices.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Notice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('target_audience').addEventListener('change', function() {
    const classSelection = document.getElementById('class_selection');
    const classSelect = document.getElementById('school_class_id');
    
    if (this.value === 'specific_class') {
        classSelection.style.display = 'block';
        classSelect.required = true;
    } else {
        classSelection.style.display = 'none';
        classSelect.required = false;
        classSelect.value = '';
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const targetAudience = document.getElementById('target_audience');
    if (targetAudience.value === 'specific_class') {
        document.getElementById('class_selection').style.display = 'block';
        document.getElementById('school_class_id').required = true;
    }
});
</script>
@endsection