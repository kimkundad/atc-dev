<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $role = optional($user->roles()->first())->name;

        // ค่า default ของแท็บเมื่อโหลดหน้า
        $activeTab = session('tab', 'general');

        return view('admin.profile.index', compact('user','role','activeTab'));
    }

    public function updateGeneral(Request $request)
    {
        $user = Auth::user();

        // ผูก error ไว้กับ bag 'general'
        $validated = $request->validateWithBag('general', [
            'fname'     => ['required','string','max:100'],
            'lname'     => ['required','string','max:100'],
            'user_type' => ['nullable','string','max:100'],
        ]);

        $user->update([
            'fname'     => $validated['fname'],
            'lname'     => $validated['lname'],
            'user_type' => $validated['user_type'] ?? null,
        ]);

        return back()
            ->with('success_general', 'บันทึกข้อมูลทั่วไปเรียบร้อย')
            ->with('tab','general'); // คงแท็บเดิมไว้
    }

    public function updateAccount(Request $request)
    {
        $user  = Auth::user();

        $rules = [
            'name' => ['required','string','max:50', Rule::unique('users','name')->ignore($user->id)],
        ];

        if ($request->filled('password')) {
            $rules['current_password'] = ['required','current_password'];
            $rules['password']         = ['required','min:8','confirmed'];
        }

        // ผูก error ไว้กับ bag 'account'
        $validated = $request->validateWithBag('account', $rules);

        $user->name = $validated['name'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return back()
            ->with('success_account', 'บันทึกการตั้งค่าบัญชีเรียบร้อย')
            ->with('tab','account'); // คงแท็บเดิมไว้
    }
}
