<?php
// database/migrations/2026_01_01_000002_create_events_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('event_title');
            $table->enum('category', ['Symposium', 'Seminar', 'Workshop', 'Conference', 'Celebration', 'Others'])->default('Symposium');
            $table->string('category_other')->nullable(); // if category = Others
            $table->text('description')->nullable();
            $table->string('venue')->nullable();
            $table->datetime('date_start');
            $table->datetime('date_end');
            $table->enum('publish_status', ['draft', 'published'])->default('draft');
            $table->string('announcement')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
