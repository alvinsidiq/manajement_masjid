<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified','active','role:admin']);
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request)
    {
        $q   = $request->input('q');
        $role = $request->input('role');
        $status = $request->has('active') ? (bool) $request->input('active') : null;
        $verified = $request->input('verified');
        $sort = $request->input('sort','created_at');
        $dir  = $request->input('dir','desc');

        $users = User::query()
            ->when($q, fn($qq) => $qq->where(function($w) use ($q){
                $w->where('username','like',"%$q%")
                  ->orWhere('email','like',"%$q%");
            }))
            ->when($role, fn($qq) => $qq->where('role',$role))
            ->when(!is_null($status), fn($qq) => $qq->where('is_active',$status))
            ->when($verified === '1', fn($qq) => $qq->whereNotNull('email_verified_at'))
            ->when($verified === '0', fn($qq) => $qq->whereNull('email_verified_at'))
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users','q','role','status','verified','sort','dir'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $isVerified = array_key_exists('is_verified', $data) ? (bool) $data['is_verified'] : false;
        unset($data['is_verified']);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        if ($isVerified) {
            $user->email_verified_at = now();
            $user->save();
        }
        $user->syncSpatieRoleFromEnum();

        return redirect()->route('admin.users.index')->with('status','Pengguna dibuat.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $hasVerifiedInput = array_key_exists('is_verified', $data);
        $isVerified = $hasVerifiedInput ? (bool) $data['is_verified'] : null;
        unset($data['is_verified']);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        if ($hasVerifiedInput) {
            if ($isVerified) {
                if (!$user->hasVerifiedEmail()) {
                    $user->email_verified_at = now();
                }
            } else {
                $user->email_verified_at = null;
            }
            $user->save();
        }
        $user->syncSpatieRoleFromEnum();

        return redirect()->route('admin.users.index')->with('status','Pengguna diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('status','Pengguna dihapus.');
    }
}
