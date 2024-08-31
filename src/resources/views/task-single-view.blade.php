<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Task Management System
        </h2>
    </x-slot>




    <div class="py-12" x-data="taskData()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          
            <form id="updateTaskForm" method="POST">
                @csrf
                @method('PUT')
            
                <div id="cardModalx" style="display: block" class="modal">
                    @php
                    $isCreator = $task->created_by === $currentUserId;
                @endphp
                    <div class="modal-content bg-white p-6 rounded shadow-md relative">
                        @if (!$isAdmin && !$isCreator)
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">View Only</span>

                        @endif
                     

                        <div class="mt-3">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ $task->title }}" 
                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500" 
                                {{ !$isAdmin && !$isCreator ? 'disabled' : '' }}>  
                        </div>
            
                        <div class="mt-3">
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                            <input type="date" name="due_date" id="due_date" value="{{ $task->due_date }}" 
                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500" 
                                {{ !$isAdmin && !$isCreator ? 'disabled' : '' }}>
                        </div>

            
                        <div class="mt-3">
                            <label for="status" class="block text-sm font-medium text-gray-700">Change Status</label>
                            <select name="status" id="status" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg px-10 focus:ring-blue-500 focus:border-blue-500 block w-full" 
                                {{ !$isAdmin && !$isCreator ? 'disabled' : '' }}>
                                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
            
                        <div class="mt-3">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" 
                                {{ !$isAdmin && !$isCreator ? 'disabled' : '' }}>{{ $task->description }}</textarea>
                        </div>

                        <div class="mt-3">
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assign to</label>
                            <select name="assigned_to" id="assigned_to" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg px-4 focus:ring-blue-500 focus:border-blue-500 block w-full"
                                {{ !$isAdmin && !$isCreator ? 'disabled' : '' }}>
                                <option value="">Unassigned</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
            
                        <div class="mt-6 flex justify-end space-x-3">
                            @if ($task->created_by === $currentUserId)
                                <button type="button" id="deleteTask" class="px-3 py-2 text-xs font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300">Delete</button>
                                <button type="submit" class="px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">Save</button>
                            @endif
                        </div>
            
                        <div class="mt-3">
                            <label for="task-activity-log" class="block text-sm font-medium text-gray-700">Task Activity Log</label>
                            <div class="bg-gray-50 p-4 rounded-lg shadow-md mt-2">
                                @if($task->activityLogs->isEmpty())
                                    <p class="text-gray-500 text-sm">No activity log available for this task.</p>
                                @else
                                    <ul class="ml-5 list-disc space-y-2">
                                        @foreach ($task->activityLogs->reverse() as $log)
                                            <li class="flex items-start space-x-2">
                                                <div class="flex-shrink-0">
                                                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-blue-500 mt-1"></span>
                                                </div>
                                                <div class="flex-1 text-sm">
                                                    <p class="text-gray-700 font-semibold">{{ $log->action }} by User ID: {{ $log->user_id }}</p>
                                                    <p class="text-gray-500">{{ $log->details }}</p>
                                                    <p class="text-gray-400 text-xs">{{ $log->created_at->format('M d, Y h:i A') }}</p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            


            <div class="flex flex-col space-y-4 md:flex-row md:items-center md:space-x-4 md:space-y-0">
                <a href="/task/create"
                    class="px-4 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full md:w-36 h-10 flex items-center justify-center">
                    Create New Task
                </a>

                <div class="w-full md:w-36">
                    <select id="countries"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-10">
                        <option value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Complete</option>
                    </select>
                </div>

                <div class="w-full md:w-36">
                    <input type="date" id="small-input"
                        class="block w-full h-10 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="w-full md:w-36">
                    <input type="text" placeholder="Search" id="small-input"
                        class="block w-full h-10 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="w-full md:w-36">
                    <button type="button"
                        class="px-4 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 w-full h-10 flex items-center justify-center">
                        Search
                    </button>
                </div>
            </div>


            <div class="flex space-x-4 mt-5">
                <!-- Column 1 - Pending -->
                <div class="bg-white p-4 rounded  shadow-md w-1/3">
                    <h2 class="text-xl font-bold mb-4">Pending</h2>
                    <div class="space-y-4">
                        @foreach ($tasks as $task)
                            @if ($task['status'] === 'pending')
                                <a href="{{ url('/task/view/' . $task['id']) }}"
                                    class="bg-gray-50 p-4 border border-gray-300 rounded shadow-sm cursor-pointer block">
                                    <h3 class="text-lg font-semibold">{{ $task['title'] }}</h3>
                                    <p class="text-gray-600">{{ $task['description'] }}</p>
                                    <p class="text-gray-600">
                                        <span class="font-semibold">Due date:</span>
                                        <span
                                            class="text-blue-500">{{ \Carbon\Carbon::parse($task['due_date'])->format('M d, Y') }}</span>
                                    </p>
                                    <p class="text-gray-600">
                                        <span class="font-semibold">Assigned to:</span>
                                        {{ $task['assigned_to'] ? $task['assigned_to'] : 'Not assigned yet' }}
                                    </p>
                                   
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Column 2 - In Progress -->
                <div class="bg-white p-4 rounded shadow-md w-1/3">
                    <h2 class="text-xl font-bold mb-4">In Progress</h2>
                    <div class="space-y-4">
                        @foreach ($tasks as $task)
                            @if ($task['status'] === 'in_progress')
                                <a href="{{ url('/task/view/' . $task['id']) }}"
                                    class="bg-gray-50 p-4 border border-gray-300 rounded shadow-sm cursor-pointer block">
                                    <h3 class="text-lg font-semibold">{{ $task['title'] }}</h3>
                                    <p class="text-gray-600">{{ $task['description'] }}</p>
                                    <p class="text-gray-600">
                                        <span class="font-semibold">Due date:</span>
                                        <span
                                            class="text-blue-500">{{ \Carbon\Carbon::parse($task['due_date'])->format('M d, Y') }}</span>
                                    </p>
                                    <p class="text-gray-600">
                                        <span class="font-semibold">Assigned to:</span>
                                        {{ $task['assigned_to'] ? $task['assigned_to'] : 'Not assigned yet' }}
                                    </p>
                                    
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Column 3 - Completed -->
                <div class="bg-white p-4 rounded shadow-md w-1/3">
                    <h2 class="text-xl font-bold mb-4">Completed</h2>
                    <div class="space-y-4">
                        @foreach ($tasks as $task)
                            @if ($task['status'] === 'completed')
                                <a href="{{ url('/task/view/' . $task['id']) }}"
                                    class="bg-gray-50 p-4 border border-gray-300 rounded shadow-sm cursor-pointer block">
                                    <h3 class="text-lg font-semibold">{{ $task['title'] }}</h3>
                                    <p class="text-gray-600">{{ $task['description'] }}</p>
                                    <p class="text-gray-600">
                                        <span class="font-semibold">Due date:</span>
                                        <span
                                            class="text-blue-500">{{ \Carbon\Carbon::parse($task['due_date'])->format('M d, Y') }}</span>
                                    </p>
                                    <p class="text-gray-600">
                                        <span class="font-semibold">Assigned to:</span>
                                        {{ $task['assigned_to'] ? $task['assigned_to'] : 'Not assigned yet' }}
                                    </p>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('cardModalx').onclick = function(event) {
            // Check if the click was on the modal background
            if (event.target === document.getElementById('cardModalx')) {
                window.location.href = "{{ route('dashboard') }}";
            }
        };


        document.getElementById('updateTaskForm').onsubmit = function(event) {
    event.preventDefault();
    
    const taskId = {{ $task->id }};
    const formData = new FormData(this);

    axios.post(`/task/update/${taskId}`, formData)
        .then(response => {
            alert('Task updated successfully');
            window.location.href = "{{ route('dashboard') }}";
        })
        .catch(error => {
            console.error('Error updating task:', error);
            alert('Failed to update task');
        });
};

// Handle Task Deletion
document.getElementById('deleteTask').onclick = function() {
    if (confirm('Are you sure you want to delete this task?')) {
        const taskId = {{ $task->id }};
        
        axios.delete(`/task/delete/${taskId}`)
            .then(response => {
                alert('Task deleted successfully');
                // Optionally redirect or update the UI here
                window.location.href = "{{ route('dashboard') }}";
            })
            .catch(error => {
                console.error('Error deleting task:', error);
                alert('Failed to delete task');
            });
    }
};

    </script>

    <script>
        function taskData() {
            return {
                tasks: [],
                init() {
                    this.fetchTasks();
                },
                fetchTasks() {
                    console.log("sss");

                    axios.get('/axios/get', {})
                        .then(response => {
                            this.tasks = response.data;
                        })
                        .catch(error => {
                            console.error('Error fetching tasks:', error);
                        });
                },
                deleteTask(taskId) {
                    if (confirm('Are you sure you want to delete this task?')) {
                        axios.delete(`/api/axios/get`)
                            .then(response => {
                                this.tasks = this.tasks.filter(task => task.id !== taskId);
                            })
                            .catch(error => {
                                console.error('Error deleting task:', error);
                            });
                    }
                }
            }
        }
    </script>
</x-app-layout>
