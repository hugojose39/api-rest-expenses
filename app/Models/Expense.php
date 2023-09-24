<?php

namespace App\Models;

use App\Events\Expense\Created;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dispatchesEvents = [
        'created' => Created::class,
    ];

    protected $fillable = [
        'description',
        'user_id',
        'date',
        'value',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => 'R$ '.number_format(($value / 100), 2)
        );
    }
}
