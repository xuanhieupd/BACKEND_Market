<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Modules\ShortUrl\Models\Entities\ShortUrl;

class CreateShortUrlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @author xuanhieupd
     */
    public function up()
    {
        Schema::connection('box')->create((new ShortUrl())->getTable(), function (Blueprint $table) {
            $table->unsignedInteger('short_id');

            $table->char('code', 8);
            $table->mediumText('long_url');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @author xuanhieupd
     */
    public function down()
    {
        Schema::connection('box')->dropIfExists((new ShortUrl())->getTable());
    }
}
