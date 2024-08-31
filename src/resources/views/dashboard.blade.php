<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Task Management System
        </h2>
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-col space-y-4 md:flex-row md:items-center md:space-x-4 md:space-y-0">
                <a href="/task/create"
                    class="px-4 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full md:w-36 h-10 flex items-center justify-center">
                    Create New Task
                </a>

                <div class="w-full md:w-36">
                    <select id="status-filter" name="status"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-10">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In
                            Progress</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Complete
                        </option>
                    </select>
                </div>

                <div class="w-full md:w-36">
                    <input type="date" name="due_date" id="due-date-filter" value="{{ request('due_date') }}"
                        class="block w-full h-10 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="w-full md:w-36">
                    <input type="text" name="search" placeholder="Search" id="search-filter"
                        value="{{ request('search') }}"
                        class="block w-full h-10 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="w-full md:w-36 flex space-x-2">
                    <button type="button" id="search-button"
                        class="px-4 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 w-full h-10 flex items-center justify-center">
                        Search
                    </button>
                    <button type="button" id="clear-button"
                        class="px-4 py-2 text-xs font-medium text-center text-white bg-gray-500 rounded-lg hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300 w-full h-10 flex items-center justify-center">
                        Clear
                    </button>
                </div>
            </div>

            <!-- Modal -->
            <div id="cardModal" class="modal">
                <div class="modal-content bg-white p-6 rounded shadow-md">
                    <span id="closeModal" class="absolute top-0 right-0 p-2 cursor-pointer text-gray-500">&times;</span>
                    <h2 id="modalTitle" class="text-xl font-bold mb-4">Card Details</h2>
                    <p id="modalDescription" class="text-gray-600">Details will appear here.</p>
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
        function openModal(title, description) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalDescription').innerText = description;
            document.getElementById('cardModal').style.display = 'block';
        }

        document.getElementById('closeModal').onclick = function() {
            document.getElementById('cardModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target === document.getElementById('cardModal')) {
                document.getElementById('cardModal').style.display = 'none';
            }
        }
        document.getElementById('search-button').addEventListener('click', function() {
            const status = document.getElementById('status-filter').value;
            const dueDate = document.getElementById('due-date-filter').value;
            const search = document.getElementById('search-filter').value;

            const query = new URLSearchParams({
                status,
                due_date: dueDate,
                search
            }).toString();
            window.location.href = `/dashboard?${query}`;
        });

        document.getElementById('clear-button').addEventListener('click', function() {
            // Clear the input fields
            document.getElementById('status-filter').value = 'all';
            document.getElementById('due-date-filter').value = '';
            document.getElementById('search-filter').value = '';

            // Redirect to dashboard with no query parameters
            window.location.href = '/dashboard';
        });
    </script>

    {{-- <script>
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
    </script> --}}
</x-app-layout>
