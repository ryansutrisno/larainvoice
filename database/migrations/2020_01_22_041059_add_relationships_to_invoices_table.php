<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationshipsToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            //add relation invoice table
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade'); //Cascade berarti apabila data induk berubah maka data anak juga berubah
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            //drop table
            $table->dropForeign('invoices_customer_id_foreign');
        });
    }
}
