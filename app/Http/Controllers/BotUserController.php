<?php

namespace App\Http\Controllers;

use App\Models\BotUser;
use Illuminate\Http\Request;

class BotUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $users = BotUser::getAllUsers();
        $title = 'Пользователи бота';
        return view('admin.bot.users', compact('users', 'title'));
    }

    public function toggleActive(BotUser $botUser): \Illuminate\Http\RedirectResponse
    {
        $botUser->toggleActive();
        return back();
    }
}
