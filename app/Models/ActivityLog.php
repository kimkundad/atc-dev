<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id','entity_type','entity_id','action','changes','meta',
        'ip','user_agent','method','uri'
    ];
    protected $casts = ['changes'=>'array','meta'=>'array'];

    public function user(){ return $this->belongsTo(User::class); }
    public function entity(){ return $this->morphTo(); }

    public static function record(string $action, $model, array $payload = []): self
    {
        $req = request();
        return static::create([
            'user_id'     => auth()->id(),
            'entity_type' => get_class($model),
            'entity_id'   => $model->getKey(),
            'action'      => $action,
            'changes'     => $payload['changes'] ?? null,
            'meta'        => $payload['meta'] ?? null,
            'ip'          => $req?->ip(),
            'user_agent'  => substr($req?->userAgent() ?? '', 0, 1000),
            'method'      => $req?->method(),
            'uri'         => $req?->path(),
        ]);
    }
}
