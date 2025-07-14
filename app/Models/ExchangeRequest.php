<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ExchangeRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'book_id',        
        'requester_id',   
        'status',         
    ];

    /**
     * Get the book associated with the exchange request.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the user who made the exchange request.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
    /**
     * Get the rating associated with this exchange request.
     */
    public function rating(): HasOne
    {
        return $this->hasOne(Rating::class, 'exchange_request_id');
    }
    /**
     * Get the messages related to this exchange request.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'exchange_request_id');
    }
}