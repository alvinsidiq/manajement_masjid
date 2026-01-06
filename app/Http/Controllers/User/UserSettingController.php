<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserSettingRequest;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Notifications\SettingUpdated;

class UserSettingController extends Controller
{
    public function index()
    {
        $setting = auth()->user()->setting()->firstOrCreate(['user_id' => auth()->id()], [
            'dark_mode' => false,
            'preferred_landing' => 'dashboard',
        ]);
        Gate::authorize('view', $setting);
        return view('user.settings.index', compact('setting'));
    }

    public function create() { abort(404); }

    public function store(UpdateUserSettingRequest $request)
    {
        abort(404);
    }

    public function show(UserSetting $setting)
    {
        Gate::authorize('view', $setting);
        return view('user.settings.index', compact('setting'));
    }

    public function edit(UserSetting $setting)
    {
        Gate::authorize('view', $setting);
        return view('user.settings.edit', compact('setting'));
    }

    public function update(UpdateUserSettingRequest $request, UserSetting $setting)
    {
        Gate::authorize('update', $setting);
        $setting->update($request->validated());

        auth()->user()->notify(new SettingUpdated($setting));

        return redirect()->route('user.settings.index')->with('status','Pengaturan diperbarui.');
    }

    public function destroy(UserSetting $setting)
    {
        Gate::authorize('update', $setting);
        $setting->delete();
        return redirect()->route('user.settings.index')->with('status','Pengaturan direset.');
    }
}

