<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Modules\Product\Models\Entities\Product;

class AddAttachmentIdToProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table((new Product())->getTable(), function (Blueprint $table) {
//            if (!Schema::hasColumn('hnw_product', 'attachment_id')) {
//                $table->unsignedInteger('attachment_id')
//                    ->nullable()
//                    ->after('user_id');
//            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table((new Product())->getTable(), function (Blueprint $table) {
//            $table->dropColumn('attachment_id');
        });
    }
}
