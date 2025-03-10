<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['admin', 'manager', 'member'])->default('member');
            $table->string('description');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });

        // Insere perfis
        DB::table('profiles')->insert([
            [
                'type' => 'admin',
                'description' => 'Administrador',
            ],
            [
                'type' => 'manager',
                'description' => 'Gerente de equipe',
            ],
            [
                'type' => 'member',
                'description' => 'Membro da equipe',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
