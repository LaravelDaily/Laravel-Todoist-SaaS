@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.task.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.tasks.update", [$task->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="name">{{ trans('cruds.task.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $task->name) }}">
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.task.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.task.fields.description') }}</label>
                <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', $task->description) }}">
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.task.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="project_id">{{ trans('cruds.task.fields.project') }}</label>
                <select class="form-control select2 {{ $errors->has('project_id') ? 'is-invalid' : '' }}" name="project_id" id="project_id">
                    @foreach($projects as $id => $project)
                        <option value="{{ $id }}" {{ ($task->project ? $task->project->id : old('project_id')) == $id ? 'selected' : '' }}>{{ $project }}</option>
                    @endforeach
                </select>
                @if($errors->has('project_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('project_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.task.fields.project_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="due_date">{{ trans('cruds.task.fields.due_date') }}</label>
                <input class="form-control date {{ $errors->has('due_date') ? 'is-invalid' : '' }}" type="text" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date) }}">
                @if($errors->has('due_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('due_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.task.fields.due_date_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('send_reminder') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="send_reminder" value="0">
                    <input class="form-check-input"
                        type="checkbox"
                        name="send_reminder"
                        id="send_reminder"
                        value="1"
                        {{ Gate::check('reminders') && $task->send_reminder || old('send_reminder', 0) === 1 ? 'checked' : '' }}
                        {{ !Gate::check('reminders') ? 'disabled' : '' }}
                    >
                    <label class="form-check-label" for="send_reminder">{{ trans('cruds.task.fields.send_reminder') }}</label>
                </div>
                @if($errors->has('send_reminder'))
                    <div class="invalid-feedback">
                        {{ $errors->first('send_reminder') }}
                    </div>
                @endif
                @cannot('reminders')
                    <small>This field is only for Premium users. <a href="{{ route('admin.billing.index') }}">Upgrade your plan</a></small>
                @endcannot
                <span class="help-block">{{ trans('cruds.task.fields.send_reminder_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="labels">{{ trans('cruds.task.fields.labels') }}</label>
                @can('labels')
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                @endcan
                <select
                    class="form-control select2 {{ $errors->has('labels') ? 'is-invalid' : '' }}"
                    name="labels[]"
                    id="labels"
                    multiple
                    required
                    {{ Gate::denies('labels') ? 'disabled' : '' }}
                >
                    @foreach($labels as $id => $labels)
                        <option value="{{ $id }}" {{ (in_array($id, old('labels', [])) || $task->labels->contains($id)) ? 'selected' : '' }}>{{ $labels }}</option>
                    @endforeach
                </select>
                @if($errors->has('labels'))
                    <div class="invalid-feedback">
                        {{ $errors->first('labels') }}
                    </div>
                @endif
                @cannot('labels')
                    <small>This field is only for Premium users. <a href="{{ route('admin.billing.index') }}">Upgrade your plan</a></small>
                @endcannot
                <span class="help-block">{{ trans('cruds.task.fields.labels_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
