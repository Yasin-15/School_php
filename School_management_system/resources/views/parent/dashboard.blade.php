@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">Welcome, {{ auth()->user()->name }}</h1>
                
                <!-- Children Overview -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold mb-4">My Children</h2>
                    @forelse($childrenData as $childData)
                        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-4">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-medium">{{ $childData['student']->user->name }}</h3>
                                    <p class="text-sm text-gray-600">
                                        {{ $childData['student']->schoolClass->name ?? 'No Class' }} - 
                                        {{ $childData['student']->section->name ?? 'No Section' }}
                                    </p>
                                    <p class="text-sm text-gray-500">Student ID: {{ $childData['student']->student_id }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Roll No: {{ $childData['student']->roll_number ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Attendance -->
                                <div class="bg-blue-50 p-4 rounded">
                                    <h4 class="font-medium text-blue-800">Attendance (This Month)</h4>
                                    @if($childData['attendance_stats'] && $childData['attendance_stats']->total_days > 0)
                                        <p class="text-2xl font-bold text-blue-900">
                                            {{ round(($childData['attendance_stats']->present_days / $childData['attendance_stats']->total_days) * 100) }}%
                                        </p>
                                        <p class="text-sm text-blue-600">
                                            {{ $childData['attendance_stats']->present_days }}/{{ $childData['attendance_stats']->total_days }} days
                                        </p>
                                    @else
                                        <p class="text-blue-600">No attendance data</p>
                                    @endif
                                </div>

                                <!-- Recent Grades -->
                                <div class="bg-green-50 p-4 rounded">
                                    <h4 class="font-medium text-green-800">Recent Grades</h4>
                                    @if($childData['recent_grades']->count() > 0)
                                        @foreach($childData['recent_grades']->take(2) as $grade)
                                            <div class="text-sm">
                                                <span class="font-medium">{{ $grade->exam->subject->name }}:</span>
                                                <span class="text-green-900">{{ $grade->grade_letter }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-green-600">No grades yet</p>
                                    @endif
                                </div>

                                <!-- Fees -->
                                <div class="bg-yellow-50 p-4 rounded">
                                    <h4 class="font-medium text-yellow-800">Pending Fees</h4>
                                    <p class="text-2xl font-bold text-yellow-900">${{ number_format($childData['pending_fees'], 2) }}</p>
                                    @if($childData['pending_fees'] > 0)
                                        <p class="text-sm text-yellow-600">Payment required</p>
                                    @else
                                        <p class="text-sm text-yellow-600">All paid up</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-center">No children linked to this account</p>
                        </div>
                    @endforelse
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Children -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600">Children</p>
                                <p class="text-2xl font-bold text-blue-900">{{ count($childrenData) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Average Attendance -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600">Avg Attendance</p>
                                @php
                                    $totalAttendance = 0;
                                    $childrenWithAttendance = 0;
                                    foreach($childrenData as $childData) {
                                        if($childData['attendance_stats'] && $childData['attendance_stats']->total_days > 0) {
                                            $totalAttendance += ($childData['attendance_stats']->present_days / $childData['attendance_stats']->total_days) * 100;
                                            $childrenWithAttendance++;
                                        }
                                    }
                                    $avgAttendance = $childrenWithAttendance > 0 ? round($totalAttendance / $childrenWithAttendance) : 0;
                                @endphp
                                <p class="text-2xl font-bold text-green-900">{{ $avgAttendance }}%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Pending Fees -->
                    <div class="bg-yellow-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-600">Total Pending Fees</p>
                                <p class="text-2xl font-bold text-yellow-900">
                                    ${{ number_format(collect($childrenData)->sum('pending_fees'), 2) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h8V9H4v2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-600">New Notices</p>
                                <p class="text-2xl font-bold text-purple-900">{{ $notices->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- School Notices -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4">Recent School Notices</h2>
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