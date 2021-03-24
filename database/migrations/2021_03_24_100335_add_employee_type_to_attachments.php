<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmployeeTypeToAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attachments', function (Blueprint $table) {
            DB::statement("ALTER TABLE attachments MODIFY COLUMN type ENUM('employee','quote',
            'image',
            'youtube',
            'tweet');");
            $table->string('employee_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attachments', function (Blueprint $table) {
            DB::statement("ALTER TABLE attachments MODIFY COLUMN type ENUM('quote',
            'image',
            'youtube',
            'tweet');");
            $table->dropColumn('employee_name');
        });
    }
}
