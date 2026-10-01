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
        Schema::table('employees', function (Blueprint $table) {
            // Menambahkan kolom rfid_uid, posisinya kita taruh setelah kolom jabatan/divisi
            $table->string('rfid_uid')->nullable()->unique()->after('jabatan_posisi'); 
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('rfid_uid');
        });
    }
};
