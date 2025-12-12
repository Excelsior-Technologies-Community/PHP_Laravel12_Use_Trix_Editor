<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Create the 'posts' table
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // Primary key 'id' (auto-increment)

            $table->string('title'); // Title of the post
            $table->longText('body'); // Body content of the post (HTML from Trix editor)

            // Additional fields
            $table->tinyInteger('status')->default(1); // Post status: 1 = active, 0 = inactive
            $table->unsignedBigInteger('created_by')->nullable(); // User ID who created the post (optional)
            $table->unsignedBigInteger('updated_by')->nullable(); // User ID who last updated the post (optional)

            $table->softDeletes(); // Adds 'deleted_at' column for soft deletes

            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the 'posts' table if the migration is rolled back
        Schema::dropIfExists('posts');
    }
};
