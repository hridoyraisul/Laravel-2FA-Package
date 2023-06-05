<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTfaToCustoms extends Migration
{
    protected $tableName;
    public function __construct()
    {
        $this->tableName = config('laravel2fa.2fa_with_table');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            if (Schema::hasTable($this->tableName) && !Schema::hasColumns($this->tableName,['google2fa_secret','google2fa_enabled','google2fa_verify_status'])) {
                $table->longText("google2fa_secret")->nullable();
                $table->boolean("google2fa_enabled")->default(false);
                $table->enum("google2fa_verify_status",['verified','failed','unverified'])->default('unverified');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //
        });
    }
}
