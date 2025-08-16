@extends('layouts.app')

@section('title', 'Timetable Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Timetable Management</h1>
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Create Timetable
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                <select name="class" id="class" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Class</option>
                    <option value="1">Class 1A</option>
                    <option value="2">Class 1B</option>
                    <option value="3">Class 2A</option>
                    <option value="4">Class 2B</option>
                </select>
            </div>
            
            <div>
                <label for="teacher" class="block text-sm font-medium text-gray-700 mb-1">Teacher</label>
                <select name="teacher" id="teacher" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Teacher</option>
                    <option value="1">Dr. David Wilson</option>
                    <option value="2">Ms. Sarah Brown</option>
                    <option value="3">Mr. Michael Chen</option>
                </select>
            </div>
            
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <select name="subject" id="subject" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Subject</option>
                    <option value="1">Mathematics</option>
                    <option value="2">English Literature</option>
                    <option value="3">Physics</option>
                    <option value="4">Chemistry</option>
                </select>
            </div>
            
            <div>
                <label for="day" class="block text-sm font-medium text-gray-700 mb-1">Day</label>
                <select name="day" id="day" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Days</option>
                    <option value="monday">Monday</option>
                    <option value="tuesday">Tuesday</option>
                    <option value="wednesday">Wednesday</option>
                    <option value="thursday">Thursday</option>
                    <option value="friday">Friday</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Timetable Display -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Class 1A Timetable</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monday</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tuesday</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wednesday</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thursday</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Friday</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">8:00 - 9:00</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-blue-100 p-2 rounded">
                                <div class="text-sm font-medium text-blue-900">Mathematics</div>
                                <div class="text-xs text-blue-700">Dr. Wilson</div>
                                <div class="text-xs text-blue-600">Room 101</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-green-100 p-2 rounded">
                                <div class="text-sm font-medium text-green-900">English</div>
                                <div class="text-xs text-green-700">Ms. Brown</div>
                                <div class="text-xs text-green-600">Room 102</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-purple-100 p-2 rounded">
                                <div class="text-sm font-medium text-purple-900">Physics</div>
                                <div class="text-xs text-purple-700">Mr. Chen</div>
                                <div class="text-xs text-purple-600">Lab 1</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-yellow-100 p-2 rounded">
                                <div class="text-sm font-medium text-yellow-900">History</div>
                                <div class="text-xs text-yellow-700">Mrs. Davis</div>
                                <div class="text-xs text-yellow-600">Room 103</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-red-100 p-2 rounded">
                                <div class="text-sm font-medium text-red-900">Art</div>
                                <div class="text-xs text-red-700">Mr. Johnson</div>
                                <div class="text-xs text-red-600">Art Room</div>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">9:00 - 10:00</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-green-100 p-2 rounded">
                                <div class="text-sm font-medium text-green-900">English</div>
                                <div class="text-xs text-green-700">Ms. Brown</div>
                                <div class="text-xs text-green-600">Room 102</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-blue-100 p-2 rounded">
                                <div class="text-sm font-medium text-blue-900">Mathematics</div>
                                <div class="text-xs text-blue-700">Dr. Wilson</div>
                                <div class="text-xs text-blue-600">Room 101</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-yellow-100 p-2 rounded">
                                <div class="text-sm font-medium text-yellow-900">History</div>
                                <div class="text-xs text-yellow-700">Mrs. Davis</div>
                                <div class="text-xs text-yellow-600">Room 103</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-purple-100 p-2 rounded">
                                <div class="text-sm font-medium text-purple-900">Physics</div>
                                <div class="text-xs text-purple-700">Mr. Chen</div>
                                <div class="text-xs text-purple-600">Lab 1</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-blue-100 p-2 rounded">
                                <div class="text-sm font-medium text-blue-900">Mathematics</div>
                                <div class="text-xs text-blue-700">Dr. Wilson</div>
                                <div class="text-xs text-blue-600">Room 101</div>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">10:00 - 10:15</td>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 bg-gray-50">
                            Break
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">10:15 - 11:15</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-purple-100 p-2 rounded">
                                <div class="text-sm font-medium text-purple-900">Physics</div>
                                <div class="text-xs text-purple-700">Mr. Chen</div>
                                <div class="text-xs text-purple-600">Lab 1</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-yellow-100 p-2 rounded">
                                <div class="text-sm font-medium text-yellow-900">History</div>
                                <div class="text-xs text-yellow-700">Mrs. Davis</div>
                                <div class="text-xs text-yellow-600">Room 103</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-green-100 p-2 rounded">
                                <div class="text-sm font-medium text-green-900">English</div>
                                <div class="text-xs text-green-700">Ms. Brown</div>
                                <div class="text-xs text-green-600">Room 102</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-blue-100 p-2 rounded">
                                <div class="text-sm font-medium text-blue-900">Mathematics</div>
                                <div class="text-xs text-blue-700">Dr. Wilson</div>
                                <div class="text-xs text-blue-600">Room 101</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-red-100 p-2 rounded">
                                <div class="text-sm font-medium text-red-900">Art</div>
                                <div class="text-xs text-red-700">Mr. Johnson</div>
                                <div class="text-xs text-red-600">Art Room</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Legend -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Legend</h4>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-blue-100 rounded mr-2"></div>
                <span class="text-sm text-gray-700">Mathematics</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-100 rounded mr-2"></div>
                <span class="text-sm text-gray-700">English</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-purple-100 rounded mr-2"></div>
                <span class="text-sm text-gray-700">Physics</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-yellow-100 rounded mr-2"></div>
                <span class="text-sm text-gray-700">History</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-100 rounded mr-2"></div>
                <span class="text-sm text-gray-700">Art</span>
            </div>
        </div>
    </div>
</div>
@endsection
