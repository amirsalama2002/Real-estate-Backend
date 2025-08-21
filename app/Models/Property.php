<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    // 📝 الحقول القابلة للتعبئة
    protected $fillable = [
        'title',
        'description',
        'price',
        'type',
        'city',
        'address',
        'status',
        'user_id',
    ];

    // 👤 العقار ينتمي لمستخدم (صاحب الإعلان)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🖼️ العقار له صور متعددة
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    // 📅 العقار ممكن يكون له حجوزات (لو عايز نظام إيجار)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
