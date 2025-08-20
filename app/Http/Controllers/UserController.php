<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $q       = trim($request->input('q', ''));
    $roleId  = $request->input('role_id');
    $perPage = (int) $request->input('per_page', 12);

    $query = User::query()->with('roles');

    if ($q !== '') {
        $query->where(function ($w) use ($q) {
            $w->where('fname', 'like', "%{$q}%")
              ->orWhere('lname', 'like', "%{$q}%")
              ->orWhere('email', 'like', "%{$q}%")
              ->orWhere('name', 'like', "%{$q}%"); // username
        });
    }

    if ($roleId) {
        $query->whereHas('roles', fn($r) => $r->where('roles.id', $roleId));
    }

    $users = $query->paginate($perPage)->withQueryString();
    $roles = Role::orderBy('name')->get();
    $total = User::count();

    return view('admin.users.index', compact('users','roles','q','roleId','perPage','total'));
}

    /**c
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ดึงบทบาททั้งหมดมาแสดงใน select
        $roles = Role::orderBy('name')->get();   // id, name
        return view('admin.users.create', compact('roles'));
    }

    public function activity()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // map ชื่อฟิลด์จากฟอร์ม -> คอลัมน์จริง (users.name = username)
        $validated = $request->validate([
            'first_name' => ['required','string','max:100'],
            'last_name'  => ['required','string','max:100'],
            'phone'      => ['nullable','string','max:50'],
            'email'      => ['required','email', Rule::unique('users','email')],
            'username'   => ['required','string','max:50', Rule::unique('users','name')],
            'password'   => ['required','string','min:8','confirmed'],
            'role_id'    => ['required','exists:roles,id'],
        ],[],[
            'role_id' => 'ประเภทผู้ใช้งาน',
            'username' => 'ชื่อบัญชี',
        ]);

        $user = User::create([
            'fname'    => $validated['first_name'],   // ถ้าในตารางใช้ fname/lname
            'lname'    => $validated['last_name'],
            'phone'    => $validated['phone'] ?? null,
            'email'    => $validated['email'],
            'name'     => $validated['username'],     // ชื่อบัญชี -> คอลัมน์ name
            'password' => Hash::make($validated['password']),
        ]);

        // ผูกบทบาท (pivot role_user: user_id, role_id)
        $user->roles()->sync([$validated['role_id']]);

        return redirect()->route('users.index')->with('success','สร้างผู้ใช้งานเรียบร้อย');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // role ปัจจุบัน (เอา role แรกของ user)
        $currentRoleId = optional($user->roles()->first())->id;

        // รายการ role ทั้งหมด
        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles', 'currentRoleId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'first_name' => ['required','string','max:100'],
            'last_name'  => ['required','string','max:100'],
            'phone'      => ['nullable','string','max:50'],
            'email'      => ['required','email','max:150', Rule::unique('users','email')->ignore($user->id)],
            // ชื่อบัญชี map -> คอลัมน์ users.name
            'username'   => ['required','string','max:50', Rule::unique('users','name')->ignore($user->id)],
            'role_id'    => ['required','exists:roles,id'],
            // เปลี่ยนรหัสผ่านเฉพาะกรอกมา
            'password'   => ['nullable','confirmed','min:8'],
        ];

        $validated = $request->validate($rules);

        // map ชื่อฟิลด์จากฟอร์ม -> คอลัมน์ในตาราง users
        $user->fname  = $validated['first_name'];  // หากคอลัมน์ของคุณคือ first_name เปลี่ยนตามจริงได้
        $user->lname  = $validated['last_name'];   // เช่นเดียวกัน
        $user->phone  = $validated['phone'] ?? null;
        $user->email  = $validated['email'];
        $user->name   = $validated['username'];    // ชื่อบัญชี

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // sync role (pivot: role_user)
        $user->roles()->sync([$validated['role_id']]);

        return redirect()->route('users.index')->with('success', 'อัปเดตผู้ใช้งานเรียบร้อย');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $auth = Auth::user();

        // กันลบตัวเอง
        if ($auth->id === $user->id) {
            return back()->with('error', 'ไม่สามารถลบบัญชีของตัวเองได้');
        }

        // ตรวจ role (ใช้เมธอด hasRole() ที่มีใน Model ของคุณแล้ว)
        $isTargetSuper = $user->hasRole('superadmin');
        $isActorSuper  = $auth->hasRole('superadmin');

        // admin ธรรมดาห้ามลบ superadmin
        if ($isTargetSuper && !$isActorSuper) {
            return back()->with('error', 'ต้องเป็น superadmin เท่านั้นจึงจะลบผู้ใช้งานนี้ได้');
        }

        // กันลบ superadmin คนสุดท้าย
        if ($isTargetSuper) {
            $superCount = User::whereHas('roles', fn($q)=>$q->where('name','superadmin'))->count();
            if ($superCount <= 1) {
                return back()->with('error', 'ไม่สามารถลบ superadmin คนสุดท้ายได้');
            }
        }

        // ลบแบบ soft delete
        $user->delete();

        return back()->with('success', 'ลบผู้ใช้งานเรียบร้อย');
    }

    public function restore($id)
    {
        $auth = Auth::user();
        if (!$auth->hasRole('superadmin')) {
            return back()->with('error', 'ต้องเป็น superadmin เท่านั้นจึงจะกู้คืนได้');
        }

        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return back()->with('success', 'กู้คืนผู้ใช้งานเรียบร้อย');
    }

}
