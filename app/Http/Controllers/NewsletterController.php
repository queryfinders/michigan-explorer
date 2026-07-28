<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribeRequest;
use App\Services\NewsletterService;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    protected NewsletterService $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    /**
     * Handle incoming AJAX subscription request.
     */
    public function subscribe(SubscribeRequest $request)
    {
        $data = [
            'email' => $request->input('email'),
            'source' => $request->input('source'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ];

        $result = $this->newsletterService->subscribe($data);

        return response()->json($result);
    }

    /**
     * Handle subscriber verification.
     */
    public function verify(string $token)
    {
        $result = $this->newsletterService->verify($token);

        return view('web.newsletter.verify', [
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    /**
     * Handle unsubscribe request.
     */
    public function unsubscribe(string $token)
    {
        $result = $this->newsletterService->unsubscribe($token);

        return view('web.newsletter.unsubscribe', [
            'success' => $result['success'],
            'message' => $result['message']
        ]);
    }
}
