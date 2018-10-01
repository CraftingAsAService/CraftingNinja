<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_groups', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('group_id')->unsigned()->default(0);
            $table->integer('job_id')->unsigned()->default(0);

            $table->unique(['group_id', 'job_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_groups');
    }
}
