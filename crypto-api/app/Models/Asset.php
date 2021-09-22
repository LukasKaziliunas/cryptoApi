<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'crypto',
        'amount',
        'user_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getUserCryptoAmounts($userId)
    {
        return DB::table('assets')->select('crypto', 'amount')->where( 'user_id', $userId )->get();
    }
}
