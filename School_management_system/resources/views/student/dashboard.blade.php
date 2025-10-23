@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">Welcome, {{ $student->user->name }}</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Attendance -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600">Attendance</p>
                                @if($attendanceStats && $attendanceStats->total_days > 0)
                                    <p class="text-2xl font-bold text-blue-900">{{ round(($attendanceStats->present_days / $attendanceStats->total_days) * 100) }}%</p>
                                @else
                                    <p class="text-2xl font-bold text-blue-900">N/A</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Assignments -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600">Pending Assignments</p>
                                <p class="text-2xl font-bold text-green-900">{{ $pendingAssignments->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Exams -->
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-600">Upcoming Exams</p>
                                <p class="text-2xl font-bold text-purple-900">{{ $upcomingExams->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notices -->
                    <div class="bg-yellow-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h8V9H4v2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-600">New Notices</p>
                                <p class="text-2xl font-bold text-yellow-900">{{ $notices->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Class Information -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Class Information</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium">Class:</span> {{ $student->schoolClass->name ?? 'Not Assigned' }}</p>
                            <p><span class="font-medium">Section:</span> {{ $student->section->name ?? 'Not Assigned' }}</p>
                            <p><span class="font-medium">Roll Number:</span> {{ $student->roll_number ?? 'Not Assigned' }}</p>
                            <p><span class="font-medium">Student ID:</span> {{ $student->student_id }}</p>
                        </div>
                    </div>

                    <!-- Today's Timetable -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Today's Classes</h3>
                        <div class="space-y-3">
                            @forelse($todayTimetable as $class)
                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                    <div>
                                        <p class="font-medium">{{ $class->subject->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $class->teacher->user->name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium">{{ date('H:i', strtotime($class->start_time)) }} - {{ date('H:i', strtotime($class->end_time)) }}</p>
                                        @if($class->room_number)
                                            <p class="text-xs text-gray-500">Room: {{ $class->room_number }}</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No classes today</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Grades and Upcoming Exams -->
                <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Grades -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Recent Grades</h3>
                        <div class="space-y-3">
                            @forelse($recentGrades as $grade)
                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                    <div>
                                        <p class="font-medium">{{ $grade->exam->subject->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $grade->exam->name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-blue-600">{{ $grade->grade_letter }}</p>
                                        <p class="text-sm text-gray-500">{{ $grade->marks_obtained }}/{{ $grade->exam->total_marks }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No recent grades available</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Upcoming Exams -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Upcoming Exams</h3>
                        <div class="space-y-3">
                            @forelse($upcomingExams as $exam)
                                <div class="p-3 bg-red-50 border border-red-200 rounded">
                                    <p class="font-medium text-red-800">{{ $exam->subject->name }}</p>
                                    <p class="text-sm text-red-600">{{ $exam->name }}</p>
                                    <p class="text-sm text-red-500">{{ date('M d, Y', strtotime($exam->exam_date)) }} at {{ date('H:i', strtotime($exam->start_time)) }}</p>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No upcoming exams</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Notices -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4">Recent Notices</h2>
                    <div class="space-y-4">
                        @forelse($notices as $notice)
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $notice->title }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($notice->content, 150) }}</p>
                                        <p class="text-xs text-gray-500 mt-2">{{ date('M d, Y', strtotime($notice->publish_date)) }}</p>
                                    </div>
                                    @if($notice->is_urgent)
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Urgent</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-600 text-center">No recent notices</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection