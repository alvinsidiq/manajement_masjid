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
        $sort = $request->input('sort','created_at');
        $dir  = $request->input('dir','desc');

        $users = User::query()
            ->when($q, fn($qq) => $qq->where(function($w) use ($q){
                $w->where('username','like',"%$q%")
                  ->orWhere('email','like',"%$q%");
            }))
            ->when($role, fn($qq) => $qq->where('role',$role))
            ->when(!is_null($status), fn($qq) => $qq->where('is_active',$status))
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users','q','role','status','sort','dir'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
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
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        $user->syncSpatieRoleFromEnum();

        return redirect()->route('admin.users.index')->with('status','Pengguna diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('status','Pengguna dihapus.');
    }
}

