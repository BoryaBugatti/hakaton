<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetMileStonesController extends AbstractController
{
    #[Route('/api/milestones/{projectId}', name: 'api_milestones_get', methods: ['GET'])]
    public function getMilestones(int $projectId, Connection $connection): JsonResponse
    {
        try {
            $sql = "
                SELECT 
                    milestone_name AS name,
                    milestone_update_date AS updateDate,
                    milestone_status AS status,
                    project_id
                FROM milestone
                WHERE project_id = :projectId
                ORDER BY milestone_update_date ASC
            ";

            $stmt = $connection->prepare($sql);
            $stmt->bindValue('projectId', $projectId);
            $result = $stmt->executeQuery();

            $milestones = $result->fetchAllAssociative();

            $responseData = [];
            foreach ($milestones as $milestone) {
                $responseData[] = [
                    'name' => $milestone['name'] ?? null,
                    'updateDate' => isset($milestone['updateDate']) ? 
                        (is_string($milestone['updateDate']) ? 
                            $milestone['updateDate'] : 
                            ($milestone['updateDate'] instanceof \DateTimeInterface ? 
                                $milestone['updateDate']->format('Y-m-d H:i:s') : 
                                null)) : 
                        null,
                    'status' => $milestone['status'] ?? null,
                    'projectId' => $milestone['project_id'] ?? null
                ];
            }

            return $this->json($responseData);

        } catch (\Exception $e) {
            return $this->json(
                ['error' => 'Ошибка при получении вех проекта: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}