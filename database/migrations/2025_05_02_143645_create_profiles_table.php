<?php

use App\Enums\ProfileStatus;
use App\Models\Admin;
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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Admin::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('lastname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ProfileStatus::values())->default(ProfileStatus::INACTIVE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
