<?php

namespace Tests\Unit;

use App\Services\GithubWebhookService;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GithubWebhookServiceTest extends TestCase
{
    private GithubWebhookService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GithubWebhookService();
    }

    #[Test]
    public function verify_signature_returns_false_when_header_missing(): void
    {
        $request = Request::create('/webhook', 'POST', [], [], [], [], 'payload body');
        $request->headers->remove('X-Hub-Signature-256');

        $result = $this->service->verifySignature($request, 'my-secret');

        $this->assertFalse($result);
    }

    #[Test]
    public function verify_signature_returns_true_with_valid_hmac(): void
    {
        $secret  = 'my-webhook-secret';
        $body    = '{"action":"push"}';
        $hmac    = 'sha256=' . hash_hmac('sha256', $body, $secret);

        $request = Request::create('/webhook', 'POST', [], [], [], [], $body);
        $request->headers->set('X-Hub-Signature-256', $hmac);

        $result = $this->service->verifySignature($request, $secret);

        $this->assertTrue($result);
    }

    #[Test]
    public function verify_signature_returns_false_with_invalid_hmac(): void
    {
        $secret  = 'my-webhook-secret';
        $body    = '{"action":"push"}';

        $request = Request::create('/webhook', 'POST', [], [], [], [], $body);
        $request->headers->set('X-Hub-Signature-256', 'sha256=invalidhash');

        $result = $this->service->verifySignature($request, $secret);

        $this->assertFalse($result);
    }

    #[Test]
    public function handle_returns_null_when_repo_name_missing_in_payload(): void
    {
        $payload = ['repository' => []]; // no 'name' key

        $result = $this->service->handle('push', $payload);

        $this->assertNull($result);
    }
}
