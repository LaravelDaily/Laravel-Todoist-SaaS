@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.task.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tasks.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.task.fields.id') }}
                        </th>
                        <td>
                            {{ $task->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.task.fields.name') }}
                        </th>
                        <td>
                            {{ $task->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.task.fields.description') }}
                        </th>
                        <td>
                            {{ $task->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.task.fields.project') }}
                        </th>
                        <td>
                            {{ $task->project->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.task.fields.due_date') }}
                        </th>
                        <td>
                            {{ $task->due_date ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.task.fields.send_reminder') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ Gate::check('reminders') && $task->send_reminder ? 'checked' : '' }}>
                            @cannot('reminders')
                                <br>
                                <small>Only Premium plan. <a href="{{ route('admin.billing.index') }}">Upgrade your plan</a></small>
                            @endcannot
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tasks.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Comments
    </div>

    <div class="card-body" id="comments">
        @forelse($task->comments as $comment)
            <strong>{{ $comment->user->name }}</strong> ({{ $comment->created_at }}) <br />
            {{ $comment->comment_text }} <hr />
        @empty
            There are no comments <hr />
        @endforelse
        <form action="{{ route('admin.tasks.comment', $task->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="comment_text">Comment Text</label>
                <textarea
                    name="comment_text"
                    id="comment_text"
                    class="form-control{{ $errors->has('comment_text') ? ' is-invalid' : '' }}"
                    required
                    {{ Gate::denies('comments') ? 'disabled' : '' }}
                >{{ old('comment_text', '') }}</textarea>
                @if($errors->has('comment_text'))
                    <div class="invalid-feedback">
                        {{ $errors->first('comment_text') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit"{{ Gate::denies('comments') ? ' disabled' : '' }}>
                    Post
                </button>
            </div>
            @cannot('comments')
                <div class="form-group">
                    Only Premium plan. <a href="{{ route('admin.billing.index') }}">Upgrade your plan</a>
                </div>
            @endcannot
        </form>
    </div>
</div>
@endsection
