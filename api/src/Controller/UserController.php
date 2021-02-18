<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/info", name="info.profile")
     */
    public function info(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $form = json_decode($request->getContent(), true);
            $conn = $this->getDoctrine()->getManager()
                ->getConnection();
            $sql = '
                SELECT * FROM users WHERE id = :id
                ';
            $stmt = $conn->prepare($sql);
            $stmt->execute
                (array('id' => $form['id']));
            $user = $stmt->fetch();
            if (!$user) {
                $data = ['status' => 500, 'message' => 'El usuario no existe.'];
            } else {

                $sql = '
            SELECT p.*, u.name, u.profile FROM posts p INNER JOIN users u ON u.id = p.id_user WHERE p.id_user = :user ORDER BY p.date DESC
            ';
                $stmt = $conn->prepare($sql);
                $stmt->execute(array('user' => $user['id']));
                $posts = $stmt->fetchAll();

                if ($user['id'] == $form['user']) {
                    $state = 0;
                } else {
                    $sql = '
                    SELECT * FROM user_user WHERE user_source = :user AND user_target = :sub
                    ';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute
                        (array('user' => $user['id'],
                        'sub' => $form['user']));
                    $state = $stmt->fetch();
                    $state = ($state) ? 2 : 1;
                }
                $sql = '
                SELECT * FROM user_user WHERE user_source = :user
                ';
                $stmt = $conn->prepare($sql);
                $stmt->execute
                    (array('user' => $user['id']));
                $subs = $stmt->fetchAll();
                $subs = ($subs) ? count($subs) : 0;


                $sql = '
                SELECT u.*, uu.user_source, uu.user_target FROM user_user uu INNER JOIN users u ON u.id = uu.user_source WHERE uu.user_target = :user
                ';
                $stmt = $conn->prepare($sql);
                $stmt->execute
                    (array('user' => $user['id']));
                    $subscriptions = $stmt->fetchAll();

                  /*  $sql = '
                    SELECT * FROM users WHERE id = :id
                    ';
                $stmt = $conn->prepare($sql);
                $stmt->execute
                    (array('id' => $form['user']));
                $user = $stmt->fetch();*/

                $sql = '
                SELECT * FROM messages
                ';
                $stmt = $conn->prepare($sql);
                $stmt->execute
                    (array());
                    $messages = $stmt->fetchAll();

                $data = ["status" => 200, "user" => $user,
                "subscriptions"=>$subscriptions,"messages"=>$messages,
                    "state" => $state, "subs" => $subs, "posts" => $posts];

            }

        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }

    /**
     * @Route("/user/subscribe", name="subscribe")
     */
    public function subscribe(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $form = json_decode($request->getContent(), true);
            $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneBy(['id' => $form['user']]);

                    $sub = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneBy(['id' => $form['sub']]);

                    $conn = $this->getDoctrine()->getManager()
                    ->getConnection();
                $sql = '
                    INSERT INTO user_user VALUES(:user,:sub) 
                    ';
                $stmt = $conn->prepare($sql);
                $stmt->execute
                    (array('user' => $form['user'],'sub' => $form['sub']));
               
            $data = ['status' => 200, 'message' => "Siguiendo al usuario \"".$user->getName()."\"."];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }

     /**
     * @Route("/user/desubscribe", name="desubscribe")
     */
    public function desubscribe(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $form = json_decode($request->getContent(), true);
            $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneBy(['id' => $form['user']]);

                    $sub = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneBy(['id' => $form['sub']]);

                    $conn = $this->getDoctrine()->getManager()
                    ->getConnection();
                $sql = '
                    DELETE FROM user_user WHERE user_source = :user AND user_target = :sub';
                $stmt = $conn->prepare($sql);
                $stmt->execute
                    (array('user' => $form['user'],'sub' => $form['sub']));
               
                    $data = ['status' => 200, 'message' => "Dejar de seguir al usuario \"".$user->getName()."\"."];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }

    /**
     * @Route("/user/update", name="update.profile")
     */
    public function update(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $profile = $request->files->get('profile');
            $banner = $request->files->get('banner');

            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['id' => $request->get('user_id')]);

            $bio = $request->get('bio');
            if ($bio) {
                $user->setBio($bio);
            }

            $path = $this->getParameter('img_dir');
            if ($profile) {
                $name_profile = md5(uniqid()) . '.' . $profile->guessExtension();
                $profile->move($path, $name_profile);
                $user->setProfile($name_profile);
            }
            if ($banner) {
                $name_banner = md5(uniqid()) . '.' . $banner->guessExtension();
                $banner->move($path, $name_banner);
                $user->setBanner($name_banner);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $data = ['status' => 200, 'message' => "El Usuario \"".$user->getName()."\" ha sido actualizado."];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }

}
