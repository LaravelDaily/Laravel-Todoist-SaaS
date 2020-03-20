@component('mail::message')
# Hello!

You have tasks that are due today:
@foreach($tasks as $task)
* *{{ $task->name }}* in project {{ $task->project->name }}
@endforeach

@component('mail::button', ['url' => route('admin.tasks.index')])
See All Your Tasks
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
