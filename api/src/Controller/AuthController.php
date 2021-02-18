<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{
    /**
     * @Route("/auth/login", name="login")
     */
    public function login(UserPasswordEncoderInterface $encoder, Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $form = json_decode($request->getContent(), true);
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['email' => $form['email']]);
            if (!$user) {
                $data = ['status' => 500, 'message' => 'El em@il "' . $form['email'] . '" no existe.'];
            } else {
                if ($encoder->isPasswordValid($user, $form['password'])) {
                    $data = ['status' => 200, 'message' => 'Bienvenido ' . $user->getName() . ".", 'user' => $user->getId()];
                } else {
                    $data = ['status' => 500, 'message' => 'Contraseña incorrecta.'];
                }
            }

        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }

    /**
     * @Route("/auth/register", name="register")
     */
    public function register(UserPasswordEncoderInterface $encoder, Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $form = json_decode($request->getContent(), true);
            $user = new User();
            $password = $encoder->encodePassword($user, $form['password']);
            $user->setName($form['name']);
            $user->setEmail($form['email']);
            $user->setPassword($password);
            $user->setRoles(["ROLE_USER"]);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $data = ['status' => 200, 'message' => 'Bienvenido ' . $user->getName() . ".", 'user' => $user->getId()];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }
}
