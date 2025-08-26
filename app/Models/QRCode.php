<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\LogsActivity;

class QRCode extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'qrcodes';

    protected $fillable = [
        'qr_code','link_url','is_active','lot_id','created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    public function lot()     { return $this->belongsTo(LotNumber::class, 'lot_id'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
