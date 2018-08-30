<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShortUrl5Table extends Migration
{
    public $table = 'sjd_short_url_5';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ids_id')->index("ids_id")->comment('自增id')->default(0);
            $table->string('url', 500)->comment('长链地址')->default('');
            $table->string('short', 30)->index()->comment('短链key')->default('');
            $table->tinyInteger('status')->unsigned()->comment('状态:0:失效,1:有效')->default(1);
            $table->unsignedInteger('created_at')->nullable()->default(0);
            $table->unsignedInteger('updated_at')->nullable()->default(0);
            $table->unsignedInteger('deleted_at')->nullable();

        });
        $table = env('DB_PREFIX') . $this->table;
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `$table` comment '短链关系表'");
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
