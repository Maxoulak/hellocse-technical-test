<?php

use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Admin::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Profile::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('comment');
            $table->timestamps();

            $table->unique(['admin_id', 'profile_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
