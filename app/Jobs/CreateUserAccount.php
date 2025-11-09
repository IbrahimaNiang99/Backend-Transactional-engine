<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Container\Attributes\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Str;

class CreateUserAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            Account::create([
                'user_id' => $this->user->id,
                'balance' => 0,
                'account_number' => (string) Str::uuid(),
            ]);
        }catch(\Exception $e){
            FacadesLog::error('Failed to create account for user ID ' . $this->user->id . ': ' . $e->getMessage());
        }
    }
}