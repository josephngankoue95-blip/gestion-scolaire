<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function lire($id)
    {
        $notif = Auth::user()->notifications()->findOrFail($id);
        $notif->markAsRead();
        return response()->json(['ok' => true]);
    }

    public function marquerToutLu()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    }
}