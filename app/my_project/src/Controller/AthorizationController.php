<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

final class AthorizationController extends AbstractController
{
    #[Route('/athorization', name: 'app_athorization', methods:['POST'])]
    public function index(Request $request, EntityManagerInterface $entity_manager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user_email = htmlspecialchars(trim($data['user_email']));
        $user_password = htmlspecialchars(trim($data['user_password']));
        $user = $entity_manager->getRepository(Users::class)->findOneBy(['user_email'=>$user_email]);
        if ($user && password_verify($user_password, $user->getUserPassword()))
            return $this->json(['status'=>'OK']);
        if (!$user)
            return $this->json('Пользователь с такими данными не найден');
        return $this->json(['status'=>'OK']);
    }
}
