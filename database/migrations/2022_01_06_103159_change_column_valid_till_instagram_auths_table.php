<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnValidTillInstagramAuthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instagram_auths', function (Blueprint $table) {
            $table->dropColumn('valid_till');
        });
        Schema::table('instagram_auths', function (Blueprint $table) {
            $table->integer('valid_till')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
