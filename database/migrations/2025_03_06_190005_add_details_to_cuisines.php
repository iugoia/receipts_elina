<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cuisines', function (Blueprint $table) {
            $table->text('full_description')->nullable()->after('description');
            $table->text('history')->nullable()->after('full_description');
            $table->json('popular_dishes')->nullable()->after('history');
            $table->string('video_url')->nullable()->after('popular_dishes');
            $table->json('interesting_facts')->nullable()->after('video_url');
        });
    }

    public function down()
    {
        Schema::table('cuisines', function (Blueprint $table) {
            $table->dropColumn(['full_description', 'history', 'popular_dishes', 'video_url', 'interesting_facts']);
        });
    }
};
