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
            $table->string('uid'); // 유저 아이디
            $table->string('name'); // 테스트 이름
            $table->string('subject'); // 주제
            $table->datetime('date'); // 생성 날짜
            $table->string('count'); // 응시 횟수
            $table->string('avg'); // 평균 점수
            $table->string('secret'); // 공개 여부
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
