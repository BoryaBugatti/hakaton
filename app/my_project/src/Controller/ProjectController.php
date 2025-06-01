<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;

class ProjectController extends AbstractController
{
    public function __construct(
        private Connection $connection,
        private LoggerInterface $logger
    ) {}

    #[Route('/api/applications', name: 'get_applications', methods: ['GET'])]
    public function getApplications(): JsonResponse
    {
        try {
            $applications = $this->connection->executeQuery(
                "SELECT 
                    a.id as project_id,
                    a.application_name as project_name,
                    a.application_description as project_description,
                    a.application_status,
                    a.application_time as deadline,
                    a.application_budget as budget,
                    a.application_type as app_type,
                    u.user_id
                FROM application a
                JOIN \"user\" u ON a.user_id = u.user_id"
            )->fetchAllAssociative();

            $result = array_map(function ($app) {
                return [
                    'application_id' => $app['project_id'],
                    'application_name' => $app['project_name'],
                    'application_description' => $app['project_description'],
                    'status' => $app['application_status'],
                    'deadline' => $app['deadline'],
                    'user_id' => $app['user_id'],
                    "application_type"=>$app['app_type']
                ];
            }, $applications);

            return $this->json($result);

        } catch (\Exception $e) {
            $this->logger->error('Ошибка при получении заявок: ' . $e->getMessage());
            return $this->json([
                'error' => 'Ошибка при получении данных заявок',
                'details' => $e->getMessage()
            ], 500);
        }
    }

   #[Route('/api/applications/{applicationId}', name: 'update_application', methods: ['POST'])]
    public function updateApplication(Request $request, int $applicationId): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Неверный JSON формат');
            }
            
            $this->connection->beginTransaction();

            try {
                $this->connection->executeStatement(
                    "UPDATE application 
                    SET application_status = :status
                    WHERE id = :id",
                    [
                        'status' => $data['status'],
                        'id' => $applicationId
                    ]
                );

                if ($data['status'] === 'подтвержден') {
                    $application = $this->connection->executeQuery(
                        "SELECT 
                            a.application_name,
                            a.application_description,
                            a.application_type,
                            a.application_time,
                            a.user_id
                        FROM application a
                        WHERE a.id = :id",
                        ['id' => $applicationId]
                    )->fetchAssociative();

                    if (!$application) {
                        throw new \RuntimeException('Заявка не найдена');
                    }

                    $this->connection->executeStatement(
                        "INSERT INTO project (
                            project_name, 
                            project_description, 
                            project_stack,
                            project_time,
                            user_id
                        ) VALUES (
                            :name, 
                            :description, 
                            :stack,
                            :deadline,
                            :user_id
                        )",
                        [
                            'name' => $application['application_name'],
                            'description' => $application['application_description'],
                            'stack' => $application['application_type'],
                            'deadline' => $application['application_time'],
                            'user_id' => $application['user_id']
                        ]
                    );

                    $projectId = $this->connection->lastInsertId();

                    $milestones = [
                        ['name' => 'Серверная часть', 'status' => 'не начато'],
                        ['name' => 'Клиентская часть', 'status' => 'не начато'],
                        ['name' => 'База данных', 'status' => 'не начато'],
                        ['name' => 'Девопс', 'status' => 'не начато'],
                        ['name' => 'Дополнительно', 'status' => 'не начато']
                    ];

                    foreach ($milestones as $milestone) {
                        $this->connection->executeStatement(
                            "INSERT INTO milestone (
                                milestone_name,
                                milestone_status,
                                project_id
                            ) VALUES (
                                :name,
                                :status,
                                :project_id
                            )",
                            [
                                'name' => $milestone['name'],
                                'status' => $milestone['status'],
                                'project_id' => $projectId
                            ]
                        );
                    }
                }

                $this->connection->commit();

                return $this->json([
                    'status' => 'success',
                    'message' => 'Статус заявки обновлен' . 
                                ($data['status'] === 'подтвержден' ? ' и проект с вехами создан' : '')
                ]);

            } catch (\Exception $e) {
                $this->connection->rollBack();
                throw $e;
            }

        } catch (\InvalidArgumentException $e) {
            $this->logger->error('Ошибка валидации: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (\RuntimeException $e) {
            $this->logger->error('Ошибка: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            $this->logger->error('Ошибка обновления заявки: ' . $e->getMessage());
            return $this->json([
                'error' => 'Ошибка обновления заявки',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}