<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = 'status';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable($this->table)) {
            Schema::create($this->table, function (Blueprint $table) {
                $table->id();
                $table->string('name', 100)->nullable()->default(null);
                $table->softDeletes();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')
                    ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable($this->table)) {
            Schema::dropIfExists($this->table);
        }
    }
};
