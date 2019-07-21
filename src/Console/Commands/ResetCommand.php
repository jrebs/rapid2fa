<?php

namespace Jrebs\Rapid2FA\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class ResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rapid2fa:reset {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the secret two factor key for a user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = User::where('email', $this->argument('email'))->first();
        if (!$user) {
            $this->error('user not found for email');
            return;
        }
        $this->table(['id', 'name', 'email'], [[
            'id' => 1,
            'name' => $user->name,
            'email' => $user->email,
        ]]);
        if (!$this->confirm('Confirm to clear two-factor key')) {
            return;
        }
        $user->google2fa_secret = null;
        $user->save();
    }
}
