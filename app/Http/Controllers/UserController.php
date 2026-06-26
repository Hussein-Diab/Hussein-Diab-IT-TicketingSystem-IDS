<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdminOrManager()) {
            return redirect('/dashbaord');
        }
        $query = User::query();
        $query->when($request->search, function ($q, $search) {
            $q->where('Name', 'like', "%{$search}%")
                ->orWhere('Email', 'like', "%{$search}%");
        });
        if ($request->role) {
            $query->where('RoleId', $request->role);
        }
        if ($request->status === 'active') {
            $query->where('IsActive', true);
        } elseif ($request->status === 'inactive') {
            $query->where('IsActive', false);
        }
        $users = $query->latest()->paginate(10);
        return view('users.index', compact('users'));
    }
    public function show($id)
    {
        if (!auth()->user()->isAdminOrManager()) {
            return redirect('/dashboard');
        }
        $user = User::findOrFail((int)$id);
        $totalCreated = Ticket::where('UserId', $user->Id)->count();
        $totalAssigned = Ticket::where('AssignedTo', $user->Id)->count();
        $openTickets = Ticket::where('UserId', $user->Id)->where('StatusId', 1)->count();
        $resolved = Ticket::where('UserId', $user->Id)->where('StatusId', 4)->count();
        $totalComments = DB::table('TicketComments')->where('UserId', $user->Id)->count();
        $recentTickets = Ticket::with(['category', 'priority', 'status'])->where('UserId', $user->Id)->latest()->take(5)->get();
        $assignedTickets = Ticket::with(['category', 'priority', 'status'])->where('AssignedTo', $user->Id)->latest()->take(5)->get();
        $activityLogs = DB::table('ActivityLogs')->where('UserId', $user->Id)->orderBy('created_at', 'desc')->take(5)->get();
        $roles = [1 => 'Admin', 2 => 'Agent', 3 => 'Employee', 4 => 'Manager'];
        return view('users.show', compact('user', 'totalCreated', 'totalAssigned', 'openTickets', 'resolved', 'totalComments', 'recentTickets', 'assignedTickets', 'activityLogs', 'roles'));
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->isAdminOrManager()) {
            return redirect('/dashboard');
        }
        $request->validate(['RoleId' => 'required|in:1,2,3,4', 'IsActive' => 'required|in:0,1',]);
        $user = User::findOrFail((int)$id);
        if ($user->Id === auth()->user()->Id) {
            return redirect('/users/' . $id);
        }
        $user->update(['RoleId' => $request->RoleId, 'IsActive' => $request->IsActive,]);
        DB::table('ActivityLogs')->insert([
            'UserId'     => auth()->user()->Id,
            'TicketId'   => null,
            'Action'     => auth()->user()->Name . ' updated user ' . $user->Name . ' — Role: ' . $request->RoleId . ', Active: ' . $request->IsActive,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect('/users/'.$id)->with('success','User updated successfully');

    }
    public function toggleActive($id){
        if (!auth()->user()->isAdminOrManager()) {
            return redirect('/dashboard');
        }
        $user=User::findOrFail((int)$id);
        if($user->Id===auth()->user()->Id){
            return redirect('/users')->withErrors(['error'=>'a user cannot deactivate themselves']);
        }
        $user->update(['IsActive'=>!$user->IsActive]);
        $status=$user->IsActive ? 'activated' : 'deactivated';
        return redirect('/users')->with('success','User'.$user->Name.' has been '. $status);
    }
}

