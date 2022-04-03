<?php

namespace App\Models;

use App\Events\SubscriptionCanceled;
use App\Events\SubscriptionRenewed;
use App\Events\SubscriptionStarted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Subscription extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'uId',
        'clientToken',
        'receiptId',
        'status',
        'expireDate'
    ];
    public static function boot()
    {
        parent::boot();
        static::created(function ($subscription) {
            SubscriptionStarted::dispatch($subscription);
        });

        static::updated(function ($subscription) {
            $changes = $subscription->getChanges();
            if (isset($changes['expireDate'])) {
                SubscriptionRenewed::dispatch($subscription);
            }
            if (isset($changes['status']) && $changes['status'] === false) {
                SubscriptionCanceled::dispatch($subscription);
            }
        });

        static::deleted(function ($subscription) {
            SubscriptionCanceled::dispatch($subscription);
        });
    }

    public function device(): HasOne
    {
        return $this->hasOne(Device::class, 'id', 'uId');
    }
}
