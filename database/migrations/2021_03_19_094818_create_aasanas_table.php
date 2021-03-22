<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAasanasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aasanas', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('aasana_sub_categories_id');
            $table->string('aasana_name',150);
            $table->text('aasana_description');
            $table->string('assana_tag',150);
            $table->string('assana_video_id',100);
            $table->string('assana_video_duration',15);
            $table->text('assana_benifits');
            $table->text('assana_instruction');
            $table->Integer('status');           
            $table->integer('updated_by');
            $table->softDeletes();
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
        Schema::dropIfExists('aasanas');
    }
}
