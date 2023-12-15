<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'city_id', 'content', 'parent_id', 'likes']; //security: protect against mass-assignment vulnerabilities

    //these following methods are called relationships
    public function user() {
        return $this->belongsTo(User::class); //the comment belongs to a user
    }

    public function subcomments() {
        return $this->hasMany(Comment::class, 'parent_id'); //the comment can have multiple subcomments
        // parent_id is the foreign key that links the current model with the related model (the same in this case)
        // the current model can have multiple instances of the related model (comments can have many comments)
    }
}

//fillable, guarded, hidden, casts(add data types to attributes when dealing with json columns)