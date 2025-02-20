<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->string('staff_id')->unique();
            $table->string('ic_no')->unique();
            $table->decimal('salary', 10, 2);
        });
    }

    public function down()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['staff_id', 'ic_no', 'salary']);
        });
    }
};
