<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNotificationTipoMedio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('tipo_medio')->nullable();;
            $table->string('link')->nullable();  
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
        Schema::table('notifications', function(Blueprint $table) {
            $table->dropColumn('tipo_medio');
            $table->string('link');  
        });
    }
}
