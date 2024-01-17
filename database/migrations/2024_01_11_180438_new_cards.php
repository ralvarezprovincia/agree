<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('list_expansion', function (Blueprint $table1) {
            $table1->id();
            $table1->string('name');
            $table1->softDeletes();
            $table1->timestamps();
        });

        Schema::create('list_type', function (Blueprint $table2) {
            $table2->id();
            $table2->string('name');
            $table2->softDeletes();
            $table2->timestamps();
        });

        Schema::create('list_rarity', function (Blueprint $table3) {
            $table3->id();
            $table3->string('name');
            $table3->softDeletes();
            $table3->timestamps();
        });

        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('first_edition', ['1', '0']);  
            $table->unsignedBigInteger('id_expansion')->index();  
            $table->unsignedBigInteger('id_type')->index();  
            $table->unsignedBigInteger('id_rarity')->index();  
            $table->float('price', 20, 4);
            $table->string('img')->nullable();
            $table->unsignedBigInteger('id_user')->index();
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('list_expansion')->insert([
            'name' => 'Base Set',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); 
        DB::table('list_expansion')->insert([
            'name' => 'Jungle',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); 
        DB::table('list_expansion')->insert([
            'name' => 'Fossil',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); 
        DB::table('list_expansion')->insert([
            'name' => 'Base Set 2',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); 
        
        DB::table('list_type')->insert([
            'name' => 'Agua',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); 
        DB::table('list_type')->insert([
            'name' => 'Fuego',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('list_type')->insert([
            'name' => 'Hierba',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('list_type')->insert([
            'name' => 'Eléctrico',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('list_type')->insert([
            'name' => 'etc',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('list_rarity')->insert([
            'name' => 'Común',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); 
        DB::table('list_rarity')->insert([
            'name' => 'No Común',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('list_rarity')->insert([
            'name' => 'Rara',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_expansion');
        Schema::dropIfExists('list_type');
        Schema::dropIfExists('list_rarity');
        Schema::dropIfExists('cards');
    }
};
