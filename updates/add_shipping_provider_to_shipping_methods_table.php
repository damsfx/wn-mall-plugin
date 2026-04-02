<?php namespace Winter\Mall\Updates;

use Winter\Storm\Database\Updates\Migration;
use Schema;

return new class extends Migration
{
    protected string $table = 'winter_mall_shipping_methods';

    public function up()
    {
        if (!Schema::hasTable($this->table) || Schema::hasColumn($this->table, 'shipping_provider')) {
            return;
        }

        Schema::table($this->table, function ($table) {
            $table->string('shipping_provider')->nullable()->after('published');
        });
    }

    public function down()
    {
        if (!Schema::hasTable($this->table) || !Schema::hasColumn($this->table, 'shipping_provider')) {
            return;
        }

        Schema::table($this->table, function ($table) {
            $table->dropColumn('shipping_provider');
        });
    }
};