<?php

namespace App\Services;

use App\Models\EngineeringEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $model;
    private string $endpoint;

    public function __construct()
    {
        $this->apiKey   = config('services.gemini.api_key') ?: env('GEMINI_API_KEY', '');
        $this->model    = 'gemini-2.5-flash';
        $this->endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
    }

    public function generateSummary(EngineeringEvent $event): array
    {
        // If no API key, return mock data for development
        if (empty($this->apiKey) || $this->apiKey === 'your_gemini_api_key_here') {
            return $this->getMockSummary($event);
        }

        $prompt = $this->buildPrompt($event);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(60)->post("{$this->endpoint}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature'      => 0.7,
                    'maxOutputTokens'  => 4096,
                    'responseMimeType' => 'application/json',
                    'thinkingConfig'   => [
                        'thinkingBudget' => 0,
                    ],
                    'responseSchema'   => [
                        'type' => 'OBJECT',
                        'properties' => [
                            'technical_summary'       => ['type' => 'STRING'],
                            'business_summary'        => ['type' => 'STRING'],
                            'client_friendly_summary' => ['type' => 'STRING'],
                            'risk_level'              => ['type' => 'STRING', 'enum' => ['low', 'medium', 'high', 'critical']],
                            'business_impact'         => ['type' => 'STRING'],
                            'recommended_action'      => ['type' => 'STRING'],
                        ],
                        'required' => [
                            'technical_summary',
                            'business_summary',
                            'client_friendly_summary',
                            'risk_level',
                            'business_impact',
                            'recommended_action'
                        ],
                    ],
                ],
            ]);

            if ($response->failed()) {
                Log::error('Gemini API request failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return $this->getMockSummary($event);
            }

            $data = $response->json();
            Log::info('Gemini API raw response data', ['data' => $data]);
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // Strip markdown code blocks if present (just in case)
            $text = preg_replace('/^```(?:json)?\n?/m', '', $text);
            $text = preg_replace('/\n?```$/m', '', $text);
            $text = trim($text);

            $parsed = json_decode($text, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse Gemini JSON response', ['raw' => $text]);
                return $this->getMockSummary($event);
            }

            return $this->normalizeResponse($parsed);
        } catch (\Exception $e) {
            Log::error('GeminiService exception: ' . $e->getMessage(), [
                'event_id' => $event->id,
            ]);
            return $this->getMockSummary($event);
        }
    }

    public function generateProjectReport(array $projectData): array
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_gemini_api_key_here') {
            return $this->getMockProjectReport($projectData);
        }

        $prompt = $this->buildProjectReportPrompt($projectData);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(60)->post("{$this->endpoint}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature'      => 0.7,
                    'maxOutputTokens'  => 4096,
                    'responseMimeType' => 'application/json',
                    'thinkingConfig'   => [
                        'thinkingBudget' => 0,
                    ],
                    'responseSchema'   => [
                        'type' => 'OBJECT',
                        'properties' => [
                            'progress_summary' => ['type' => 'STRING'],
                            'completed_work' => [
                                'type' => 'ARRAY',
                                'items' => ['type' => 'STRING']
                            ],
                            'issues_and_risks' => [
                                'type' => 'ARRAY',
                                'items' => ['type' => 'STRING']
                            ],
                            'business_impact' => ['type' => 'STRING'],
                            'recommended_actions' => [
                                'type' => 'ARRAY',
                                'items' => ['type' => 'STRING']
                            ],
                            'overall_risk_level' => ['type' => 'STRING', 'enum' => ['low', 'medium', 'high', 'critical']],
                        ],
                        'required' => [
                            'progress_summary',
                            'completed_work',
                            'issues_and_risks',
                            'business_impact',
                            'recommended_actions',
                            'overall_risk_level'
                        ],
                    ],
                ],
            ]);

            if ($response->failed()) {
                return $this->getMockProjectReport($projectData);
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

            $text    = preg_replace('/^```(?:json)?\n?/m', '', $text);
            $text    = preg_replace('/\n?```$/m', '', $text);
            $text    = trim($text);
            $parsed  = json_decode($text, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->getMockProjectReport($projectData);
            }

            return $parsed;
        } catch (\Exception $e) {
            Log::error('GeminiService generateProjectReport exception: ' . $e->getMessage());
            return $this->getMockProjectReport($projectData);
        }
    }

    private function buildPrompt(EngineeringEvent $event): string
    {
        return <<<PROMPT
You are BridgeOps AI, an assistant that translates software engineering activities into clear business-friendly project updates.

Input:
Source: {$event->source}
Event Type: {$event->event_type}
Title: {$event->title}
Description: {$event->description}
Actor: {$event->actor}

Task:
Generate a JSON response with these exact keys:
- technical_summary
- business_summary
- client_friendly_summary
- risk_level (one of: low, medium, high, critical)
- business_impact
- recommended_action

Rules:
- Use simple, professional Bahasa Indonesia.
- Avoid unnecessary technical jargon.
- Focus on business impact.
- Output ONLY valid JSON, no markdown code blocks.
PROMPT;
    }

    private function buildProjectReportPrompt(array $projectData): string
    {
        $eventsText = collect($projectData['recent_events'] ?? [])->map(function ($e) {
            return "- [{$e['event_type']}] {$e['title']} by {$e['actor']}";
        })->join("\n");

        $errorsText = collect($projectData['error_logs'] ?? [])->map(function ($e) {
            return "- [{$e['severity']}] {$e['title']}";
        })->join("\n");

        return <<<PROMPT
You are BridgeOps AI. Generate a comprehensive project status report.

Project: {$projectData['name']}
Client: {$projectData['client_name']}
Status: {$projectData['status']}
Period: {$projectData['start_date']} to {$projectData['end_date']}

Recent Engineering Activities:
{$eventsText}

Error Logs:
{$errorsText}

Generate a JSON response with these exact keys:
- progress_summary (string: overall progress in Bahasa Indonesia)
- completed_work (array of strings: list of completed items)
- issues_and_risks (array of strings: list of issues and risks)
- business_impact (string: overall business impact)
- recommended_actions (array of strings: list of recommended actions)
- overall_risk_level (one of: low, medium, high, critical)

Rules:
- Use simple, professional Bahasa Indonesia.
- Be concise and actionable.
- Focus on business value and client communication.
- Output ONLY valid JSON, no markdown code blocks.
PROMPT;
    }

    private function normalizeResponse(array $parsed): array
    {
        return [
            'technical_summary'       => $parsed['technical_summary'] ?? 'Aktivitas teknis telah diproses.',
            'business_summary'        => $parsed['business_summary'] ?? 'Tidak ada dampak bisnis signifikan.',
            'client_friendly_summary' => $parsed['client_friendly_summary'] ?? 'Tim sedang mengerjakan peningkatan sistem.',
            'risk_level'              => in_array($parsed['risk_level'] ?? '', ['low', 'medium', 'high', 'critical'])
                ? $parsed['risk_level']
                : 'low',
            'business_impact'    => $parsed['business_impact'] ?? '-',
            'recommended_action' => $parsed['recommended_action'] ?? 'Tidak ada tindakan khusus yang diperlukan.',
        ];
    }

    private function getMockSummary(EngineeringEvent $event): array
    {
        $riskLevel = match ($event->event_type) {
            'error_log'    => 'high',
            'issue'        => 'medium',
            'pull_request' => 'low',
            'push'         => 'low',
            default        => 'low',
        };

        return [
            'technical_summary'       => "[MOCK] Aktivitas {$event->event_type} dari {$event->actor}: {$event->title}",
            'business_summary'        => "[MOCK] Tim engineering melakukan {$event->eventTypeLabel()} pada proyek. Aktivitas ini merupakan bagian dari pengembangan rutin.",
            'client_friendly_summary' => "[MOCK] Tim sedang bekerja secara aktif untuk meningkatkan sistem. Progres berjalan sesuai rencana.",
            'risk_level'              => $riskLevel,
            'business_impact'         => "[MOCK] Dampak pada operasional bisnis bersifat " . ($riskLevel === 'high' ? 'signifikan' : 'minimal') . ".",
            'recommended_action'      => "[MOCK] " . ($riskLevel === 'high' ? 'Segera lakukan review dan pengujian.' : 'Pantau perkembangan secara berkala.'),
        ];
    }

    private function getMockProjectReport(array $projectData): array
    {
        return [
            'progress_summary'   => "[MOCK] Proyek {$projectData['name']} sedang berjalan dengan status {$projectData['status']}. Tim terus bekerja sesuai timeline yang ditetapkan.",
            'completed_work'     => [
                '[MOCK] Setup infrastruktur dasar telah selesai',
                '[MOCK] Fitur autentikasi berhasil diimplementasikan',
                '[MOCK] Integrasi GitHub webhook sudah berjalan',
            ],
            'issues_and_risks'   => [
                '[MOCK] Beberapa error log perlu ditangani segera',
                '[MOCK] Review pull request masih dalam proses',
            ],
            'business_impact'    => '[MOCK] Proyek berjalan sesuai target dengan beberapa risiko minor yang perlu diperhatikan.',
            'recommended_actions' => [
                '[MOCK] Lakukan code review untuk pull request yang pending',
                '[MOCK] Prioritaskan penyelesaian error log dengan severity tinggi',
                '[MOCK] Update progress kepada client secara berkala',
            ],
            'overall_risk_level' => 'medium',
        ];
    }
}
