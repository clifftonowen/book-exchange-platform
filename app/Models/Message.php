<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'exchange_request_id',
        'content',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Get the user who sent this message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id'); // 'sender_id' is the foreign key in 'messages' table
    }

    /**
     * Get the user who received this message.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id'); // 'receiver_id' is the foreign key in 'messages' table
    }

    /**
     * Get the exchange request this message belongs to.
     */
    public function exchangeRequest(): BelongsTo
    {
        return $this->belongsTo(ExchangeRequest::class); // Assumes foreign key is exchange_request_id
    }
}