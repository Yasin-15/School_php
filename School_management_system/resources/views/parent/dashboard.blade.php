@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">Parent Dashboard</h1>
                
                <!-- Children Overview -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold mb-4">My Children</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-600 text-center">No children linked to this account</p>
                    </div>
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
                                <p class="text-2xl font-bold text-blue-900">0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Rate -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600">Avg Attendance</p>
                                <p class="text-2xl font-bold text-green-900">--</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Fees -->
                    <div class="bg-yellow-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-600">Pending Fees</p>
                                <p class="text-2xl font-bold text-yellow-900">$0</p>
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
                                <p class="text-2xl font-bold text-purple-900">0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Grades -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Recent Grades</h3>
                        <div class="space-y-3">
                            <p class="text-gray-500 text-center py-4">No recent grades available</p>
                        </div>
                    </div>

                    <!-- School Notices -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">School Notices</h3>
                        <div class="space-y-3">
                            <p class="text-gray-500 text-center py-4">No new notices</p>
                        </div>
                    </div>
                </div>

                <!-- Communication -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4">Communication</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded">
                            Message Teachers
                        </button>
                        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded">
                            View Fee Details
                        </button>
                        <button class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded">
                            Download Reports
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection