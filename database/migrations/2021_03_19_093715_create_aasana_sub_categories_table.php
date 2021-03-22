<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAasanaSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aasana_sub_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('aasana_categories_id');
            $table->string('subcategory_name',150);
            $table->text('subcategory_description');
            $table->Integer('status');
            $table->string('subcategory_image',150);
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
        Schema::dropIfExists('aasana_sub_categories');
    }
}
