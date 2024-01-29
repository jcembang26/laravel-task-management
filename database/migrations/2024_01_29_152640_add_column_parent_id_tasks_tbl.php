<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected $table = 'tasks';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->table, function (Blueprint $table) {
            if (!Schema::hasColumn($this->table, 'parent_id')) {
                $table->integer('parent_id')->unsigned()->nullable()->default(0)->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->table, function (Blueprint $table) {
            if (Schema::hasColumn($this->table, 'parent_id')) {
                $table->dropColumn('parent_id');
            }
        });
    }
};
