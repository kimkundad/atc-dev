<?php

// app/Models/Concerns/LogsActivity.php
namespace App\Models\Concerns;

use App\Models\ActivityLog;
use App\Models\QRCode;
use App\Models\LotNumber;
use App\Models\User;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(fn ($m) => self::writeLog($m, 'created'));
        static::updated(fn ($m) => self::writeLog($m, 'updated'));
        static::deleted(fn ($m) => self::writeLog($m, 'deleted'));
        if (method_exists(static::class, 'bootSoftDeletes')) {
            static::restored(fn ($m) => self::writeLog($m, 'restored'));
        }
    }

    protected static function writeLog($model, string $action, array $extraMeta = []): void
    {
        $req   = request();
        $user  = auth()->user();
        $etype = get_class($model);

        // ค่าที่จะแสดงเป็น "โมดูล"
        $module = match ($etype) {
            QRCode::class    => 'QR Code',
            LotNumber::class => 'ล็อตนัมเบอร์',
            User::class      => 'ผู้ใช้งาน',
            default          => class_basename($etype),
        };

        // ข้อความ action ที่อ่านง่าย
        $actionText = [
            'created'  => "สร้าง {$module}",
            'updated'  => "แก้ไข {$module}",
            'deleted'  => "ลบ {$module}",
            'restored' => "กู้คืน {$module}",
        ][$action] ?? $action;

        // label ของเอนทิตี (ไว้โชว์ประกอบ)
        $entityLabel = self::entityLabel($model);

        // diff รายฟิลด์ (เก็บเฉพาะที่จำเป็น)
        $keep = [
            'qr_code','link_url','is_active','lot_id',
            'lot_no','qty','run_range','mfg_date','mfg_time','supplier',
            'name','sku'
        ];
        $changes = null;
        if ($action === 'updated') {
            $changes = [];
            foreach ($model->getDirty() as $k => $after) {
                if ($k === 'updated_at' || ! in_array($k, $keep, true)) continue;
                $before = $model->getOriginal($k);
                $changes[$k] = ['from' => $before, 'to' => $after];
            }
            if (!count($changes)) $changes = null;
        }

        ActivityLog::create([
            'user_id'     => $user?->id,
            'entity_type' => $etype,
            'entity_id'   => $model->getKey(),
            'action'      => $action,
            'changes'     => $changes,              // JSON
            'meta'        => array_merge($extraMeta, [
                'module'      => $module,
                'action_text' => $actionText,
                'entity'      => $entityLabel,
                'route_name'  => $req->route()?->getName(),
                'uri'         => $req->path(),
                'method'      => $req->method(),
            ]),
            'ip'         => $req->ip(),
            'user_agent' => substr((string) $req->userAgent(), 0, 255),
        ]);
    }

    protected static function entityLabel($model): string
    {
        if ($model instanceof QRCode)    return (string) $model->qr_code;
        if ($model instanceof LotNumber) return (string) $model->lot_no;
        if ($model instanceof User)      return (string) ($model->username ?? $model->name);
        return (string) $model->getKey();
    }
}
