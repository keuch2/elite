<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('report_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Name of the report config (e.g., "Basic Athlete Report")
            $table->json('fields'); // JSON array of selected fields (e.g., ["nombre", "talla_parado"])
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('report_configs');
    }
};
