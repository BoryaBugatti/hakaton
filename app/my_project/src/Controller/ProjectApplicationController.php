<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use DateTime;

class ProjectApplicationController extends AbstractController
{
    private const YANDEX_GPT_URL = 'https://llm.api.cloud.yandex.net/foundationModels/v1/completion';
    
    public function __construct(
        private Connection $connection,
        private LoggerInterface $logger,
        private HttpClientInterface $httpClient,
        #[Autowire('%env(YANDEX_API_KEY)%')] 
        private string $yandexApiKey,
        #[Autowire('%env(YANDEX_FOLDER_ID)%')]
        private string $yandexFolderId
    ) {}

    #[Route('/api/make-app/{type}', name: 'app_create_application', methods: ['POST'])]
    public function createApplication(Request $request, string $type): JsonResponse
    {
        try {
            $data = $this->validateRequest($request);
            
            $userExists = $this->connection->executeQuery(
                'SELECT 1 FROM "user" WHERE user_id = ?',
                [$data['user_id']]
            )->fetchOne();

            if (!$userExists) {
                throw new \RuntimeException('Пользователь не найден');
            }

            $estimatedDays = null;
            if ($type === 'aiaiai') {
                $estimatedDays = $this->getAiEstimatedTime($data);
            }

            $applicationData = [
                'user_id' => $data['user_id'],
                'application_name' => $data['project_name'],
                'application_description' => $data['project_description'],
                'application_status' => 'new',
                'application_type' => $data['project_type'] ?? null,
                'application_budget' => $data['project_budget'] ?? null,
                'application_time_count' => 0
            ];

            if ($estimatedDays !== null) {
                $endDate = new DateTime();
                $endDate->modify("+{$estimatedDays} days");
                $applicationData['application_time'] = $endDate->format('Y-m-d');
            }

            $this->connection->insert('application', $applicationData);

            return $this->json([
                'status' => 'success',
                'application_id' => $this->connection->lastInsertId(),
                'estimated_days' => $estimatedDays,
                'end_date' => $applicationData['application_time'] ?? null
            ]);

        } catch (\InvalidArgumentException $e) {
            $this->logger->error('Ошибка валидации: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (\RuntimeException $e) {
            $this->logger->error('Ошибка: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            $this->logger->error('Ошибка БД: ' . $e->getMessage());
            return $this->json(['error' => 'Ошибка базы данных'], 500);
        }
    }

    private function getAiEstimatedTime(array $projectData): int
    {
        try {
            $prompt = $this->createAiPrompt($projectData);
            $response = $this->callYandexGptApi($prompt);
            
            $resultText = $response['result']['alternatives'][0]['message']['text'] ?? '';
            $endDateStr = $this->extractEndDateFromAiResponse($resultText);
            
            if ($endDateStr) {
                $endDate = DateTime::createFromFormat('d.m.Y', $endDateStr);
                $today = new DateTime();
                $interval = $today->diff($endDate);
                return (int) $interval->days;
            }
            
            return rand(10, 30);
            
        } catch (\Exception $e) {
            $this->logger->error('Ошибка при запросе к YandexGPT: ' . $e->getMessage());
            return rand(10, 30);
        }
    }

    private function createAiPrompt(array $projectData): string
    {
        return sprintf(
            "Проанализируй проект и оцени сроки выполнения:\nНазвание: %s\nОписание: %s\nТехнологии: %s\n\n" .
            "Укажи примерную дату окончания проекта в формате ДД.ММ.ГГГГ, учитывая сложность проекта.",
            $projectData['project_name'] ?? 'Не указано',
            $projectData['project_description'],
            implode(', ', $projectData['tech_stack'] ?? [])
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
                    'maxTokens' => 1000,
                ],
                'messages' => [
                    [
                        'role' => 'user',
                        'text' => $prompt,
                    ]
                ]
            ],
            'timeout' => 20,
        ]);

        return $response->toArray();
    }

    private function extractEndDateFromAiResponse(string $content): ?string
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

    private function validateRequest(Request $request): array
    {
        $data = json_decode($request->getContent(), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Неверный JSON формат');
        }
        
        $requiredFields = [
            'user_id' => 'Требуется ID пользователя',
            'project_name' => 'Требуется название проекта',
            'project_description' => 'Требуется описание проекта'
        ];
        
        foreach ($requiredFields as $field => $error) {
            if (empty($data[$field])) {
                throw new \InvalidArgumentException($error);
            }
            
            if ($field === 'user_id' && !is_numeric($data[$field])) {
                throw new \InvalidArgumentException('ID пользователя должен быть числом');
            }
        }
        
        return $data;
    }
}