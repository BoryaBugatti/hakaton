<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class RegistrationController extends AbstractController
{
    #[Route('/api/registration', name: 'app_registration', methods:['POST'])]
    public function index(EntityManagerInterface $entitymanager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user_name = htmlspecialchars(trim($data['user_name']));
        $user_email = htmlspecialchars(trim($data['user_email']));
        $user_password = htmlspecialchars(trim($data['user_password']));
        $user_role = 'Пользователь';
        $user_avatar = "https://cdn-icons-png.flaticon.com/512/3607/3607444.png";
        if (empty($user_name) || empty($user_email) || empty($user_password) || empty($user_avatar))
            return $this->json('Поля не могут быть пустыми');
        $user = new User();
        $user->setUserName($user_name);
        $user->setUserEmail($user_email);
        $user->setUserPassword(password_hash($user_password, PASSWORD_DEFAULT));
        $user->setUserRole($user_role);
        $user->setUserAvatar($user_avatar);
        $entitymanager->persist($user);
        $entitymanager->flush();
        return $this->json(['status'=>'access']);
    }
}