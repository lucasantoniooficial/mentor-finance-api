<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class PurchaseMonth extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_list_id',
        'purchase_date',
        'total',
        'status',
    ];

    public function purchaseList(): BelongsTo
    {
        return $this->belongsTo(PurchaseList::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'purchaseable');
    }

    public function isPending(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->status === 'Pendente';
            }
        );
    }

    public function isShipping(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->status === 'Comprando';
            }
        );
    }
}
