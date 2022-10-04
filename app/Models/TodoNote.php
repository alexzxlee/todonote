<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoNote extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'content',
        'completion_time',
    ];

    /**
     * Define one-user-to-one-todonote relationship.
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
