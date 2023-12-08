<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_month_id',
        'item_list_id',
        'quantity',
        'purchased',
    ];

    public function purchaseMonth(): BelongsTo
    {
        return $this->belongsTo(PurchaseMonth::class);
    }

    public function itemList(): BelongsTo
    {
        return $this->belongsTo(ItemList::class);
    }
}
