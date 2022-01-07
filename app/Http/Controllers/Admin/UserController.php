<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('type', 'user')->paginate(10);
        return view('admin.users.index', compact(['users']));
    }

    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        $user->block_status = true;
        $user->save();
        return back()->with('success', __('User Blocked  Successfully'));
    }

    public function unBlockUser($id)
    {
        $user = User::findOrFail($id);
        $user->block_status = false;
        $user->save();
        return back()->with('success', __('User Un Blocked  Successfully'));
    }
}
