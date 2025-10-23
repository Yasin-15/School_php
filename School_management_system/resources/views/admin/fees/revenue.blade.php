@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Revenue & Financial Report</h1>
                    <a href="{{ route('admin.fees.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to Fees
                    </a>
                </div>

                <!-- Financial Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-green-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600">Total Revenue</p>
                                <p class="text-2xl font-bold text-green-900">${{ number_format($totalRevenue, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-red-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-red-600">Total Expenses</p>
                                <p class="text-2xl font-bold text-red-900">${{ number_format($totalExpenses, 2) }}</p>
                                <p class="text-xs text-red-500">Annual teacher salaries</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-{{ $netProfit >= 0 ? 'blue' : 'orange' }}-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-{{ $netProfit >= 0 ? 'blue' : 'orange' }}-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-{{ $netProfit >= 0 ? 'blue' : 'orange' }}-600">Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</p>
                                <p class="text-2xl font-bold text-{{ $netProfit >= 0 ? 'blue' : 'orange' }}-900">${{ number_format(abs($netProfit), 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-600">Outstanding Fees</p>
                                <p class="text-2xl font-bold text-yellow-900">${{ number_format($outstandingFees, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Teacher Salary Breakdown -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold mb-4">Teacher Salary Expenses</h2>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Monthly Salaries</p>
                                <p class="text-2xl font-bold text-gray-900">${{ number_format($teacherSalaries, 2) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Annual Salaries</p>
                                <p class="text-2xl font-bold text-gray-900">${{ number_format($teacherSalaries * 12, 2) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Revenue vs Salary Ratio</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $teacherSalaries > 0 ? number_format($totalRevenue / ($teacherSalaries * 12), 2) : 'N/A' }}:1</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts and Breakdowns -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Fee Type Revenue -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Revenue by Fee Type</h3>
                        <div class="space-y-3">
                            @foreach($feeTypeRevenue as $feeType)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">{{ ucfirst($feeType->fee_type) }}</span>
                                    <span class="text-sm font-bold text-gray-900">${{ number_format($feeType->total, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $totalRevenue > 0 ? ($feeType->total / $totalRevenue) * 100 : 0 }}%"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Class Revenue -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Revenue by Class</h3>
                        <div class="space-y-3">
                            @foreach($classRevenue as $classRev)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">{{ $classRev->class_name }}</span>
                                    <span class="text-sm font-bold text-gray-900">${{ number_format($classRev->total, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $totalRevenue > 0 ? ($classRev->total / $totalRevenue) * 100 : 0 }}%"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue Trend -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold mb-4">Monthly Revenue Trend</h2>
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-2">Month</th>
                                        <th class="text-right py-2">Revenue</th>
                                        <th class="text-right py-2">Teacher Expenses</th>
                                        <th class="text-right py-2">Net Profit/Loss</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlyRevenue as $month)
                                        @php
                                            $monthlyProfit = $month->total - $teacherSalaries;
                                        @endphp
                                        <tr class="border-b">
                                            <td class="py-2">{{ date('F Y', mktime(0, 0, 0, $month->month, 1, $month->year)) }}</td>
                                            <td class="text-right py-2">${{ number_format($month->total, 2) }}</td>
                                            <td class="text-right py-2">${{ number_format($teacherSalaries, 2) }}</td>
                                            <td class="text-right py-2 {{ $monthlyProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                ${{ number_format($monthlyProfit, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div>
                    <h2 class="text-lg font-semibold mb-4">Recent Payments</h2>
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentPayments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $payment->student->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $payment->student->schoolClass->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ucfirst($payment->fee_type) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ${{ number_format($payment->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $payment->paid_date ? date('M d, Y', strtotime($payment->paid_date)) : 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection