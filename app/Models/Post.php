<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes; 
    // HasFactory: Enables factory-based testing and seeding
    // SoftDeletes: Allows "soft deleting" records (adds deleted_at column instead of permanently deleting)

    // Fields that are mass assignable
    protected $fillable = [
        'title',       // Post title
        'body',        // Post content (HTML from Trix editor)
        'status',      // Post status: 1 = active, 0 = inactive
        'created_by',  // ID of the user who created the post
        'updated_by'   // ID of the user who last updated the post
    ];

    // You can also add custom relationships or accessors here if needed
}
    