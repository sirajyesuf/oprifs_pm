<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Models\Client;
use App\Models\LandingPageSection;
use App\Models\Stage;
use App\Models\ClientProject;
use App\Models\ClientWorkspace;
use App\Models\User;
use App\Models\Utility;
use App\Models\UserProject;
use App\Models\Task;
use App\Models\Todo;
use App\Models\Calendar;
use App\Models\Project;
use App\Models\UserWorkspace;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function landingPage()
    {
        if (!file_exists(storage_path() . "/installed")) {
            header('location:install');
            die;
        }

        if (env('DISPLAY_LANDING') == 'on' || env('DISPLAY_LANDING') == '') {
            $get_section = LandingPageSection::orderBy('section_order', 'ASC')->get();

            return view('layouts.landing', compact('get_section'));
        } else {
            return redirect('login');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($slug = '')
    {
        \Artisan::call('route:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');


        // Auth::logout();
        $userObj = Auth::user();
        if ($userObj->type == 'admin') {
            $users = User::where('type', '!=', 'admin')->get();
            $doneStage = Stage::where('complete', '=', '1')->first();

            $totalBugs  = BugReport::count();
            $totalProject = Project::count();
            $totalTask =    Task::count();
            if($doneStage){
                $completeTask = Task::where('status', $doneStage->id)->count();

            }else{
               $completeTask=0; 
            }
            $totalUsers = User::where('type','user')->count();

            $tasks        = Task::select([
                'tasks.*',
                'stages.name as status',
                'stages.complete',
            ])->join("user_projects", "tasks.project_id", "=", "user_projects.project_id")
                ->join("projects", "projects.id", "=", "user_projects.project_id")
                ->join("stages", "stages.id", "=", "tasks.status")
                ->orderBy('tasks.id', 'desc')->limit(4)->get();
            $projectProcess  = DB::table('projects')
                ->groupBy('projects.status')
                ->selectRaw('count(projects.id) as count, projects.status')
                ->pluck('count', 'projects.status');

            $arrProcessPer   = [];
            $arrProcessLabel = [];
            foreach ($projectProcess as $lable => $process) {
                $arrProcessLabel[] = $lable;
                if ($totalProject == 0) {
                    $arrProcessPer[] = 0.00;
                } else {
                    $arrProcessPer[] = round(($process * 100) / $totalProject, 2);
                }
            }
            $arrProcessClass = [
                'text-success',
                'text-primary',
                'text-danger',
            ];

            $chartData = app('App\Http\Controllers\ProjectController')->getProjectChart([
                'duration' => 'week',
            ]);

            return view('users.index', compact(
                'totalProject',
                'totalBugs',
                'totalTask',
                'totalUsers',
                'arrProcessLabel',
                'arrProcessPer',
                'arrProcessClass',
                'completeTask',
                'tasks',
                'chartData'
            ));
        }
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if ($currentWorkspace) {
            $doneStage = Stage::where('workspace_id', '=', $currentWorkspace->id)->where('complete', '=', '1')->first();
            if ($userObj->getGuard() == 'client') {

                $totalProject   = ClientProject::join("projects", "projects.id", "=", "client_projects.project_id")->where("client_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->count();
                $totalBugs      = ClientProject::join("bug_reports", "bug_reports.project_id", "=", "client_projects.project_id")->join("projects", "projects.id", "=", "client_projects.project_id")->where('projects.workspace', '=', $currentWorkspace->id)->count();
                $totalTask      = ClientProject::join("tasks", "tasks.project_id", "=", "client_projects.project_id")->join("projects", "projects.id", "=", "client_projects.project_id")->where('projects.workspace', '=', $currentWorkspace->id)->where("client_id", "=", $userObj->id)->count();
                $completeTask   = ClientProject::join("tasks", "tasks.project_id", "=", "client_projects.project_id")->join("projects", "projects.id", "=", "client_projects.project_id")->where('projects.workspace', '=', $currentWorkspace->id)->where("client_id", "=", $userObj->id)->where('tasks.status', '=', $doneStage->id)->count();
                $tasks          = Task::select([
                    'tasks.*',
                    'stages.name as status',
                    'stages.complete',
                ])->join("client_projects", "tasks.project_id", "=", "client_projects.project_id")->join("projects", "projects.id", "=", "client_projects.project_id")->join("stages", "stages.id", "=", "tasks.status")->where('projects.workspace', '=', $currentWorkspace->id)->where("client_id", "=", $userObj->id)->orderBy('tasks.id', 'desc')->limit(4)->get();
                $totalMembers   = UserWorkspace::where('workspace_id', '=', $currentWorkspace->id)->count();
                $projectProcess = ClientProject::join("projects", "projects.id", "=", "client_projects.project_id")->where('projects.workspace', '=', $currentWorkspace->id)->where("client_id", "=", $userObj->id)->groupBy('projects.status')->selectRaw('count(projects.id) as count, projects.status')->pluck('count', 'projects.status');

                $arrProcessPer   = [];
                $arrProcessLabel = [];
                foreach ($projectProcess as $lable => $process) {
                    $arrProcessLabel[] = $lable;
                    if ($totalProject == 0) {
                        $arrProcessPer[] = 0.00;
                    } else {
                        $arrProcessPer[] = round(($process * 100) / $totalProject, 2);
                    }
                }
                $arrProcessClass = [
                    'text-success',
                    'text-primary',
                    'text-danger',
                ];
                $chartData       = app('App\Http\Controllers\ProjectController')->getProjectChart([
                    'workspace_id' => $currentWorkspace->id,
                    'duration' => 'week',
                ]);

                return view('home', compact('currentWorkspace', 'totalProject', 'totalBugs', 'totalTask', 'totalMembers', 'arrProcessLabel', 'arrProcessPer', 'arrProcessClass', 'completeTask', 'tasks', 'chartData'));
            } else {
                $totalProject = UserProject::join("projects", "projects.id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->count();

                if ($currentWorkspace->permission == 'Owner') {
                    $totalBugs    = UserProject::join("bug_reports", "bug_reports.project_id", "=", "user_projects.project_id")->join("projects", "projects.id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->count();
                    $totalTask    = UserProject::join("tasks", "tasks.project_id", "=", "user_projects.project_id")->join("projects", "projects.id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->count();
                    $completeTask = UserProject::join("tasks", "tasks.project_id", "=", "user_projects.project_id")->join("projects", "projects.id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('tasks.status', '=', $doneStage->id)->count();
                    $tasks        = Task::select([
                        'tasks.*',
                        'stages.name as status',
                        'stages.complete',
                    ])->join("user_projects", "tasks.project_id", "=", "user_projects.project_id")->join("projects", "projects.id", "=", "user_projects.project_id")->join("stages", "stages.id", "=", "tasks.status")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->orderBy('tasks.id', 'desc')->limit(4)->get();
                } else {
                    $totalBugs    = UserProject::join("bug_reports", "bug_reports.project_id", "=", "user_projects.project_id")->join("projects", "projects.id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->where('bug_reports.assign_to', '=', $userObj->id)->count();
                    $totalTask    = UserProject::join("tasks", "tasks.project_id", "=", "user_projects.project_id")->join("projects", "projects.id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->whereRaw("find_in_set('" . $userObj->id . "',tasks.assign_to)")->count();
                    $completeTask = UserProject::join("tasks", "tasks.project_id", "=", "user_projects.project_id")->join("projects", "projects.id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->whereRaw("find_in_set('" . $userObj->id . "',tasks.assign_to)")->where('tasks.status', '=', $doneStage->id)->count();
                    $tasks        = Task::select([
                        'tasks.*',
                        'stages.name as status',
                        'stages.complete',
                    ])->join("user_projects", "tasks.project_id", "=", "user_projects.project_id")
                        ->join("projects", "projects.id", "=", "user_projects.project_id")
                        ->join("stages", "stages.id", "=", "tasks.status")
                        ->where("user_id", "=", $userObj->id)
                        ->where('projects.workspace', '=', $currentWorkspace->id)
                        ->whereRaw("find_in_set('" . $userObj->id . "',tasks.assign_to)")
                        ->orderBy('tasks.id', 'desc')->limit(4)->get();
                }

                $totalMembers = UserWorkspace::where('workspace_id', '=', $currentWorkspace->id)->count();

                $projectProcess  = UserProject::join("projects", "projects.id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace->id)->groupBy('projects.status')->selectRaw('count(projects.id) as count, projects.status')->pluck('count', 'projects.status');
                $arrProcessPer   = [];
                $arrProcessLabel = [];
                foreach ($projectProcess as $lable => $process) {
                    $arrProcessLabel[] = $lable;
                    if ($totalProject == 0) {
                        $arrProcessPer[] = 0.00;
                    } else {
                        $arrProcessPer[] = round(($process * 100) / $totalProject, 2);
                    }
                }
                $arrProcessClass = [
                    'text-success',
                    'text-primary',
                    'text-danger',
                ];

                $chartData = app('App\Http\Controllers\ProjectController')->getProjectChart([
                    'workspace_id' => $currentWorkspace->id,
                    'duration' => 'week',
                ]);

                return view('home', compact('currentWorkspace', 'totalProject', 'totalBugs', 'totalTask', 'totalMembers', 'arrProcessLabel', 'arrProcessPer', 'arrProcessClass', 'completeTask', 'tasks', 'chartData'));
            }
        } else {
            return view('home', compact('currentWorkspace'));
        }
    }
}
