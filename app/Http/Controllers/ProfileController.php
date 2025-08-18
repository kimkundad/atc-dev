<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $role = optional($user->roles()->first())->name; // superadmin|admin|user
        return view('admin.profile.index', compact('user', 'role'));
    }

    public function updateGeneral(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fname'      => ['required','string','max:100'],
            'lname'      => ['required','string','max:100'],
            'user_type'  => ['nullable','string','max:100'], // ใช้แทน "ตำแหน่ง"
        ]);

        $user->update([
            'fname'     => $request->fname,
            'lname'     => $request->lname,
            'user_type' => $request->user_type,
        ]);

        return back()->with('success_general', 'บันทึกข้อมูลทั่วไปเรียบร้อย');
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        // validate username เสมอ
        $rules = [
            'name' => ['required','string','max:50', Rule::unique('users','name')->ignore($user->id)],
        ];

        // หากมีการกรอกรหัสผ่านใหม่ ให้บังคับกรอกรหัสผ่านปัจจุบันและยืนยัน
        if ($request->filled('password')) {
            $rules['current_password'] = ['required','current_password'];
            $rules['password']         = ['required','min:8','confirmed'];
        } else {
            // อนุญาตไม่กรอกรหัสผ่านใหม่ได้
            $rules['current_password'] = ['nullable'];
            $rules['password']         = ['nullable','confirmed'];
        }

        $validated = $request->validate($rules);

        // อัปเดตชื่อบัญชี
        $user->name = $validated['name'];

        // อัปเดตรหัสผ่านถ้ากรอกมา
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success_account', 'บันทึกการตั้งค่าบัญชีเรียบร้อย');
    }
}
