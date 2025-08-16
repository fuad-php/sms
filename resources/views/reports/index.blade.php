@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Reports</h1>
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            <i class="fas fa-download mr-2"></i>Generate Report
        </button>
    </div>

    <!-- Report Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Academic Reports -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">Academic Reports</h3>
            </div>
            <ul class="space-y-2">
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Class Performance Report</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Student Progress Report</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Subject-wise Analysis</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Exam Results Summary</a></li>
            </ul>
        </div>

        <!-- Attendance Reports -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">Attendance Reports</h3>
            </div>
            <ul class="space-y-2">
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Daily Attendance Report</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Monthly Attendance Summary</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Class-wise Attendance</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Absentee Analysis</a></li>
            </ul>
        </div>

        <!-- Financial Reports -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">Financial Reports</h3>
            </div>
            <ul class="space-y-2">
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Fee Collection Report</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Outstanding Fees Report</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Revenue Analysis</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Budget vs Actual</a></li>
            </ul>
        </div>

        <!-- Administrative Reports -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-users-cog text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">Administrative Reports</h3>
            </div>
            <ul class="space-y-2">
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Staff Directory</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Student Directory</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Class Allocation Report</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Timetable Report</a></li>
            </ul>
        </div>

        <!-- Statistical Reports -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-chart-bar text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">Statistical Reports</h3>
            </div>
            <ul class="space-y-2">
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Enrollment Statistics</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Performance Trends</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Demographic Analysis</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Comparative Analysis</a></li>
            </ul>
        </div>

        <!-- Custom Reports -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                    <i class="fas fa-cogs text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">Custom Reports</h3>
            </div>
            <ul class="space-y-2">
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Create Custom Report</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Saved Reports</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Report Templates</a></li>
                <li><a href="#" class="text-blue-600 hover:text-blue-800">Export Options</a></li>
            </ul>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Reports</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Generated By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Class 1A Performance Report</div>
                            <div class="text-sm text-gray-500">December 2024</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Academic</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Dr. Wilson</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Dec 15, 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="#" class="text-blue-600 hover:text-blue-900">View</a>
                                <a href="#" class="text-green-600 hover:text-green-900">Download</a>
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Share</a>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Monthly Attendance Summary</div>
                            <div class="text-sm text-gray-500">November 2024</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Attendance</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Admin</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Dec 1, 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="#" class="text-blue-600 hover:text-blue-900">View</a>
                                <a href="#" class="text-green-600 hover:text-green-900">Download</a>
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Share</a>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Fee Collection Report</div>
                            <div class="text-sm text-gray-500">Q4 2024</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Financial</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Finance Dept</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Nov 30, 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="#" class="text-blue-600 hover:text-blue-900">View</a>
                                <a href="#" class="text-green-600 hover:text-green-900">Download</a>
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Share</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
