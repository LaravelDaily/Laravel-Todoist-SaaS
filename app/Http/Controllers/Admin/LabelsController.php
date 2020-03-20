<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLabelRequest;
use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;
use App\Label;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LabelsController extends Controller
{
    public function index()
    {
        $labels = Label::all();

        return view('admin.labels.index', compact('labels'));
    }

    public function create()
    {
        abort_if(Gate::denies('labels'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.labels.create');
    }

    public function store(StoreLabelRequest $request)
    {
        $label = Label::create($request->all());

        return redirect()->route('admin.labels.index');
    }

    public function edit(Label $label)
    {
        abort_if(Gate::denies('labels'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.labels.edit', compact('label'));
    }

    public function update(UpdateLabelRequest $request, Label $label)
    {
        $label->update($request->all());

        return redirect()->route('admin.labels.index');
    }

    public function show(Label $label)
    {
        abort_if(Gate::denies('labels'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.labels.show', compact('label'));
    }

    public function destroy(Label $label)
    {
        abort_if(Gate::denies('labels'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $label->delete();

        return back();
    }

    public function massDestroy(MassDestroyLabelRequest $request)
    {
        Label::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
