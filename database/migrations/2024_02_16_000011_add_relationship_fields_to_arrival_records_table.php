<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToArrivalRecordsTable extends Migration
{
    public function up()
    {
        Schema::table('arrival_records', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_9493409')->references('id')->on('users');
        });
    }
}
