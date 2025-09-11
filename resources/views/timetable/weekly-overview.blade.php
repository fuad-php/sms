@extends('layouts.app')

@section('title', __('app.weekly_timetable_overview'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('app.weekly_timetable_overview') }}</h1>
                    <p class="text-gray-600">{{ __('app.view_weekly_class_schedules') }}</p>
                </div>
                <div class="flex space-x-3">
                    <button id="toggleEditMode" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span id="editModeText">Edit Mode</span>
                    </button>
                    <a href="{{ route('timetable.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        {{ __('app.add_timetable') }}
                    </a>
                    <a href="{{ route('timetable.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        {{ __('app.list_view') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Class Filter -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.filter_by_class') }}</label>
                    <select name="class_id" id="class_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">{{ __('app.all_classes') }}</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="section_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.section') }}</label>
                    <select name="section_id" id="section_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">{{ __('app.all_sections') }}</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.teacher') }}</label>
                    <select name="teacher_id" id="teacher_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">{{ __('app.all_teachers') }}</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full">
                        {{ __('app.filter') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Weekly Grid -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('app.weekly_schedule') }}</h3>
            </div>
            
            <!-- Days Header -->
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 text-center border-r">
                    <h4 class="text-sm font-medium text-gray-900">{{ __('app.time') }}</h4>
                </div>
                @foreach($days as $day)
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 text-center">
                    <h4 class="text-sm font-medium text-gray-900 capitalize">{{ $day }}</h4>
                </div>
                @endforeach
            </div>

            <!-- Time Slots -->
            @foreach($timeSlots ?? [] as $slot)
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                <!-- Time Column -->
                <div class="bg-white p-3 text-center border-r">
                    <span class="text-sm font-medium text-gray-900">{{ $slot['display'] ?? '' }}</span>
                </div>
                
                <!-- Day Columns -->
                @foreach($days as $day)
                <div class="bg-white p-2 min-h-[100px] relative hover:bg-gray-50 transition-colors duration-200" 
                     data-day="{{ $day }}" 
                     data-time="{{ $slot['start'] ?? '' }}"
                     data-timetable-slot>
                    @php
                        $dayTimetables = $timetables->get($day, collect());
                        $timeSlotTimetables = $dayTimetables->filter(function($timetable) use ($slot) {
                            if (!$timetable || !$timetable->start_time || !isset($slot['start'])) {
                                return false;
                            }
                            return $timetable->start_time->format('H:i') === $slot['start'];
                        });
                    @endphp
                    
                    @foreach($timeSlotTimetables as $timetable)
                        @if(request('class_id') == '' || $timetable->class_id == request('class_id'))
                        <div class="mb-2 p-3 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg text-xs shadow-sm hover:shadow-md transition-all duration-200 cursor-move timetable-entry" 
                             data-timetable-id="{{ $timetable->id ?? '' }}"
                             data-subject="{{ $timetable->subject->name ?? 'N/A' }}"
                             data-class="{{ $timetable->class->name ?? 'N/A' }}"
                             data-teacher="{{ $timetable->teacher->name ?? 'N/A' }}"
                             data-room="{{ $timetable->room ?? '' }}"
                             draggable="true">
                            <div class="flex justify-between items-start mb-1">
                                <div class="font-semibold text-blue-900 truncate">{{ $timetable->subject->name ?? 'N/A' }}</div>
                                <div class="flex space-x-1">
                                    <button class="text-blue-600 hover:text-blue-800 edit-timetable" data-id="{{ $timetable->id ?? '' }}">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800 delete-timetable" data-id="{{ $timetable->id ?? '' }}">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="text-blue-700 text-xs">{{ $timetable->class->name ?? 'N/A' }}</div>
                            <div class="text-blue-600 text-xs">{{ $timetable->teacher->name ?? 'N/A' }}</div>
                            @if($timetable->room)
                                <div class="text-blue-500 text-xs flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ $timetable->room }}
                                </div>
                            @endif
                        </div>
                        @endif
                    @endforeach
                    
                    <!-- Add new timetable button (visible in edit mode) -->
                    <div class="add-timetable-btn hidden absolute inset-0 flex items-center justify-center bg-gray-100 bg-opacity-50 rounded-lg border-2 border-dashed border-gray-300 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200">
                        <button class="text-gray-400 hover:text-blue-600 p-2" data-day="{{ $day }}" data-time="{{ $slot['start'] ?? '' }}">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>

        <!-- Legend -->
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <h4 class="text-sm font-medium text-gray-900 mb-3">{{ __('app.legend') }}</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-50 border border-blue-200 rounded mr-2"></div>
                    <span class="text-gray-700">{{ __('app.subject') }} - {{ __('app.class') }} - {{ __('app.teacher') }}</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-50 border border-green-200 rounded mr-2"></div>
                    <span class="text-gray-700">{{ __('app.current_period') }}</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-50 border border-yellow-200 rounded mr-2"></div>
                    <span class="text-gray-700">{{ __('app.upcoming') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Edit Modal -->
<div id="quickEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Quick Edit Timetable</h3>
            </div>
            <form id="quickEditForm" class="p-6">
                <input type="hidden" id="editTimetableId" name="timetable_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subject</label>
                        <input type="text" id="editSubject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Class</label>
                        <input type="text" id="editClass" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Teacher</label>
                        <input type="text" id="editTeacher" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Room</label>
                        <input type="text" id="editRoom" name="room" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelEdit" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let editMode = false;
    let draggedElement = null;
    
    // Toggle edit mode
    const toggleEditBtn = document.getElementById('toggleEditMode');
    const editModeText = document.getElementById('editModeText');
    const addButtons = document.querySelectorAll('.add-timetable-btn');
    
    toggleEditBtn.addEventListener('click', function() {
        editMode = !editMode;
        
        if (editMode) {
            editModeText.textContent = 'Exit Edit';
            toggleEditBtn.classList.remove('bg-purple-600', 'hover:bg-purple-700');
            toggleEditBtn.classList.add('bg-red-600', 'hover:bg-red-700');
            addButtons.forEach(btn => btn.classList.remove('hidden'));
        } else {
            editModeText.textContent = 'Edit Mode';
            toggleEditBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
            toggleEditBtn.classList.add('bg-purple-600', 'hover:bg-purple-700');
            addButtons.forEach(btn => btn.classList.add('hidden'));
        }
    });
    
    // Drag and drop functionality
    const timetableEntries = document.querySelectorAll('.timetable-entry');
    const timetableSlots = document.querySelectorAll('[data-timetable-slot]');
    
    timetableEntries.forEach(entry => {
        entry.addEventListener('dragstart', function(e) {
            if (!editMode) {
                e.preventDefault();
                return;
            }
            draggedElement = this;
            this.style.opacity = '0.5';
        });
        
        entry.addEventListener('dragend', function(e) {
            this.style.opacity = '1';
            draggedElement = null;
        });
    });
    
    timetableSlots.forEach(slot => {
        slot.addEventListener('dragover', function(e) {
            if (!editMode) return;
            e.preventDefault();
            this.classList.add('bg-blue-50');
        });
        
        slot.addEventListener('dragleave', function(e) {
            this.classList.remove('bg-blue-50');
        });
        
        slot.addEventListener('drop', function(e) {
            if (!editMode || !draggedElement) return;
            e.preventDefault();
            this.classList.remove('bg-blue-50');
            
            // Move the timetable entry to new slot
            const newDay = this.dataset.day;
            const newTime = this.dataset.time;
            const timetableId = draggedElement.dataset.timetableId;
            
            // Update timetable via AJAX
            updateTimetableSlot(timetableId, newDay, newTime);
            
            // Move element in DOM
            this.appendChild(draggedElement);
        });
    });
    
    // Edit timetable functionality
    document.querySelectorAll('.edit-timetable').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const timetableId = this.dataset.id;
            const entry = this.closest('.timetable-entry');
            
            // Populate modal
            document.getElementById('editTimetableId').value = timetableId;
            document.getElementById('editSubject').value = entry.dataset.subject;
            document.getElementById('editClass').value = entry.dataset.class;
            document.getElementById('editTeacher').value = entry.dataset.teacher;
            document.getElementById('editRoom').value = entry.dataset.room;
            
            // Show modal
            document.getElementById('quickEditModal').classList.remove('hidden');
        });
    });
    
    // Delete timetable functionality
    document.querySelectorAll('.delete-timetable').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const timetableId = this.dataset.id;
            
            if (confirm('Are you sure you want to delete this timetable entry?')) {
                deleteTimetable(timetableId);
            }
        });
    });
    
    // Add new timetable functionality
    document.querySelectorAll('.add-timetable-btn button').forEach(btn => {
        btn.addEventListener('click', function() {
            const day = this.dataset.day;
            const time = this.dataset.time;
            
            // Redirect to create timetable with pre-filled data
            const url = new URL('{{ route("timetable.create") }}', window.location.origin);
            url.searchParams.set('day', day);
            url.searchParams.set('time', time);
            window.location.href = url.toString();
        });
    });
    
    // Modal functionality
    document.getElementById('cancelEdit').addEventListener('click', function() {
        document.getElementById('quickEditModal').classList.add('hidden');
    });
    
    document.getElementById('quickEditForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const timetableId = formData.get('timetable_id');
        const room = formData.get('room');
        
        updateTimetableRoom(timetableId, room);
    });
    
    // Close modal on outside click
    document.getElementById('quickEditModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
    
    // AJAX functions
    function updateTimetableSlot(timetableId, newDay, newTime) {
        fetch(`/timetable/${timetableId}/move`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                day: newDay,
                time: newTime
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Timetable updated successfully', 'success');
            } else {
                showNotification('Failed to update timetable', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
    }
    
    function updateTimetableRoom(timetableId, room) {
        fetch(`/timetable/${timetableId}/room`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                room: room
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Room updated successfully', 'success');
                document.getElementById('quickEditModal').classList.add('hidden');
                // Update the room display in the timetable entry
                const entry = document.querySelector(`[data-timetable-id="${timetableId}"]`);
                if (entry) {
                    entry.dataset.room = room;
                    const roomDisplay = entry.querySelector('.text-blue-500');
                    if (roomDisplay) {
                        roomDisplay.textContent = room;
                    } else if (room) {
                        const roomDiv = document.createElement('div');
                        roomDiv.className = 'text-blue-500 text-xs flex items-center';
                        roomDiv.innerHTML = `
                            <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            ${room}
                        `;
                        entry.appendChild(roomDiv);
                    }
                }
            } else {
                showNotification('Failed to update room', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
    }
    
    function deleteTimetable(timetableId) {
        fetch(`/timetable/${timetableId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Timetable deleted successfully', 'success');
                // Remove the timetable entry from DOM
                const entry = document.querySelector(`[data-timetable-id="${timetableId}"]`);
                if (entry) {
                    entry.remove();
                }
            } else {
                showNotification('Failed to delete timetable', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
    }
    
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});
</script>
@endpush
