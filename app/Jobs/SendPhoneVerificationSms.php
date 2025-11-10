<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendPhoneVerificationSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;

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
        try {
            // Generate a random 6-digit code
            $verificationCode = random_int(100000, 999999);

            // Set an expiration time (e.g., 10 minutes from now)
            $this->user->update([
                'phone_verification_code' => $verificationCode,
                'phone_verification_expires_at' => now()->addMinutes(10),
            ]);

            // SmsService::send($this->user->phone, "Your verification code is: {$verificationCode}");

            Log::info("SMS for user {$this->user->id}: Your verification code is {$verificationCode}");
        } catch (\Exception $e) {
            Log::error("Failed to send phone verification SMS to user {$this->user->id}: " . $e->getMessage());
        }
    }
}
