<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Listeners;

use Illuminate\Auth\Events\PasswordReset;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class LogPasswordReset
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(PasswordReset $event): void
    {
        UserActivityLog::create([
            'user_id' => $event->user->id,
            'event_type' => 'password_reset',
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'created_at' => now(),
        ]);
    }
}
