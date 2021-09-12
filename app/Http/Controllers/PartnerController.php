<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Партнеры';
        $partners = User::where('is_admin', false)->get();

        return view('admin.partners.index', compact('partners', 'title'));
    }

    public function admins()
    {
        $title = 'Админы';
        $partners = User::where('is_admin', true)->get();

        return view('admin.partners.index', compact('partners', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $partners = User::where('is_admin', false)->orderBy('tel')->get();
        return view('admin.partners.add-admin', compact('partners'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $partner)
    {
        return view('admin.partners.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $partner)
    {
        if (auth()->user()->id != $partner->id) {
            if (!auth()->user()->is_admin) {
                abort(403);
            }
        }

        $partner->fill($request->all())->save();

        return redirect()->back()->withSuccess('Данные успешно обновлены');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->back()->withSuccess('Пользователь удален');
    }

    public function passwordChange(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        \auth()->user()->password = Hash::make($request->password);
        \auth()->user()->save();

        return redirect()->route('profile')->withSuccess('Пароль успешно изменен');
    }

    public function toggleAdmin(Partner $admin)
    {
        $admin->is_admin = !$admin->is_admin;
        $admin->save();

        $result = $admin->is_admin
            ? ' теперь админ'
            : ' больше не админ';

        return redirect()->back()->with('success', $admin->name.$result);
    }
}
