<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Services\LlmsTxtService;
use Illuminate\Http\Response;

class LlmsController extends Controller
{
    protected LlmsTxtService $llmsService;

    public function __construct(LlmsTxtService $llmsService)
    {
        $this->llmsService = $llmsService;
    }

    /**
     * Serve dynamic /llms.txt
     */
    public function llms(): Response
    {
        $content = $this->llmsService->generateLlmsTxt();

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Serve dynamic /llms-full.txt
     */
    public function llmsFull(): Response
    {
        $content = $this->llmsService->generateLlmsFullTxt();

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
