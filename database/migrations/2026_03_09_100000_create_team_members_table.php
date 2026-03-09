<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->text('title');
            $table->timestamps();
        });

        // Migrate existing data: create cristina from current About.title
        $title = DB::table('about')->value('title');
        DB::table('team_members')->insert([
            'slug' => 'cristina',
            'title' => $title ?? '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add team_member_id to resumes
        Schema::table('resumes', function (Blueprint $table) {
            $table->foreignId('team_member_id')->nullable()->after('id')->constrained('team_members')->nullOnDelete();
        });

        // Link existing resumes to cristina
        $teamMemberId = DB::table('team_members')->where('slug', 'cristina')->value('id');
        if ($teamMemberId) {
            DB::table('resumes')->whereNull('team_member_id')->update(['team_member_id' => $teamMemberId]);
        }

        // Remove title and employees from about
        Schema::table('about', function (Blueprint $table) {
            $table->dropColumn(['title', 'employees']);
        });
    }

    public function down()
    {
        Schema::table('about', function (Blueprint $table) {
            $table->text('title')->nullable();
            $table->text('employees')->nullable();
        });

        Schema::table('resumes', function (Blueprint $table) {
            $table->dropForeign(['team_member_id']);
            $table->dropColumn('team_member_id');
        });

        Schema::dropIfExists('team_members');
    }
};
