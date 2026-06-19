<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::where('id_user', Auth::id())->latest()->paginate(20);
        return view('notifikasi.index', compact('notifikasis'));
    }

    public function read(Notifikasi $notifikasi)
    {
        $notifikasi->update(['is_read' => true]);
        return back();
    }

    public function readAll()
    {
        Notifikasi::where('id_user', Auth::id())->update(['is_read' => true]);
        return back();
    }
}
