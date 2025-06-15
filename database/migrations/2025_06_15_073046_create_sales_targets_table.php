<?php
// database/migrations/xxxx_xx_xx_create_sales_targets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();
            $table->decimal('monthly_target', 15, 2)->default(0);
            $table->decimal('current_achievement', 15, 2)->default(0);
            $table->year('target_year')->default(date('Y'));
            $table->tinyInteger('target_month')->default(date('n'));
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index untuk optimasi query
            $table->index(['target_year', 'target_month', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_targets');
    }
};
