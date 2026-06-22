<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function index()
    {
        $notifications = Notification::where('UserId', auth()->user()->Id)
            ->latest()
            ->paginate(15);
        Notification::where('UserId', auth()->user()->Id)
            ->where('IsRead', false)
            ->update(['IsRead' => true]);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail((int)$id);

        if ($notification->UserId == auth()->user()->Id) {
            $notification->update(['IsRead' => true]);
        }

        return redirect()->back();
    }

    public function markAllAsRead()
    {
        Notification::where('UserId', auth()->user()->Id)
            ->where('IsRead', false)
            ->update(['IsRead' => true]);

        return redirect()->back()
            ->with('success', 'All notifications marked as read!');
    }

    public static function unreadCount()
    {
        return Notification::where('UserId', auth()->user()->Id)
            ->where('IsRead', false)
            ->count();
    }
}