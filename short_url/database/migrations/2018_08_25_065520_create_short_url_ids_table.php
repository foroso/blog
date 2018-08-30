<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShortUrlIdsTable extends Migration
{
    public $table = 'sjd_short_url_ids';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('created_at')->nullable()->default(0);
            $table->unsignedInteger('updated_at')->nullable()->default(0);
            $table->unsignedInteger('deleted_at')->nullable();
        });
        $table = env('DB_PREFIX') . $this->table;
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `$table` comment '短链自增表'");
        \Illuminate\Support\Facades\DB::statement("alter table `$table` AUTO_INCREMENT=100000");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
