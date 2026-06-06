<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class AIResumeService
{
    public function buildResume(array $data): array
    {
        $prompt = $this->buildPrompt($data);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => $this->getSystemPrompt($data['tone'])],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 4000,
        ]);

        $content = $response->choices[0]->message->content;

        return $this->parseResponse($content);
    }

    private function getSystemPrompt(string $tone): string
    {
        $toneDescriptions = [
            'professional' => 'Use a formal, professional tone suitable for corporate healthcare environments. Focus on achievements, metrics, and career progression.',
            'academic' => 'Use an academic, scholarly tone suitable for research and teaching positions. Emphasize publications, research, and academic credentials.',
            'creative' => 'Use a confident, engaging tone that highlights personality while remaining professional. Make the candidate stand out.',
            'concise' => 'Use a brief, impactful tone. Every word should count. Focus on key achievements in bullet points.',
        ];

        $toneGuide = $toneDescriptions[$tone] ?? $toneDescriptions['professional'];

        return <<<SYSTEM
You are an expert healthcare resume writer. Your job is to create beautifully structured, ATS-friendly resumes for healthcare professionals.

Tone: {$toneGuide}

IMPORTANT: Return the resume in a structured JSON format with these exact keys:
{
  "name": "Full name",
  "title": "Professional title/headline",
  "summary": "2-3 sentence professional summary",
  "contact": {
    "email": "",
    "phone": "",
    "location": ""
  },
  "experience": [
    {
      "title": "Job title",
      "company": "Organization name",
      "period": "Duration",
      "highlights": ["Achievement 1", "Achievement 2"]
    }
  ],
  "education": [
    {
      "degree": "Degree name",
      "institution": "Institution name",
      "year": "Year or period"
    }
  ],
  "skills": ["Skill 1", "Skill 2"],
  "certifications": ["Certification 1"],
  "languages": ["Language 1"]
}

Rules:
- Improve and enhance the content provided — make it more impactful
- Use action verbs and quantify achievements where possible
- Keep healthcare/medical terminology accurate
- If information is missing, leave that section as an empty array
- Return ONLY valid JSON, no markdown formatting or code blocks
SYSTEM;
    }

    private function buildPrompt(array $data): string
    {
        $source = $data['source']; // 'file' or 'manual'
        $content = $data['content']; // extracted text or typed content
        $includeImage = $data['include_image'] ?? false;

        $prompt = "Please create an improved, professional resume from the following information:\n\n";

        if ($source === 'file') {
            $prompt .= "EXISTING RESUME CONTENT:\n{$content}\n\n";
            $prompt .= "Please improve this resume — enhance the language, restructure for better readability, and make achievements more impactful.";
        } else {
            $prompt .= "RAW INFORMATION PROVIDED BY CANDIDATE:\n{$content}\n\n";
            $prompt .= "Please create a complete, professional resume from this information. Fill in professional summaries and improve the language.";
        }

        if (!empty($data['profile_data'])) {
            $prompt .= "\n\nADDITIONAL PROFILE DATA:\n" . json_encode($data['profile_data'], JSON_PRETTY_PRINT);
        }

        return $prompt;
    }

    private function parseResponse(string $content): array
    {
        // Remove potential markdown code blocks
        $content = preg_replace('/^```(?:json)?\s*/', '', $content);
        $content = preg_replace('/\s*```$/', '', $content);
        $content = trim($content);

        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Try to extract JSON from the response
            if (preg_match('/\{[\s\S]*\}/', $content, $matches)) {
                $decoded = json_decode($matches[0], true);
            }
        }

        if (!$decoded) {
            throw new \RuntimeException('Failed to parse AI response into structured resume data.');
        }

        return $decoded;
    }

    public function extractTextFromPdf(string $filePath): string
    {
        // Use a simple text extraction approach for PDFs
        $content = file_get_contents($filePath);

        // Basic PDF text extraction
        $text = '';

        // Try to extract text between stream/endstream
        if (preg_match_all('/stream\s*\n(.*?)\nendstream/s', $content, $matches)) {
            foreach ($matches[1] as $match) {
                $decoded = @gzuncompress($match);
                if ($decoded) {
                    // Extract readable text
                    if (preg_match_all('/\((.*?)\)/', $decoded, $textMatches)) {
                        $text .= implode(' ', $textMatches[1]) . "\n";
                    }
                    if (preg_match_all('/\[(.*?)\]TJ/', $decoded, $tjMatches)) {
                        foreach ($tjMatches[1] as $tj) {
                            if (preg_match_all('/\((.*?)\)/', $tj, $innerMatches)) {
                                $text .= implode('', $innerMatches[1]);
                            }
                        }
                        $text .= "\n";
                    }
                }
            }
        }

        // Fallback: extract any readable strings from PDF
        if (empty(trim($text))) {
            $text = $this->extractRawText($content);
        }

        return trim($text) ?: 'Unable to extract text from PDF. Please use the manual input option.';
    }

    private function extractRawText(string $content): string
    {
        // Extract text objects from PDF
        $text = '';
        $lines = [];

        // Match text between BT and ET (text objects)
        if (preg_match_all('/BT\s*(.*?)\s*ET/s', $content, $matches)) {
            foreach ($matches[1] as $textBlock) {
                if (preg_match_all('/\((.*?)\)/', $textBlock, $stringMatches)) {
                    $lines[] = implode('', $stringMatches[1]);
                }
            }
        }

        return implode("\n", array_filter($lines));
    }
}
