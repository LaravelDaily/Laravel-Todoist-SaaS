<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTaskRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Label;
use App\Project;
use App\Task;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('task_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tasks = Task::with('project', 'labels')
            ->withCount('comments')
            ->when(request()->input('label_id'), function ($query) {
                $query->whereHas('labels', function ($query) {
                    $query->where('id', request()->input('label_id'));
                });
            })
            ->get();

        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        abort_if(Gate::denies('task_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $labels   = Label::all()->pluck('name', 'id');

        return view('admin.tasks.create', compact('projects', 'labels'));
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->all());

        if (Gate::check('labels')) {
            $task->labels()->sync($request->input('labels'));
        }

        return redirect()->route('admin.tasks.index');
    }

    public function edit(Task $task)
    {
        abort_if(Gate::denies('task_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $labels   = Label::all()->pluck('name', 'id');

        $task->load('project');

        return view('admin.tasks.edit', compact('projects', 'labels', 'task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->all());

        if (Gate::check('labels')) {
            $task->labels()->sync($request->input('labels'));
        }

        return redirect()->route('admin.tasks.index');
    }

    public function show(Task $task)
    {
        abort_if(Gate::denies('task_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->load('project', 'comments.user');

        return view('admin.tasks.show', compact('task'));
    }

    public function destroy(Task $task)
    {
        abort_if(Gate::denies('task_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->delete();

        return back();
    }

    public function massDestroy(MassDestroyTaskRequest $request)
    {
        Task::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function comment(StoreCommentRequest $request, Task $task)
    {
        abort_if(Gate::denies('comments'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->comments()->create([
            'user_id'      => auth()->id(),
            'comment_text' => $request->input('comment_text'),
        ]);

        return redirect()->back()->withMessage('Comment has been successfully posted');
    }
}
