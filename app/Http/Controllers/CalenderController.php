<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;

class CalenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($slug, $project_id = NULL)
    {
        $objUser          = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if($objUser->getGuard() == 'client')
        {
            $tasks    = Task::select('tasks.*')->join('projects', 'projects.id', '=', 'tasks.project_id')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('client_projects.permission', 'LIKE', '%show task%')->where('projects.workspace', '=', $currentWorkspace->id);
            $projects = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
        }
        elseif($currentWorkspace->permission == 'Owner')
        {
            $tasks    = Task::select('tasks.*')->join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.workspace', '=', $currentWorkspace->id);
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
        }
        else
        {
            $tasks    = Task::select('tasks.*')->join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.workspace', '=', $currentWorkspace->id)->whereRaw("find_in_set('" . Auth::user()->id . "',tasks.assign_to)");
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
        }

        if($project_id)
        {
            $tasks->where('tasks.project_id', '=', $project_id);
        }
        $tasks = $tasks->get();

        $arrayJson = [];
        foreach($tasks as $task)
        {
            $arrayJson[] = [
                "title" => $task->title,
                "start" => $task->start_date,
                "end" => $task->due_date,
                "url" => (($objUser->getGuard() != 'client') ? route(
                    'tasks.show', [
                                    $currentWorkspace->slug,
                                    $task->project_id,
                                    $task->id,
                                ]
                ) : ''),
                "task_id" => $task->id,
                "task_url" => (($objUser->getGuard() != 'client') ? route(
                    'tasks.drag.event', [
                                          $currentWorkspace->slug,
                                          $task->project_id,
                                          $task->id,
                                      ]
                ) : ''),
                "className" => (($task->priority == 'Medium') ? 'bg-warning border-warning' : (($task->priority == 'High') ? 'bg-danger border-danger' : '')),
                "allDay" => true,
            ];
        }

        return view('calendar.index', compact('currentWorkspace', 'arrayJson', 'projects', 'project_id'));
    }
}
