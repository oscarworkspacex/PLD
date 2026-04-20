<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSystemUserRequest;
use App\Http\Requests\UpdateSystemUserPasswordRequest;
use App\Http\Requests\UpdateSystemUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class SystemUserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->ensureCan('User read');

        $search = trim((string) $request->query('q', ''));

        $users = User::query()
            ->with('roles:id,name')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nestedQuery) use ($search) {
                    $nestedQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->limit(300)
            ->get()
            ->map(function (User $user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => (int) ($user->status ?? 1),
                    'role' => $user->roles->pluck('name')->first(),
                ];
            })
            ->values();

        $roles = Role::query()
            ->orderBy('name')
            ->pluck('name')
            ->values();

        return response()->json([
            'data' => $users,
            'roles' => $roles,
        ]);
    }

    public function store(StoreSystemUserRequest $request): JsonResponse
    {
        $this->ensureCan('User create');

        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'status' => $validated['status'] ?? 1,
        ]);

        $role = $validated['role'] ?? 'customer';
        if ($role) {
            $user->syncRoles([$role]);
        }

        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'data' => $user->fresh(),
        ], 201);
    }

    public function update(UpdateSystemUserRequest $request, User $user): JsonResponse
    {
        $this->ensureCan('User update');

        $validated = $request->validated();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'status' => $validated['status'] ?? 1,
        ]);

        if (array_key_exists('role', $validated) && $validated['role']) {
            $user->syncRoles([$validated['role']]);
        }

        return response()->json([
            'message' => 'Usuario actualizado correctamente.',
            'data' => $user->fresh(),
        ]);
    }

    public function updatePassword(UpdateSystemUserPasswordRequest $request, User $user): JsonResponse
    {
        $this->ensureCan('User update');

        $validated = $request->validated();

        $user->update([
            'password' => $validated['password'],
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada correctamente.',
        ]);
    }

    private function ensureCan(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }
}
