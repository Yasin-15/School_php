<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'School Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        @auth
            <!-- Navigation -->
            <nav class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
                                    School MS
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                @if(auth()->user()->isAdmin())
                                    <div class="relative group">
                                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                            Dashboard
                                        </a>
                                    </div>
                                    <div class="relative group">
                                        <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                            Academic
                                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                            <a href="{{ route('admin.students.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Students</a>
                                            <a href="{{ route('admin.teachers.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Teachers</a>
                                            <a href="{{ route('admin.classes.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Classes</a>
                                            <a href="{{ route('admin.subjects.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Subjects</a>
                                            <a href="{{ route('admin.timetables.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Timetables</a>
                                        </div>
                                    </div>
                                    <div class="relative group">
                                        <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                            Exams & Grades
                                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                            <a href="{{ route('admin.exams.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Exams</a>
                                            <a href="{{ route('admin.grades.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Grades</a>
                                            <a href="{{ route('admin.assignments.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Assignments</a>
                                        </div>
                                    </div>
                                    <div class="relative group">
                                        <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                            Management
                                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                            <a href="{{ route('admin.attendance.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Attendance</a>
                                            <a href="{{ route('admin.fees.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Fees</a>
                                            <a href="{{ route('admin.notices.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Notices</a>
                                        </div>
                                    </div>
                                @elseif(auth()->user()->isTeacher())
                                    <a href="{{ route('teacher.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                        Dashboard
                                    </a>
                                @elseif(auth()->user()->isStudent())
                                    <a href="{{ route('student.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                        Dashboard
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="ml-3 relative">
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                        {{ auth()->user()->role->display_name }}
                                    </span>
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        @endauth

        <!-- Page Content -->
        <main>
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>