<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // enforce only 1 admin in system
    public static function booted(): void
{
    static::creating(function () {
        if (static::query()->exists()) {
            abort(400, 'Only one admin is allowed.');
        }
    });

    static::deleting(function ($admin) {
        if ($admin->user) {
            $admin->user->delete();
        }
    });
}

}
