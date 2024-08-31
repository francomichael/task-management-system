<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\TaskAssigned;
use App\Models\ActivityLog;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function storeTask(Request $request)
    {
      $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'due_date' => 'nullable|date',
        'status' => 'required|string|in:pending,in_progress,completed',
        'description' => 'nullable|string',
        'assigned_to' => 'nullable|exists:users,id',
    ]);

    $task = Task::create([
        'title' => $validatedData['title'],
        'due_date' => $validatedData['due_date'],
        'status' => $validatedData['status'],
        'description' => $validatedData['description'],
        'assigned_to' => $validatedData['assigned_to'],
        'created_by' => $request->user()->id
    ]);

    ActivityLog::create([
        'task_id' => $task->id,
        'user_id' => $request->user()->id,
        'action' => 'created',
        'details' => "Task created with title '{$task->title}'"
    ]);

    if ($task->assigned_to) {
        $assignedUser = User::find($task->assigned_to);
        if ($assignedUser) {
            try {
                Mail::to($assignedUser->email)->send(new TaskAssigned($task));
                Log::info('Email sent successfully to ' . $assignedUser->email);
            } catch (\Exception $e) {
                Log::error('Failed to send email: ' . $e->getMessage());
            }
        }
    }

    return response()->json(['success' => true], 201); 
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'status' => 'required|string|in:pending,in_progress,completed',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

   

        $task = Task::create([
            'title' => $validatedData['title'],
            'due_date' => $validatedData['due_date'],
            'status' => $validatedData['status'],
            'description' => $validatedData['description'],
            'assigned_to' => $validatedData['assigned_to'],
            'created_by' =>  $request->user()->id
        ]);

        ActivityLog::create([
            'task_id' => $task->id,
            'user_id' => $request->user()->id,
            'action' => 'created',
            'details' => "Task created with title '{$task->title}'"
        ]);

        if ($task->assigned_to) {
            $assignedUser = User::find($task->assigned_to);
            if ($assignedUser) {
                try {
                    Mail::to($assignedUser->email)->send(new TaskAssigned($task));
                    Log::info('Email sent successfully to ' . $assignedUser->email);
                } catch (\Exception $e) {
                    Log::error('Failed to send email: ' . $e->getMessage());
                }
            }
        }


        return view('create-task');
    }



    public function one(Request $request)
    {

        $task = Task::find($request->id);

        if (!$task) {
            return redirect()->back()->with('error', 'Task not found.');
        }
        $currentUserId = auth()->id(); // Get the ID of the currently authenticated user
        $users = User::all();
        $isAdmin = auth()->user()->role === 'admin';



        $query = Task::query();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by due date
        if ($request->has('due_date') && $request->due_date) {
            $query->whereDate('due_date', $request->due_date);
        }

        // Search by title and description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        $tasks = $query->get();

        return view('task-single-view', compact('task','tasks', 'currentUserId', 'users', 'isAdmin'));
    }
    public function create(Request $request)
    {
        $users = User::all();

        $query = Task::query();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by due date
        if ($request->has('due_date') && $request->due_date) {
            $query->whereDate('due_date', $request->due_date);
        }

        // Search by title and description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        $tasks = $query->get();

        return view('create-task',compact('users','tasks'));
    }


    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Capture the old assigned user ID before updating

        $oldTitle = $task->title;
        $oldDueDate = $task->due_date;
        $oldStatus = $task->status;
        $oldAssignedTo = $task->assigned_to;


        // Update task fields including the assigned user
        $task->update([
            'title' => $request->input('title'),
            'due_date' => $request->input('due_date'),
            'status' => $request->input('status'),
            'description' => $request->input('description'),
            'assigned_to' => $request->input('assigned_to') // Update assigned user
        ]);

        // Convert due_date to DateTime if needed
        $task->due_date = \Carbon\Carbon::parse($task->due_date);


        if ($oldTitle !== $task->title) {
            ActivityLog::create([
                'task_id' => $task->id,
                'user_id' => $request->user()->id,
                'action' => 'updated_title',
                'details' => "Title changed from '$oldTitle' to '{$task->title}'"
            ]);
        }
        if ($oldStatus !== $task->status) {
            ActivityLog::create([
                'task_id' => $task->id,
                'user_id' => $request->user()->id,
                'action' => 'updated_title',
                'details' => "Status changed from '$oldStatus' to '{$task->status}'"
            ]);
        }
        if ($oldDueDate !== $task->due_date) {
            ActivityLog::create([
                'task_id' => $task->id,
                'user_id' => $request->user()->id,
                'action' => 'updated_title',
                'details' => "Due Date changed from '$oldDueDate' to '{$task->due_date}'"
            ]);
        }


        // Create an activity log if the assigned user changes
        $newAssignedTo = $task->assigned_to;

        // Check if the assigned user has changed
        if ($oldAssignedTo !== (int) $request->input('assigned_to')) {
            // Create an activity log entry for assignment change
            ActivityLog::create([
                'task_id' => $task->id,
                'user_id' => $request->user()->id,
                'action' => 'assigned',
                'details' => 'Task assigned to user ID: ' . $newAssignedTo
            ]);

            // Send email notification to the newly assigned user
            if ($newAssignedTo) {
                $assignedUser = User::find($newAssignedTo);
                if ($assignedUser) {
                    try {
                        Mail::to($assignedUser->email)->send(new TaskAssigned($task));
                        Log::info('Email sent successfully to ' . $assignedUser->email);
                    } catch (\Exception $e) {
                        Log::error('Failed to send email: ' . $e->getMessage());
                    }
                }
            }
        }

        return response()->json(['success' => true]);
    }


    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['success' => true]);
    }
}
