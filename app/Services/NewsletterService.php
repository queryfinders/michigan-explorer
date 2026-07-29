<?php

namespace App\Services;

use App\Models\Subscriber;
use App\Mail\VerifySubscription;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsletterService
{
    /**
     * Subscribe a new or pending email address.
     */
    public function subscribe(array $data): array
    {
        $email = strtolower(trim($data['email']));
        $source = $data['source'];
        $ipAddress = $data['ip_address'];
        $userAgent = $data['user_agent'] ?? null;

        // Check if subscriber already exists
        $subscriber = Subscriber::where('email', $email)->first();

        if ($subscriber) {
            // Case 1: Already verified and active
            if ($subscriber->is_verified && $subscriber->is_active) {
                return [
                    'success' => false,
                    'message' => 'This email is already subscribed.'
                ];
            }

            // Case 2: Already verified but unsubscribed (re-subscribing)
            if ($subscriber->is_verified && !$subscriber->is_active) {
                $subscriber->update([
                    'is_active' => true,
                    'unsubscribed_at' => null,
                    'source' => $source,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                ]);

                return [
                    'success' => true,
                    'message' => 'Your subscription has been re-activated successfully.'
                ];
            }

            // Case 3: Pending verification (resending token)
            $token = Str::random(40);
            $subscriber->update([
                'verification_token' => $token,
                'verification_token_expires_at' => Carbon::now()->addHours(24),
                'source' => $source,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ]);

            // Dispatch verification email
            Mail::to($subscriber->email)->queue(new VerifySubscription($subscriber));

            return [
                'success' => true,
                'message' => 'Please check your email to verify your subscription.'
            ];
        }

        // Case 4: Brand new subscriber
        $token = Str::random(40);
        $subscriber = Subscriber::create([
            'email' => $email,
            'source' => $source,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'verification_token' => $token,
            'verification_token_expires_at' => Carbon::now()->addHours(24),
            'is_verified' => false,
            'is_active' => true
        ]);

        // Dispatch verification email
        Mail::to($subscriber->email)->queue(new VerifySubscription($subscriber));

        return [
            'success' => true,
            'message' => 'Please check your email to verify your subscription.'
        ];
    }

    /**
     * Verify a subscriber using token.
     */
    public function verify(string $token): array
    {
        $subscriber = Subscriber::where('verification_token', $token)->first();

        if (!$subscriber) {
            return [
                'status' => 'invalid',
                'message' => 'Invalid verification token.'
            ];
        }

        // Check if token is expired
        if ($subscriber->verification_token_expires_at && $subscriber->verification_token_expires_at->isPast()) {
            return [
                'status' => 'expired',
                'message' => 'Verification link has expired. Please sign up again.'
            ];
        }

        // Check if already verified
        if ($subscriber->is_verified) {
            return [
                'status' => 'already_verified',
                'message' => 'This subscription has already been confirmed.'
            ];
        }

        // Mark as verified
        $subscriber->update([
            'is_verified' => true,
            'verified_at' => Carbon::now(),
            'verification_token' => null,
            'verification_token_expires_at' => null
        ]);

        return [
            'status' => 'success',
            'message' => 'Subscription confirmed successfully!'
        ];
    }

    /**
     * Unsubscribe a subscriber using token.
     */
    public function unsubscribe(string $token): array
    {
        // For unsubscribe, we need a secure way to verify who they are.
        // We will query by verification token or we can search by a secure hash of their email/id.
        // Let's lookup a subscriber that is active. Since verified_at is cleared on success, 
        // let's use a dedicated unsubscribe token or lookup by their email signature, or use their original verified id/token.
        // Wait! The user specifies: "Unsubscribe /newsletter/unsubscribe/{token}".
        // Let's generate a unique token or use their email / uuid / subscriber ID as token.
        // Let's look up by subscriber id/hash or a dedicated column. If we use their ID or a hash of their email, we can query it:
        
        $subscriber = Subscriber::where('email', base64_decode($token))->first();
        if (!$subscriber) {
            // Fallback: try finding by token if they saved it
            $subscriber = Subscriber::where('verification_token', $token)->first();
        }

        if (!$subscriber) {
            // Find by base64 or md5 hash matching email
            $subscribers = Subscriber::all();
            foreach($subscribers as $s) {
                if (md5($s->email) === $token) {
                    $subscriber = $s;
                    break;
                }
            }
        }

        if (!$subscriber) {
            return [
                'success' => false,
                'message' => 'Subscriber not found.'
            ];
        }

        if (!$subscriber->is_active) {
            return [
                'success' => true,
                'message' => 'You are already unsubscribed from our newsletter.'
            ];
        }

        // Deactivate
        $subscriber->update([
            'is_active' => false,
            'unsubscribed_at' => Carbon::now()
        ]);

        return [
            'success' => true,
            'message' => 'You have successfully unsubscribed from the Michigan Explorer newsletter.'
        ];
    }
}
