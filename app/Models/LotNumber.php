<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotNumber extends Model
{
    use HasFactory;

     protected $fillable = [
        'category_id','product_id','lot_no','mfg_date','mfg_time','qty',
        'product_no_old','product_no_new','received_date','supplier','stock_no',
        'remark','galvanize_cert_path','steel_cert_path','created_by',
    ];

    public function category() { return $this->belongsTo(ProductCategory::class,'category_id'); }
    public function product()  { return $this->belongsTo(Product::class); }
    public function creator()  { return $this->belongsTo(User::class,'created_by'); }
    public function qrcodes() { return $this->hasMany(QRCode::class, 'lot_id'); }
}
