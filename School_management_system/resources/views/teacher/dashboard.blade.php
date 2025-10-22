@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">Welcome, {{ $teacher->user->name }}</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- My Classes -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600">My Classes</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $myClasses->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Classes -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600">Today's Classes</p>
                                <p class="text-2xl font-bold text-green-900">{{ $todayClasses->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Exams -->
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-600">Upcoming Exams</p>
                                <p class="text-2xl font-bold text-purple-900">{{ $upcomingExams->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Assignments -->
                    <div class="bg-yellow-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h8V9H4v2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-600">Pending Assignments</p>
                                <p class="text-2xl font-bold text-yellow-900">{{ $pendingAssignments }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Schedule -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4">Today's Schedule</h2>
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        @forelse($todayClasses as $class)
                            <div class="flex justify-between items-center p-3 mb-3 bg-blue-50 rounded-lg">
                                <div>
                                    <h4 class="font-medium text-blue-900">{{ $class->subject->name }}</h4>
                                    <p class="text-sm text-blue-700">{{ $class->schoolClass->name }}</p>
                                    @if($class->room_number)
                                        <p class="text-xs text-blue-600">Room: {{ $class->room_number }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-blue-900">{{ date('H:i', strtotime($class->start_time)) }} - {{ date('H:i', strtotime($class->end_time)) }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-600 text-center py-4">No classes scheduled for today</p>
                        @endforelse
                    </div>
                </div>

                <!-- Upcoming Exams and Recent Notices -->
                <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Upcoming Exams -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Upcoming Exams</h3>
                        <div class="space-y-3">
                            @forelse($upcomingExams as $exam)
                                <div class="p-3 bg-red-50 border border-red-200 rounded">
                                    <p class="font-medium text-red-800">{{ $exam->subject->name }}</p>
                                    <p class="text-sm text-red-600">{{ $exam->schoolClass->name }} - {{ $exam->name }}</p>
                                    <p class="text-sm text-red-500">{{ date('M d, Y', strtotime($exam->exam_date)) }} at {{ date('H:i', strtotime($exam->start_time)) }}</p>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No upcoming exams</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Notices -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Recent Notices</h3>
                        <div class="space-y-3">
                            @forelse($notices as $notice)
                                <div class="p-3 bg-gray-50 rounded">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $notice->title }}</p>
                                            <p class="text-sm text-gray-600">{{ Str::limit($notice->content, 100) }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ date('M d, Y', strtotime($notice->publish_date)) }}</p>
                                        </div>
                                        @if($notice->is_urgent)
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded">Urgent</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No recent notices</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- My Classes Overview -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4">My Classes</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($myClasses as $class)
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900">{{ $class->name }}</h4>
                                <p class="text-sm text-gray-600">Grade {{ $class->grade_level }}</p>
                                <p class="text-sm text-gray-500">{{ $class->students_count ?? 0 }} students</p>
                            </div>
                        @empty
                            <div class="col-span-full">
                                <p class="text-gray-600 text-center py-4">No classes assigned</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection