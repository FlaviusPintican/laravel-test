<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table): void {
            $table->id();
            $table->string('username', 50)->unique()->index();
            $table->string('password', 100);
            $table->string('first_name', 50)->index();
            $table->string('family_name', 100)->index();
            $table->string('email', 50)->unique()->index();
            $table->string('phone_number', 50)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
}
