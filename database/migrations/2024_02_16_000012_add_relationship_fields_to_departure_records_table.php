<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDepartureRecordsTable extends Migration
{
    public function up()
    {
        Schema::table('departure_records', function (Blueprint $table) {
            $table->unsignedBigInteger('arrival_id')->nullable();
            $table->foreign('arrival_id', 'arrival_fk_9493417')->references('id')->on('arrival_records');
        });
    }
}
