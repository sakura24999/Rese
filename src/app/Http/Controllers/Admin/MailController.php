<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function create()
    {
        $userTypes = [
            'all' => '全てのユーザー',
            'customer' => '一般ユーザー',
            'owner' => '店舗オーナー',
        ];

        return view('admin.mail.create', compact('userTypes'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'user_type' => 'required|string|in:all,customer,owner',
            'subject' => 'required|string|max:100',
            'message' => 'required|string',
        ]);

        $query = User::query();

        if ($validated['user_type'] === 'customer') {
            $query->where('role', 'customer');
        } elseif ($validated['user_type'] === 'owner') {
            $query->where('role', 'owner');
        }

        $users = $query->get();

        if ($users->count() === 0) {
            return redirect()->back()->with('error', '送信対象のユーザーが見つかりませんでした。');
        }

        foreach ($users as $user) {
            Mail::to($user->email)->send(new NotificationMail(
                $validated['subject'],
                $validated['message']
            ));
        }

        return redirect()->route('admin.dashboard')->with('success', $users->count() . '人のユーザーにメールを送信しました。');
    }
}
