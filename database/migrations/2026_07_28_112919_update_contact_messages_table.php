<?php

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
        Schema::table('contact_messages', function (Blueprint $table) {
            // Rename name to full_name
            $table->renameColumn('name', 'full_name');
            
            // Drop is_read
            $table->dropColumn('is_read');
            
            // Add new columns
            $table->string('ip_address', 45)->nullable()->after('message');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->enum('status', ['new', 'read', 'replied', 'closed'])->default('new')->after('user_agent');
            $table->timestamp('replied_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->renameColumn('full_name', 'name');
            $table->boolean('is_read')->default(0)->after('message');
            $table->dropColumn(['ip_address', 'user_agent', 'status', 'replied_at']);
        });
    }
};
