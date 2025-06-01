<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use DateTime;

class AiProcessingController extends AbstractController
{
    private const YANDEX_GPT_URL = 'https://llm.api.cloud.yandex.net/foundationModels/v1/completion';
    
    public function __construct(
        private HttpClientInterface $httpClient,
        #[Autowire('%env(YANDEX_API_KEY)%')] 
        private string $yandexApiKey,
        #[Autowire('%env(YANDEX_FOLDER_ID)%')]
        private string $yandexFolderId,
        private LoggerInterface $logger
    ) {}

    #[Route('/api/ai/process-project', name: 'ai_process_project', methods: ['POST'])]
    public function processProject(Request $request): JsonResponse
    {
        try {
            $data = $this->validateRequest($request);
            $prompt = $this->createPrompt($data);
            $response = $this->callYandexGptApi($prompt);
            
            return $this->handleSuccessResponse($response, $data['start_date'] ?? null);
            
        } catch (\InvalidArgumentException $e) {
            $this->logger->error('Validation error: '.$e->getMessage());
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
            
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('API connection error: '.$e->getMessage());
            return $this->json([
                'status' => 'error',
                'message' => 'Ошибка соединения с YandexGPT',
                'error' => $e->getMessage()
            ], 503);
            
        } catch (\Exception $e) {
            $this->logger->error('YandexGPT processing error: '.$e->getMessage());
            return $this->json([
                'status' => 'error',
                'message' => 'Ошибка обработки запроса',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function validateRequest(Request $request): array
    {
        $data = json_decode($request->getContent(), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Неверный JSON формат');
        }
        
        if (empty($data['project_description'])) {
            throw new \InvalidArgumentException('Описание проекта обязательно');
        }
        
        if (!empty($data['start_date'])) {
            try {
                new DateTime($data['start_date']);
            } catch (\Exception $e) {
                throw new \InvalidArgumentException('Неверный формат даты старта проекта. Используйте YYYY-MM-DD');
            }
        }
        
        return $data;
    }

    private function createPrompt(array $projectData): string
    {
        $startDateInfo = '';
        if (!empty($projectData['start_date'])) {
            $startDate = new DateTime($projectData['start_date']);
            $startDateInfo = "\nДата старта: " . $startDate->format('d.m.Y');
        }

        return sprintf(
            "Проанализируй проект:\nНазвание: %s\nОписание: %s\nТехнологии: %s%s\n\n" .
            "Дай развернутые рекомендации по следующим пунктам:\n" .
            "1. Архитектура - предложи оптимальную архитектуру для проекта\n" .
            "2. Потенциальные проблемы - укажи возможные сложности и риски\n" .
            "3. Оптимизация - предложи пути оптимизации производительности\n" .
            "4. Сроки реализации - оцени сроки выполнения проекта\n\n" .
            "В конце ответа укажи конкретную примерную дату окончания проекта в формате ДД.ММ.ГГГГ, " .
            "отталкиваясь от даты старта %s и учитывая сложность проекта.",
            $projectData['project_name'] ?? 'Не указано',
            $projectData['project_description'],
            implode(', ', $projectData['tech_stack'] ?? []),
            $startDateInfo,
            !empty($projectData['start_date']) ? '(старт проекта ' . (new DateTime($projectData['start_date']))->format('d.m.Y') . ')' : ''
        );
    }

    private function callYandexGptApi(string $prompt): array
    {
        $response = $this->httpClient->request('POST', self::YANDEX_GPT_URL, [
            'headers' => [
                'Authorization' => 'Api-Key '.$this->yandexApiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'modelUri' => "gpt://{$this->yandexFolderId}/yandexgpt-lite",
                'completionOptions' => [
                    'temperature' => 0.7,
                    'maxTokens' => 2500,
                ],
                'messages' => [
                    [
                        'role' => 'user',
                        'text' => $prompt,
                    ]
                ]
            ],
            'timeout' => 35,
        ]);

        return $response->toArray();
    }

    private function handleSuccessResponse(array $apiResponse, ?string $startDate): JsonResponse
    {
        $resultText = $apiResponse['result']['alternatives'][0]['message']['text'] 
            ?? 'Не удалось получить ответ от YandexGPT';
        
        $recommendations = $this->extractRecommendations($resultText);
        $estimatedEndDate = $this->extractEndDate($resultText);

        return $this->json([
            'status' => 'success',
            'result' => [
                'analysis' => $resultText,
                'recommendations' => $recommendations,
                'timeline' => [
                    'start_date' => $startDate,
                    'estimated_end_date' => $estimatedEndDate
                ]
            ]
        ]);
    }

    private function extractEndDate(string $content): ?string
    {
        $patterns = [
            '/дата окончания:\s*(\d{2}\.\d{2}\.\d{4})/i',
            '/окончания проекта:\s*(\d{2}\.\d{2}\.\d{4})/i',
            '/примерная дата окончания:\s*(\d{2}\.\d{2}\.\d{4})/i',
            '/окончания проекта.*?(\d{2}\.\d{2}\.\d{4})/i',
            '/\b(\d{2}\.\d{2}\.\d{4})\b(?!.*\b\d{2}\.\d{2}\.\d{4}\b)/'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $matches) && isset($matches[1])) {
                try {
                    $date = DateTime::createFromFormat('d.m.Y', $matches[1]);
                    if ($date !== false) {
                        return $matches[1];
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        
        return null;
    }

    private function extractRecommendations(string $content): array
    {
        return [
            'architecture' => $this->extractSection('1\.\s*Архитектура|Архитектура:', $content),
            'issues' => $this->extractSection('2\.\s*Потенциальные проблемы|Проблемы:', $content),
            'optimization' => $this->extractSection('3\.\s*Оптимизация|Оптимизация:', $content),
            'timing' => $this->extractSection('4\.\s*Сроки реализации|Сроки:', $content)
        ];
    }

    private function extractSection(string $sectionPattern, string $text): string
    {
        if (preg_match("/{$sectionPattern}[\s\:]*([\s\S]*?)(?=\n###|\n\d+\.|\n\*|\n\-|\n$)/iu", $text, $matches) && isset($matches[1])) {
            $result = trim(preg_replace('/\n{2,}/', "\n", $matches[1]));
            return $result ?: 'Информация не указана';
        }
        
        return 'Не удалось извлечь данные';
    }
}