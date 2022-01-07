<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\Stage;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use App\Models\BugStage;
use App\Models\BugReport;


class ProjectController extends Controller
{
   public function index()
   {
      $projects = Project::paginate(10);
      return view('admin.projects.index', compact('projects'));
   }

   public function show($project_id)
   {

      $project = Project::findOrFail($project_id);
      $currentWorkspace = $project->workspaceData;
      $chartData = $this->getProjectChart(
         [
            'project_id' => $project_id,
            'duration' => 'week',
         ]
      );

      $daysleft = round((((strtotime($project->end_date) - strtotime(date('Y-m-d'))) / 24) / 60) / 60);

      // $permissions = Auth::user()->getPermission($project->id);

      return view('admin.projects.show', compact('currentWorkspace', 'project', 'chartData', 'daysleft'));
   }

   public function projectsTimesheet($project_id)
   {
      $project = Project::findOrFail($project_id);
      $currentWorkspace = $project->workspaceData;
      return view('admin.projects.timesheet', compact('currentWorkspace', 'project_id'));
   }

   public function projectGant($projectID, $duration = 'Week')
   {
      $is_client = '';

      $project = Project::findOrFail($projectID);

      $currentWorkspace = $project->workspaceData;
      $tasks      = [];
      $tasksobj = Task::where('project_id', '=', $project->id)->orderBy('start_date')->get();

      foreach ($tasksobj as $task) {
         $tmp                 = [];
         $tmp['id']           = 'task_' . $task->id;
         $tmp['name']         = $task->title;
         $tmp['start']        = $task->start_date;
         $tmp['end']          = $task->due_date;
         $tmp['custom_class'] = strtolower($task->priority);
         $tmp['progress']     = $task->subTaskPercentage();
         $tmp['extra']        = [
            'priority' => __($task->priority),
            'comments' => count($task->comments),
            'duration' => Date::parse($task->start_date)->format('d M Y H:i A') . ' - ' . Date::parse($task->due_date)->format('d M Y H:i A'),
         ];
         $tasks[]             = $tmp;
      }

      return view('admin.projects.gantt', compact('currentWorkspace', 'project', 'tasks', 'duration', 'is_client'));
   }

   public function taskBoard($projectID)
   {
      $project = Project::findOrFail($projectID);
      $currentWorkspace = $project->workspaceData;
      $stages = $statusClass = [];

      $stages = Stage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->get();
      foreach ($stages as &$status) {
         $statusClass[] = 'task-list-' . str_replace(' ', '_', $status->id);
         $task          = Task::where('project_id', '=', $projectID);

         $task->orderBy('order');
         $status['tasks'] = $task->where('status', '=', $status->id)->get();
      }


      return view('admin.projects.admin_taskboard', compact('currentWorkspace', 'project', 'stages', 'statusClass'));
   }

   public function bugReport($project_id)
   {
      $project = Project::findOrFail($project_id);
      $currentWorkspace = $project->workspaceData;
      $stages = $statusClass = [];
      $stages = BugStage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->get();

      foreach ($stages as &$status) {
         $statusClass[] = 'task-list-' . str_replace(' ', '_', $status->id);
         $bug           = BugReport::where('project_id', '=', $project_id);
         $bug->orderBy('order');
         $status['bugs'] = $bug->where('status', '=', $status->id)->get();
      }
      return view('admin.projects.admin_bug_report', compact('currentWorkspace', 'project', 'stages', 'statusClass'));
   }

   public function getProjectChart($arrParam)
   {
      $arrDuration = [];
      if ($arrParam['duration'] && $arrParam['duration'] == 'week') {
         $previous_week = Utility::getFirstSeventhWeekDay(-1);
         foreach ($previous_week['datePeriod'] as $dateObject) {
            $arrDuration[$dateObject->format('Y-m-d')] = $dateObject->format('D');
         }
      }

      $arrTask = [
         'label' => [],
         'color' => [],
      ];
      if (isset($arrParam['workspace_id'])) {
         $stages      = Stage::where('workspace_id', '=', $arrParam['workspace_id'])
            ->orderBy('order');
      } else {
         $stages      = Stage::orderBy('order');
      }

      foreach ($arrDuration as $date => $label) {
         $objProject = Task::select('status', DB::raw('count(*) as total'))
            ->whereDate('updated_at', '=', $date)
            ->groupBy('status');

         if (isset($arrParam['project_id'])) {
            $objProject->where('project_id', '=', $arrParam['project_id']);
         }
         if (isset($arrParam['c'])) {
            $objProject->whereIn('project_id', function ($query) use ($arrParam) {
               $query->select('id')->from('projects')->where('workspace', '=', $arrParam['workspace_id']);
            });
         }
         $data = $objProject->pluck('total', 'status')->all();

         foreach ($stages->pluck('name', 'id')->toArray() as $id => $stage) {
            $arrTask[$id][] = isset($data[$id]) ? $data[$id] : 0;
         }
         $arrTask['label'][] = __($label);
      }
      $arrTask['stages'] = $stages->pluck('name', 'id')->toArray();
      $arrTask['color'] = $stages->pluck('color')->toArray();

      return $arrTask;
   }

   public function allTasks($project_id)
   {
      $projects = Project::paginate(10);
      $stages = Stage::orderBy('order')->get();
      $users  = User::select('users.*')
      ->join('user_workspaces', 'user_workspaces.user_id', '=', 'users.id')
      ->where('user_workspaces.workspace_id', '=', $currentWorkspace->id)
      ->get();

      return view('projects.tasks', compact('currentWorkspace', 'projects', 'users', 'stages'));
   }
}
