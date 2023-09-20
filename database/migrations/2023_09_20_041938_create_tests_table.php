<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->nullable(); // 유저 아이디
            $table->string('name')->nullable(); // 테스트 이름
            $table->string('subject')->nullable(); // 주제
            $table->datetime('date')->nullable(); // 생성 날짜
            $table->string('count')->nullable(); // 응시 횟수
            $table->string('avg')->nullable(); // 평균 점수
            $table->string('secret')->nullable(); // 공개 여부
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
        Schema::dropIfExists('tests');
    }
}
