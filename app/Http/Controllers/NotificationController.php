<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->take(15)->get()->map(function ($n) {
            return [
                'id'      => $n->id,
                'type'    => $n->data['type'] ?? '',
                'title'   => $n->data['title'] ?? 'Notifikasi',
                'body'    => $n->data['body'] ?? '',
                'url'     => $n->data['url'] ?? '#',
                'read'    => !is_null($n->read_at),
                'time'    => $n->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $user->unreadNotifications()->count(),
        ]);
    }

    public function markRead($id)
    {
        Auth::user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
