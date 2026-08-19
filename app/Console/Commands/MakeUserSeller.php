<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserSeller extends Command
{
    protected $signature = 'user:make-seller {email : Email address of the user}';
    protected $description = 'Upgrade a user role to seller/creator';

    public function handle(): int
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email [{$email}] not found.");
            $this->line('Available users:');
            User::select('id', 'name', 'email', 'role')->each(function ($u) {
                $this->line("  [{$u->id}] {$u->name} <{$u->email}> role={$u->role}");
            });
            return self::FAILURE;
        }

        $old = $user->role;
        $user->role = 'seller';
        $user->save();

        $this->info("Done! User [{$user->name}] role changed: {$old} -> seller");
        return self::SUCCESS;
    }
}
