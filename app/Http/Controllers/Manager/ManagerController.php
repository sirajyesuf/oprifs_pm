<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    public function create()
    {
        if(Auth::user()->type == 'admin')
        {
            return view('manager.create');
        }
        else
        {
            return redirect()->back()->with('error',__('Permission denied.'));
        }
    }

    public function store(Request $request)
    { 
        // dd($request->password);
        if(Auth::user()->type == 'admin')
        {
            $validation = [
                'name' => ['required', 'string', 'max:255'],
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
                                        'type'     => 'manager'
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

            return redirect('/home')->with('success',__('Manager Created Successfully'));
        }
        else
        {
            return redirect()->back()->with('error',__('Permission denied.'));
        }
    }
}
