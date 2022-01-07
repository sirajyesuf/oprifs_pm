<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BugComment;
use App\Models\BugFile;
use App\Models\BugReport;
use App\Models\Calendar;
use App\Models\Comment;
use App\Models\Events;
use App\Models\Mail\SendLoginDetail;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Project;
use App\Models\SubTask;
use App\Models\TaskFile;
use App\Models\Tax;
use App\Models\Timesheet;
use App\Models\User;
use App\Models\UserProject;
use App\Models\UserWorkspace;
use App\Models\Utility;
use App\Models\Mail\SendWorkspaceInvication;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Pusher\Pusher;
use App\Models\ClientWorkspace;
use App\Models\Note;
use App\Models\ClientProject;
use App\Models\Milestone;
use App\Models\Task;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($slug = '')
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if($currentWorkspace)
        {
            $users = User::select('users.*', 'user_workspaces.permission', 'user_workspaces.is_active')->join('user_workspaces', 'user_workspaces.user_id', '=', 'users.id');
            $users->where('user_workspaces.workspace_id', '=', $currentWorkspace->id);
            $users = $users->get();
        }
        else
        {
            $users = User::where('type', '!=', 'admin')->get();
        }

        return view('users.index', compact('currentWorkspace', 'users'));
    }

    public function create(Request $request)
    {
        if(Auth::user()->type == 'admin')
        {
            return view('users.create');
        }
        else
        {
            return redirect()->back()->with('error',__('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if(Auth::user()->type == 'admin')
        {
            $validation = [
                'name' => ['required', 'string', 'max:255'],
                // 'workspace' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:4'],
            ];

            $validator = \Validator::make($request->all(), $validation);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $objUser = User::create([
                                        'name' => $request->name,
                                        'email' => $request->email,
                                        'password' => Hash::make($request->password),
                                    ]);

            // $objWorkspace = Workspace::create([
            //                                       'created_by'=>$objUser->id,
            //                                       'name'=>$request->workspace,
            //                                       'currency_code' => 'USD',
            //                                       'paypal_mode' => 'sandbox'
            //                                   ]);
            // $objUser->currant_workspace = $objWorkspace->id;
            // $objUser->save();

            // UserWorkspace::create([
            //                           'user_id' => $objUser->id,
            //                           'workspace_id' => $objWorkspace->id,
            //                           'permission' => 'Owner'
            //                       ]);

            return redirect('/home')->with('success',__('User Created Successfully'));
        }
        else
        {
            return redirect()->back()->with('error',__('Permission denied.'));
        }
    }

    public function account()
    {
        $user             = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug('');

        return view('users.account', compact('currentWorkspace', 'user'));
    }

    public function edit($slug, $id)
    {
        $user             = User::find($id);
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        return view('users.edit', compact('currentWorkspace', 'user'));
    }

    public function deleteAvatar()
    {
        $objUser         = Auth::user();
        if(asset(\Storage::exists('avatars/'.$objUser->avatar)))
        {
            asset(\Storage::delete('avatars/'.$objUser->avatar));
        }
        $objUser->avatar = '';
        $objUser->save();

        return redirect()->back()->with('success', 'Avatar deleted successfully');
    }

    public function update($slug = null, $id = null, Request $request)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if($id)
        {
            $objUser = User::find($id);
        }
        else
        {
            $objUser = Auth::user();
        }
        $validation         = [];
        $validation['name'] = 'required';
        $validation['email'] = 'required|email|max:100|unique:users,email,' . $objUser->id . ',id';

        if($request->has('avatar'))
        {
            $validation['avatar'] = 'required|mimes:jpeg,jpg,png,gif,svg|max:204800';
        }

        $validator = \Validator::make($request->all(), $validation);
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $objUser->name = $request->name;
        $objUser->email = $request->email;

        if($request->has('avatar'))
        {
            if(asset(\Storage::exists('avatars/'.$objUser->avatar)))
            {
                asset(\Storage::delete('avatars/'.$objUser->avatar));
            }

            $logoName = uniqid() . '.png';
            $request->avatar->storeAs('avatars', $logoName);
            $objUser->avatar = $logoName;
        }

        $objUser->save();
        return redirect()->back()->with('success', __('User Updated Successfully!'));
    }

    public function destroy($user_id)
    {
        if($user_id != 1)
        {
            $user = User::find($user_id);
            $user->delete();
            return redirect()->back()->with('success', __('User Deleted Successfully!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Some Thing Is Wrong!'));
        }
    }

    public function updatePassword(Request $request)
    {
        if(Auth::Check())
        {
            $request->validate(
                [
                    'old_password' => 'required',
                    'password' => 'required|same:password',
                    'password_confirmation' => 'required|same:password',
                ]
            );
            $objUser          = Auth::user();
            $request_data     = $request->All();
            $current_password = $objUser->password;

            if(Hash::check($request_data['old_password'], $current_password))
            {
                $objUser->password = Hash::make($request_data['password']);;
                $objUser->save();

                return redirect()->back()->with('success', __('Password Updated Successfully!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Please Enter Correct Current Password!'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Some Thing Is Wrong!'));
        }
    }


    public function getUserJson($workspace_id)
    {
        $return  = [];
        $objdata = UserWorkspace::select('user.email')->join('users', 'users.id', '=', 'user_workspaces.user_id')->where('user_workspaces.is_active', '=', 1)->where('users.id', '!=', auth()->id())->get();
        foreach($objdata as $data)
        {
            $return[] = $data->email;
        }

        return response()->json($return);
    }

    public function getProjectUserJson($projectID)
    {
        $project = Project::find($projectID);

        return $project->users->toJSON();
    }

    public function getProjectMilestoneJson($projectID)
    {
        $project = Project::find($projectID);

        return $project->milestones->toJSON();
    }

    public function invite($slug)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        return view('users.invite', compact('currentWorkspace'));
    }

    public function inviteUser($slug, Request $request)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        $post             = $request->all();
        $name            = $post['username'];
        $email            = $post['useremail'];
        $password            = $post['userpassword'];

        $registerUsers = User::where('email', $email)->first();
        if(!$registerUsers)
        {
            $objUser = \Auth::user();
            $arrUser                      = [];
            $arrUser['name']              = $name;
            $arrUser['email']             = $email;
            $arrUser['password']          = Hash::make($password);
            $arrUser['currant_workspace'] = $currentWorkspace->id;
            $registerUsers                = User::create($arrUser);
            $assignPlan                   = $registerUsers->assignPlan(1);
            $registerUsers->password      = $password;
            if(!$assignPlan['is_success'])
            {
                return json_encode([
                                       'code' => 400,
                                       'status' => 'Error',
                                       'error' => __($assignPlan['error']),
                                   ]);
            }

            try
            {
                Mail::to($email)->send(new SendLoginDetail($registerUsers));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }
        }

        // assign workspace first
        $is_assigned = false;
        foreach($registerUsers->workspace as $workspace)
        {
            if($workspace->id == $currentWorkspace->id)
            {
                $is_assigned = true;
            }
        }

        if(!$is_assigned)
        {
            UserWorkspace::create(
                [
                    'user_id' => $registerUsers->id,
                    'workspace_id' => $currentWorkspace->id,
                    'permission' => 'Member',
                ]
            );

            try
            {
                Mail::to($registerUsers->email)->send(new SendWorkspaceInvication($registerUsers, $currentWorkspace));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }

        }

        return json_encode([
                            'code' => 200,
                            'status' => 'Success',
                            'url' => route('users.index', $currentWorkspace->slug),
                            'success' => __('Users Invited Successfully!') . ((isset($smtp_error)) ? ' <br> <span class="text-danger">' . $smtp_error . '</span>' : ''),
                        ]);
    }

    public function removeUser($slug, $id)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $userWorkspace    = UserWorkspace::where('user_id', '=', $id)->where('workspace_id', '=', $currentWorkspace->id)->first();
        if($userWorkspace)
        {
            $user             = User::find($id);
            $userProjectCount = $user->countProject($currentWorkspace->id);
            if($userProjectCount == 0)
            {
                $userWorkspace->delete();
            }
            else
            {
                return redirect()->route('users.index', $currentWorkspace->slug)->with('warning', __('Please Remove User From Project!'));
            }

            return redirect()->route('users.index', $currentWorkspace->slug)->with('success', __('User Removed Successfully!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }

    public function chatIndex($slug = '')
    {
        if(env('CHAT_MODULE') == 'yes')
        {
            $objUser          = Auth::user();
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);
            if($currentWorkspace)
            {
                $users = User::select('users.*', 'user_workspaces.permission', 'user_workspaces.is_active')->join('user_workspaces', 'user_workspaces.user_id', '=', 'users.id');
                $users->where('user_workspaces.workspace_id', '=', $currentWorkspace->id)->where('users.id', '!=', $objUser->id);
                $users = $users->get();
            }
            else
            {
                $users = User::where('type', '!=', 'admin')->get();
            }

            return view('chats.index', compact('currentWorkspace', 'users'));
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }

    public function getMessage($currentWorkspace, $user_id)
    {
        $workspace = Workspace::find($currentWorkspace);
        Utility::getWorkspaceBySlug($workspace->slug);
        $my_id = Auth::id();

        // Make read all unread message
        Message::where(
            [
                'workspace_id' => $currentWorkspace,
                'from' => $user_id,
                'to' => $my_id,
                'is_read' => 0,
            ]
        )->update(['is_read' => 1]);

        // Get all message from selected user
        $messages = Message::where(
            function ($query) use ($currentWorkspace, $user_id, $my_id){
                $query->where('workspace_id', $currentWorkspace)->where('from', $user_id)->where('to', $my_id);
            }
        )->oRwhere(
            function ($query) use ($currentWorkspace, $user_id, $my_id){
                $query->where('workspace_id', $currentWorkspace)->where('from', $my_id)->where('to', $user_id);
            }
        )->get();

        return view('chats.message', ['messages' => $messages]);
    }

    public function sendMessage(Request $request)
    {
        $from             = Auth::id();
        $currentWorkspace = Workspace::find($request->workspace_id);
        $to               = $request->receiver_id;
        $message          = $request->message;

        $data               = new Message();
        $data->workspace_id = $currentWorkspace->id;
        $data->from         = $from;
        $data->to           = $to;
        $data->message      = $message;
        $data->is_read      = 0; // message will be unread when sending message
        $data->save();

        // pusher
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), $options
        );

        $data = [
            'from' => $from,
            'to' => $to,
        ]; // sending from and to user id when pressed enter
        $pusher->trigger($currentWorkspace->slug, 'chat', $data);

        return response()->json($data, 200);
    }

    public function notificationSeen($slug)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $user             = Auth::user();
        Notification::where('workspace_id', '=', $currentWorkspace->id)->where('user_id', '=', $user->id)->update(['is_read' => 1]);

        return response()->json(['is_success' => true], 200);
    }

    public function getMessagePopup($slug)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $user             = Auth::user();
        $messages         = Message::whereIn(
            'id', function ($query) use ($currentWorkspace, $user){
            $query->select(\DB::raw('MAX(id)'))->from('messages')->where('workspace_id', '=', $currentWorkspace->id)->where('to', $user->id)->where('is_read', '=', 0);
        }
        )->orderBy('id', 'desc')->get();

        return view('chats.popup', compact('messages', 'currentWorkspace'));
    }

    public function messageSeen($slug)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $user             = Auth::user();
        Message::where('workspace_id', '=', $currentWorkspace->id)->where('to', '=', $user->id)->update(['is_read' => 1]);

        return response()->json(['is_success' => true], 200);
    }

    public function deleteMyAccount()
    {
        $user       = Auth::user();

        ActivityLog::where('user_id', $user->id)->delete();
        BugComment::where('created_by', $user->id)->delete();
        BugFile::where('created_by', $user->id)->delete();
        BugReport::where('assign_to', $user->id)->delete();
        Calendar::where('created_by', $user->id)->delete();
        Comment::where('created_by', $user->id)->delete();
        Events::where('created_by', $user->id)->delete();
        SubTask::where('created_by', $user->id)->delete();
        TaskFile::where('created_by', $user->id)->delete();

        $workspaces = Workspace::where('created_by', '=', $user->id)->get();
        foreach($workspaces as $workspace)
        {
            Tax::where('workspace_id', '=', $workspace->id)->delete();
            UserWorkspace::where('workspace_id', '=', $workspace->id)->delete();
            ClientWorkspace::where('workspace_id', '=', $workspace->id)->delete();
            Note::where('workspace', '=', $workspace->id)->delete();
            if($projects = $workspace->projects)
            {
                foreach($projects as $project)
                {
                    UserProject::where('project_id', '=', $project->id)->delete();
                    ClientProject::where('project_id', '=', $project->id)->delete();
                    Milestone::where('project_id', '=', $project->id)->delete();
                    Timesheet::where('project_id', '=', $project->id)->delete();
                    Task::where('project_id', '=', $project->id)->delete();
                    $project->delete();
                }
            }
            $workspace->delete();
        }
        $user->delete();

        return redirect()->route('login');
    }

    public function checkUserExists(Request $request, $slug)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        $authuser = Auth::user();

        if($request->has('email')) {

            $email = $request->email;
            $registerUsers = User::where('email', '=', $email)->first(); //->where('is_active', '=', 1)
        } else if($request->has('user_id')) {

            $user_id = $request->user_id;
            $registerUsers = User::find($user_id);
        }

        $response = [
            'code' => 404,
            'status' => 'Error',
            'error' => __('This User is not connected with our system. Please fill out the below details to invite.'),
        ];

        if(!empty($registerUsers))
        {
            $is_assigned = false;
            foreach($registerUsers->workspace as $workspace)
            {
                if($workspace->id == $currentWorkspace->id)
                {
                    $is_assigned = true;
                }
            }

            if(!$is_assigned)
            {
                UserWorkspace::create(
                    [
                        'user_id' => $registerUsers->id,
                        'workspace_id' => $currentWorkspace->id,
                        'permission' => 'Member',
                    ]
                );

                try
                {
                    Mail::to($registerUsers->email)->send(new SendWorkspaceInvication($registerUsers, $currentWorkspace));
                }
                catch(\Exception $e)
                {
                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                }

            }

            $response =   [
                    'code' => 200,
                    'status' => 'Success',
                    'url' => route('users.index', $currentWorkspace->slug),
                    'success' => __('Users Invited Successfully!') . ((isset($smtp_error)) ? ' <br> <span class="text-danger">' . $smtp_error . '</span>' : ''),
                ];
        }

        return json_encode($response);
    }

}
