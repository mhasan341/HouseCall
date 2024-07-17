<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrugsTable extends Migration
{
    public function up()
    {
        Schema::create('drugs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rxcui');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->longText('side_effects')->nullable();
            $table->timestamps();
        });
    }
}
