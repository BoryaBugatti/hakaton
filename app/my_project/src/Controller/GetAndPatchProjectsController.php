<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\DBAL\Connection;

class GetAndPatchProjectsController extends AbstractController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    #[Route('/api/projects', name: 'get_projects', methods: ['GET'])]
    public function getProjects(): JsonResponse
    {
        try {
            $projectsQuery = $this->connection->executeQuery('SELECT * FROM project');
            $projects = $projectsQuery->fetchAllAssociative();

            $result = [];
            
            foreach ($projects as $project) {
                $milestonesQuery = $this->connection->executeQuery(
                    'SELECT * FROM milestone WHERE project_id = ?',
                    [$project['project_id']]
                );
                $milestones = $milestonesQuery->fetchAllAssociative();

                $result[] = [
                    'project_id' => $project['project_id'],
                    'project_name' => $project['project_name'],
                    'milestone' => array_map(function($milestone) {
                        return [
                            'project_id' => $milestone['project_id'],
                            'milestone_name' => $milestone['milestone_name'],
                            'milestone_status' => $milestone['milestone_status']
                        ];
                    }, $milestones)
                ];
            }

            return $this->json($result);

        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'Failed to fetch projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/api/projects/{projectId}', name: 'save_project', methods: ['POST'])]
    public function saveProject(int $projectId, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Invalid JSON data');
            }

            $projectExists = $this->connection->executeQuery(
                'SELECT 1 FROM projects WHERE project_id = ?',
                [$projectId]
            )->fetchOne();

            if (!$projectExists) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Project not found'
                ], 404);
            }

            foreach ($data as $milestoneData) {
                $this->connection->executeStatement(
                    'UPDATE milestones 
                    SET milestone_status = :status 
                    WHERE project_id = :projectId AND milestone_name = :name',
                    [
                        'status' => $milestoneData['milestone_status'],
                        'projectId' => $projectId,
                        'name' => $milestoneData['milestone_name']
                    ]
                );
            }

            return $this->json([
                'status' => 'success',
                'message' => 'Project milestones updated successfully',
                'project_id' => $projectId
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'Failed to update project milestones',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}