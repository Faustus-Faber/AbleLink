<?php
 
namespace App\Http\Controllers\Admin;

// F20 - Akida Lisi
 
use App\Http\Controllers\Controller;
use App\Models\Employment\Job;
use App\Models\Auth\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
 
class AdminStatsController extends Controller
{
   
 
    public function users(Request $request): View
    {
        return $this->renderUserList($request, 'Users', null, 'admin.users.create');
    }
 
    public function volunteers(Request $request): View
    {
        return $this->renderUserList($request, 'Volunteers', User::ROLE_VOLUNTEER, 'admin.users.create', User::ROLE_VOLUNTEER);
    }
 
    public function employers(Request $request): View
    {
        return $this->renderUserList($request, 'Employers', User::ROLE_EMPLOYER, 'admin.users.create', User::ROLE_EMPLOYER);
    }
 
    public function caregivers(Request $request): View
    {
        return $this->renderUserList($request, 'Caregivers', User::ROLE_CAREGIVER, 'admin.users.create', User::ROLE_CAREGIVER);
    }
 
    
 
    public function jobs(): View
    {
        return view('admin.stats.count', [
            'title' => 'Jobs',
            'count' => Job::count(),
        ]);
    }
 
    
 
    // /admin/users/create/{role?}
    public function create(?string $role = null): View
    {
        $role = $role ?: User::ROLE_DISABLED;
 
        abort_unless(in_array($role, $this->allowedRoles(), true), 404);
 
        return view('admin.stats.user-form', [
            'role' => $role,
        ]);
    }
 
    // POST /admin/users
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in($this->allowedRoles())],
            'password' => ['required', 'string', 'min:6', 'max:255'],
        ]);
 
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);
 
        return $this->redirectToRolePage($data['role'])
            ->with('success', ucfirst($data['role']) . ' account created.');
    }
 
    
 
    // DELETE /admin/users/{user}
    public function destroy(User $user): RedirectResponse
    {
        // Safety: prevent deleting yourself
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }
 
        // Safety: prevent deleting last admin
        if ($user->role === User::ROLE_ADMIN) {
            $adminCount = User::where('role', User::ROLE_ADMIN)->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'You cannot delete the last admin account.');
            }
        }
 
        $user->delete();
 
        return back()->with('success', 'User removed.');
    }
 
   
    private function renderUserList(
        Request $request,
        string $title,
        ?string $role,
        string $createRouteName,
        ?string $createRoleParam = null
    ): View {
        $query = User::query();
 
        if ($role !== null) {
            $query->where('role', $role);
        }
 
        // Optional search: ?q=
        if ($request->filled('q')) {
            $q = trim((string) $request->query('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }
 
        $users = $query->orderBy('name')->paginate(15)->withQueryString();
 
        return view('admin.stats.users', [
            'title' => $title,
            'users' => $users,
            'count' => $users->total(),
            'search' => $request->query('q', ''),
            'createUrl' => $createRoleParam
                ? route($createRouteName, $createRoleParam)
                : route($createRouteName),
        ]);
    }
 
    private function allowedRoles(): array
    {
        return [
            User::ROLE_DISABLED,
            User::ROLE_VOLUNTEER,
            User::ROLE_EMPLOYER,
            User::ROLE_CAREGIVER,
            User::ROLE_ADMIN,
        ];
    }
 
    private function redirectToRolePage(string $role)
{
    return match ($role) {
        User::ROLE_VOLUNTEER => redirect()->route('admin.volunteers.list'),
        User::ROLE_EMPLOYER  => redirect()->route('admin.employers.list'),
        User::ROLE_CAREGIVER => redirect()->route('admin.caregivers.list'),
        default              => redirect()->route('admin.users.list'),
    };
}
    }

