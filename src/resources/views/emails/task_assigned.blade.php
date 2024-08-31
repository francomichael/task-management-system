Task Assigned

You have been assigned a new task:

Title: **{{ $task->title }}**

Description:
{{ $task->description }}

Due Date: *{{ $task->due_date->format('M d, Y') }}*

Please check the task details and update as necessary.

Thanks,
{{ config('app.name') }}
