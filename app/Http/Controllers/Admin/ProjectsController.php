<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Notifications\ExistingUserNotification;
use App\Notifications\NewUserNotification;
use App\Project;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;

class ProjectsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::all();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        abort_if(Gate::denies('project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.projects.create');
    }

    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->all());

        return redirect()->route('admin.projects.index');
    }

    public function edit(Project $project)
    {
        abort_if(Gate::denies('project_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->all());

        return redirect()->route('admin.projects.index');
    }

    public function show(Project $project)
    {
        abort_if(Gate::denies('project_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->load('projectTasks');

        $collaborators = DB::table('project_user')->leftJoin('users as user', 'user_id', '=', 'id')->get();

        return view('admin.projects.show', compact('project', 'collaborators'));
    }

    public function destroy(Project $project)
    {
        abort_if(Gate::denies('project_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->delete();

        return back();
    }

    public function massDestroy(MassDestroyProjectRequest $request)
    {
        Project::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function invite(Request $request, Project $project)
    {
        abort_if(!auth()->user()->is_admin && $project->created_by_id != auth()->id(), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        $table = DB::table('project_user');
        $isPremium = $project->created_by ? $project->created_by->is_premium : true;

        if ($isPremium || (!$isPremium && $table->where('project_id', $project->id)->count() < 5)) {
            if ($user && !$project->collaborators()->where('id', $user->id)->exists()) {
                $project->collaborators()->attach($user->id, [
                    'email' => $request->input('email'),
                ]);

                $user->notify(new ExistingUserNotification($project->id));
            } else if (!$table->where('email', $request->input('email'))->exists()) {
                Notification::route('mail', $request->input('email'))->notify(new NewUserNotification());

                $project->collaborators()->attach([ null ], [
                    'email' => $request->input('email')
                ]);
            } else {
                return back()->withErrors([
                    'email' => 'This person is already invited'
                ]);
            }

            return back()->withMessage('Person has been invited');
        } else {
            return back()->withErrors([
                'email' => 'You have reached the free plan limit (5 people invited). Upgrade to invite more'
            ]);
        }
    }

    public function acceptInvitation(Request $request, Project $project)
    {
        $invitation = $project->collaborators()->where('id', auth()->id())->first();

        if ($invitation && $invitation->pivot->confirmed_at == null) {
            $project->collaborators()->updateExistingPivot(auth()->id(), [
                'confirmed_at' => now(),
            ]);

            return redirect()->route('admin.projects.show', $project->id)->withMessage('Invitation has been successfully accepted');
        } else {
            return redirect()->route('admin.projects.index')->withErrors('Invitation does not exist');
        }
    }
}
