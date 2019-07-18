<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id'); 
            $table->integer('status_id')->default(1); 
            $table->integer('folio'); 
            
            $table->string('contact_phone')->nullable();
            $table->string('contact_available')->nullable();
            $table->text('description');
            
            $table->integer('assigned_to')->nullable();
            $table->datetime('assigned_at')->nullable();
            $table->integer('procedure_id')->nullable();
            $table->string('against_name')->nullable();
            $table->string('against_position')->nullable();
            $table->string('against_relation')->nullable();
            
            $table->text('facts')->nullable();
            $table->string('frequency')->nullable();
            $table->date('date_since')->nullable();
            $table->date('date_until')->nullable();
            $table->string('place')->nullable();
            $table->string('attitude')->nullable();
            $table->string('reaction')->nullable();
            $table->text('other_cases')->nullable();
            $table->text('witnesses')->nullable();
            $table->string('made_changes')->nullable();
            $table->string('affectation')->nullable();
            $table->text('long_terms_considerations')->nullable();
            $table->string('psy_support')->nullable();
            $table->string('medical_support')->nullable();
            $table->text('boss_knowledge')->nullable();
            $table->text('solution')->nullable();
            $table->text('evidence')->nullable();
            $table->text('aditional_info')->nullable();
            
            $table->integer('resolution_id')->nullable();
            $table->date('resolution_date')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaints');
    }
}
