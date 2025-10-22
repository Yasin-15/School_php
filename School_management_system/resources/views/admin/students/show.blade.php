@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Student Details</h1>
                    <div class="space-x-2">
                        <a href="{{ route('admin.students.edit', $student) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Edit Student
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Students
                        </a>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->user->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->user->date_of_birth ? $student->user->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gender</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst($student->user->gender) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $student->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $student->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        @if($student->user->address)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->user->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-4">Academic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Student ID</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->student_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Roll Number</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->roll_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Class</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->schoolClass->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Section</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->section->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Admission Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->admission_date ? $student->admission_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Parent/Guardian Information -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-4">Parent/Guardian Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Parent/Guardian Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->parent_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Parent Phone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->parent_phone }}</p>
                        </div>
                        @if($student->parent_email)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Parent Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->parent_email }}</p>
                        </div>
                        @endif
                        @if($student->emergency_contact)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Emergency Contact</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->emergency_contact }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-4">Medical Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($student->blood_group)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Blood Group</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->blood_group }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Transport Required</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->transport_required ? 'Yes' : 'No' }}</p>
                        </div>
                        @if($student->medical_conditions)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Medical Conditions</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->medical_conditions }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection