<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\UserRole;

class AddRoleColumnToUserTable extends Migration
{

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', UserRole::Types)->default(UserRole::USER);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
}
