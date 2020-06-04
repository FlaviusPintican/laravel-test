<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('comment', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('post_id')->unique();
            $table->string('name', 100);
            $table->string('email', 50);
            $table->longText('body');
            $table->timestamps();
            $table->foreign('post_id')
                  ->references('id')
                  ->on('post')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('comment');
    }
}
