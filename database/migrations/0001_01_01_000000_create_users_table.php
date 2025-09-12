    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('fullname');
                $table->string('email')->unique();
                $table->string('password');
                $table->enum('role', ['USER', 'ADMIN', 'SUPERADMIN']);
                $table->string('baris1');
                $table->string('baris2');
                $table->string('baris3');
                $table->string('baris4');
                $table->string('sizebaris1');
                $table->string('sizebaris2');
                $table->string('sizebaris3');
                $table->string('sizebaris4');
                $table->timestamps();
            });

            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('users');
            Schema::dropIfExists('sessions');
        }
    };
