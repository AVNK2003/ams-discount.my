<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('org');
            $table->string('title');
            $table->string('img')->nullable();
            $table->string('address')->nullable();
            $table->string('working_hours')->nullable();
            $table->string('tel')->nullable();
            $table->string('site')->nullable();
            $table->string('discount');
            $table->text('description')->nullable();
            $table->string('instagram')->nullable();
            $table->string('vk')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->boolean('active')->default(false);
            $table->integer('priority')->nullable();
            $table->string('coordinates')->nullable();
            $table->integer('views')->nullable();
            $table->date('date_end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
