<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index2()
    {
        //
        $sum = 1;
        return view('admin.activity-logs.index', compact('sum'));
    }

public function index(Request $request)
{
    $q       = trim((string) $request->input('q'));
    $role    = $request->input('role');      // ชื่อ role จากตาราง roles.name
    $perPage = (int) $request->input('per_page', 12);

    // รายการ role สำหรับ dropdown (มาจากตาราง roles)
    $roles = Role::orderBy('name')->pluck('name')->all();

    $logs = ActivityLog::query()
        // ดึงข้อมูล user + roles (ผ่าน pivot role_user)
        ->with(['user:id,fname,lname,name,email', 'user.roles:id,name'])

        // ค้นหาข้อความ
        ->when($q, function ($qb) use ($q) {
            $qb->where('meta->action_text', 'like', "%{$q}%")
               ->orWhere('meta->entity', 'like', "%{$q}%")
               ->orWhere('action', 'like', "%{$q}%")
               ->orWhere('entity_type', 'like', "%{$q}%")
               ->orWhere('meta->route_name', 'like', "%{$q}%")
               ->orWhere('uri', 'like', "%{$q}%")
               ->orWhereHas('user', function ($u) use ($q) {
                   $u->where('fname','like',"%{$q}%")
                     ->orWhere('lname','like',"%{$q}%")
                     ->orWhere('name','like',"%{$q}%")
                     ->orWhere('email','like',"%{$q}%");
               })
               // ให้ค้นหาด้วยชื่อ role ได้ด้วย
               ->orWhereHas('user.roles', function ($r) use ($q) {
                   $r->where('name', 'like', "%{$q}%");
               });
        })

        // กรองตาม role (ผ่านความสัมพันธ์ user.roles)
        ->when($role, function ($qb) use ($role) {
            $qb->whereHas('user.roles', fn($r) => $r->where('name', $role));
        })

        ->latest()
        ->paginate($perPage)
        ->withQueryString();

    // ===== สรุปผลการแก้ไข/เปลี่ยนแปลง เพื่อแสดงผล =====
    $fieldLabel = [
        'qr_code'=>'รหัส QR','link_url'=>'ลิงก์','is_active'=>'สถานะ','lot_id'=>'ล็อตนัมเบอร์',
        'qty'=>'จำนวน','run_range'=>'เลขรัน','mfg_date'=>'วันที่ผลิต','mfg_time'=>'เวลา',
        'supplier'=>'Supplier','name'=>'ชื่อสินค้า','sku'=>'SKU','lot_no'=>'Lot',
    ];
    $actionColor = [
        'created'=>'badge-light-success','updated'=>'badge-light-warning',
        'deleted'=>'badge-light-danger', 'restored'=>'badge-light-info',
    ];

    $logs->getCollection()->transform(function ($log) use ($fieldLabel, $actionColor) {
        $meta = $log->meta ?? [];
        $log->menu         = $meta['module']      ?? class_basename($log->entity_type);
        $log->action_text  = $meta['action_text'] ?? $log->action;
        $log->action_badge = $actionColor[$log->action] ?? 'badge-light';
        $log->route_name   = $meta['route_name']  ?? null;
        $log->entity_name  = $meta['entity']      ?? null;

        $summary = [];
        if (is_array($log->changes)) {
            foreach ($log->changes as $k => $pair) {
                $label = $fieldLabel[$k] ?? $k;
                if ($k === 'lot_id') {
                    $from = $pair['from'] ? optional(LotNumber::find($pair['from']))->lot_no : null;
                    $to   = $pair['to']   ? optional(LotNumber::find($pair['to']))->lot_no   : null;
                } elseif ($k === 'is_active') {
                    $from = isset($pair['from']) ? ($pair['from'] ? 'เปิด' : 'ปิด') : null;
                    $to   = isset($pair['to'])   ? ($pair['to']   ? 'เปิด' : 'ปิด') : null;
                } else {
                    $from = $pair['from'] ?? null;
                    $to   = $pair['to']   ?? null;
                }
                $summary[] = $from === null ? "{$label}: {$to}" : "{$label}: {$from} → {$to}";
            }
        }
        $log->summary = $summary ? implode(' • ', $summary) : null;
        return $log;
    });

    $total = ActivityLog::count();

    return view('admin.activity-logs.index',
        compact('logs','q','role','roles','perPage','total'));
}

    /**
     * Show the form for creating a new resource. return view('admin.activity-logs.index', compact('logs','q','role','perPage','total','roles'));
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
