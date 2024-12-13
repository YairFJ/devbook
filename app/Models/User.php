<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\FriendshipStatus;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación uno a muchos con Post
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function friendships()
    {
        return $this->hasMany(Friend::class);
    }

    // Relación many-to-many con la tabla 'friends'
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
                    ->wherePivot('status', FriendshipStatus::ACCEPTED->value);
    }

    public function pendingRequests()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
                    ->wherePivot('status', FriendshipStatus::PENDING->value);
    }

    public function friendOf()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id');
    }

    public function allFriends()
    {
        return $this->friends()->union($this->friendOf());
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

     // Relación muchos a muchos con Likes
     public function likedPosts()
     {
        return $this->belongsToMany(Post::class, 'likes');
         
     }
}
