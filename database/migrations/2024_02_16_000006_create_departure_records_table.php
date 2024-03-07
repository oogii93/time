<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartureRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('departure_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('recorded_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
