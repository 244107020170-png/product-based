<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
        });

        $reservedUsernames = [];

        DB::table('users')
            ->select('id', 'name', 'email')
            ->orderBy('id')
            ->get()
            ->each(function (object $user) use (&$reservedUsernames): void {
                $username = $this->generateUniqueUsername($user, $reservedUsernames);

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['username' => $username]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_username_unique');
            $table->dropColumn('username');
        });
    }

    /**
     * Generate a unique username for an existing user.
     *
     * @param  array<string, bool>  $reservedUsernames
     */
    private function generateUniqueUsername(object $user, array &$reservedUsernames): string
    {
        $baseUsername = $this->normalizeUsername($user->name)
            ?: $this->normalizeUsername(Str::before((string) $user->email, '@'))
            ?: 'user';

        $username = $baseUsername;
        $suffix = 2;

        while (isset($reservedUsernames[$username])) {
            $suffixText = '_'.$suffix;
            $username = Str::substr($baseUsername, 0, 255 - strlen($suffixText)).$suffixText;
            $suffix++;
        }

        $reservedUsernames[$username] = true;

        return $username;
    }

    /**
     * Normalize a username candidate.
     */
    private function normalizeUsername(?string $value): string
    {
        return Str::substr(Str::slug(Str::lower((string) $value), '_'), 0, 255);
    }
};
