<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->hasRole('admin')) {
                abort(403, 'Accès réservé aux administrateurs.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::with(['roles', 'chantier'])
            ->withoutTrashed()
            ->where('id', '!=', auth()->id())
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'admin')->get();
        $chantiers = Chantier::orderBy('name')->get();

        return view('users.create', compact('roles', 'chantiers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
            'chantier_id' => [
                Rule::requiredIf($request->role === 'site_manager'),
                'nullable',
                'exists:chantiers,id',
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'chantier_id' => $request->role === 'site_manager' ? $request->chantier_id : null,
        ]);
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        $user->load('roles', 'permissions', 'chantier');

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', 'admin')->get();
        $chantiers = Chantier::orderBy('name')->get();

        return view('users.edit', compact('user', 'roles', 'chantiers'));
    }

    public function update(Request $request, User $user)
    {
        $currentRole = $user->roles->first()?->name;
        $role = $request->input('role', $currentRole);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'role' => 'nullable|string|exists:roles,name',
            'chantier_id' => [
                Rule::requiredIf($role === 'site_manager'),
                'nullable',
                'exists:chantiers,id',
            ],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'chantier_id' => $role === 'site_manager' ? $request->chantier_id : null,
        ]);

        if ($request->filled('role') && !$user->hasRole('admin')) {
            $user->syncRoles([$request->role]);
        }

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function restore($id)
    {
        User::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('users.index')->with('success', 'Utilisateur restauré avec succès.');
    }

    public function forceDelete($id)
    {
        User::withTrashed()->findOrFail($id)->forceDelete();

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé définitivement.');
    }

    public function trash()
    {
        $users = User::onlyTrashed()->paginate(10);

        return view('users.trash', compact('users'));
    }
}
