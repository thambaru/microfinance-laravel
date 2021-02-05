<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 15)->after('name');
            $table->string('full_name', 200)->after('name'); 
            $table->string('address')->after('email');
            $table->string('nic')->after('email');
            $table->string('phone_num', 20)->after('email')->nullable();
            $table->double('commiss_perc')->after('nic');
            $table->double('commiss_bal')->after('nic')->default(0);
            $table->boolean('is_active')->after('commiss_perc')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'full_name', 'phone_num', 'address', 'nic', 'commiss_bal', 'commiss_perc', 'is_active']);
        });
    }
}
