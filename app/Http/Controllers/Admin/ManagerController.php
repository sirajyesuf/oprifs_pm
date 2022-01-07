<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index()
    {
        $managers = User::where('type', 'manager')->paginate(10);
        return view('admin.managers.index', compact(['managers']));
    }

    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        $user->block_status = true;
        $user->save();
        return back()->with('success', __('Manager Blocked  Successfully'));
    }

    public function unBlockUser($id)
    {
        $user = User::findOrFail($id);
        $user->block_status = false;
        $user->save();
        return back()->with('success', __('Manager Un Blocked  Successfully'));
    }
}
