<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post/index", name="index")
     */
    public function index(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $form = json_decode($request->getContent(), true);
            $conn = $this->getDoctrine()->getManager()
                ->getConnection();

            $sql = '
            SELECT p.*, u.name, u.profile FROM posts p INNER JOIN users u ON u.id = p.id_user ORDER BY p.date DESC
            ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array());
            $posts = $stmt->fetchAll();

            $data = ["status" => 200, "posts" => $posts];

        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }

    /**
     * @Route("/post/query", name="query")
     */
    public function query(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $form = json_decode($request->getContent(), true);
            $conn = $this->getDoctrine()->getManager()
                ->getConnection();

            $sql = "
            SELECT p.*, u.name, u.profile FROM posts p INNER JOIN users u ON u.id = p.id_user 
            WHERE lower(p.title) like :query ORDER BY p.date DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute(array('query'=>'%'.strtolower($form['query']).'%' ));
            $posts = $stmt->fetchAll();

            $data = ["status" => 200, "posts" => $posts];

        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }

  /**
     * @Route("/post/delete", name="post.delete")
     */
    public function delete(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $form = json_decode($request->getContent(), true);

                    $conn = $this->getDoctrine()->getManager()
                    ->getConnection();
                $sql = '
                    DELETE FROM comments WHERE id_post = :id';
                $stmt = $conn->prepare($sql);
                $stmt->execute
                    (array('id' => $form['id']));

                    $sql = '
                    DELETE FROM posts WHERE id = :id';
                $stmt = $conn->prepare($sql);
                $stmt->execute
                    (array('id' => $form['id']));
               
                    $data = ['status' => 200, 'message' => "Entrada eliminada."];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }

    /**
     * @Route("/post/like", name="like.post")
     */
    public function like(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $form = json_decode($request->getContent(), true);
            $model = $this->getDoctrine()
                    ->getRepository(Posts::class)
                    ->findOneBy(['id' => $form['id']]);
                $model->setLikes($model->getLikes() + 1);

                $em = $this->getDoctrine()->getManager();
                $em->persist($model);
                $em->flush();
            $data = ['status' => 200, 'message' => "Like al post."];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }

    /**
     * @Route("/post/dislike", name="dislike.post")
     */
    public function dislike(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $form = json_decode($request->getContent(), true);
            $model = $this->getDoctrine()
                    ->getRepository(Posts::class)
                    ->findOneBy(['id' => $form['id']]);
                $model->setDislikes($model->getDislikes() + 1);

                $em = $this->getDoctrine()->getManager();
                $em->persist($model);
                $em->flush();
            $data = ['status' => 200, 'message' => "Dislike al post."];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }

    /**
     * @Route("/post/read", name="read")
     */
    public function read(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $form = json_decode($request->getContent(), true);
            $conn = $this->getDoctrine()->getManager()
                ->getConnection();
            $sql = '
            SELECT p.*, u.name, u.profile FROM posts p INNER JOIN users u ON u.id = p.id_user
            WHERE p.id = :id
                ';
            $stmt = $conn->prepare($sql);
            $stmt->execute
                (array('id' => $form['id']));
            $post = $stmt->fetch();
            if (!$post) {
                $data = ['status' => 500, 'message' => 'El post no existe.'];
            } else {

                if ($post['id_user'] == $form['user']) {
                    $state = 0;
                } else {
                    $sql = '
                    SELECT * FROM user_user WHERE user_source = :user AND user_target = :sub
                    ';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute
                        (array('user' => $post['id_user'],
                        'sub' => $form['user']));
                    $state = $stmt->fetch();
                    $state = ($state) ? 2 : 1;
                }
                $sql = '
                SELECT * FROM user_user WHERE user_source = :user
                ';
                $stmt = $conn->prepare($sql);
                $stmt->execute
                    (array('user' => $post['id_user']));
                $subs = $stmt->fetchAll();
                $subs = ($subs) ? count($subs) : 0;

                $sql = '
                SELECT p.*, u.name, u.profile FROM posts p INNER JOIN users u ON u.id = p.id_user
                WHERE p.id_user = :user AND  p.id <> :post ORDER BY p.date DESC
                ';
                $stmt = $conn->prepare($sql);
                $stmt->execute(array('user' => $post['id_user'], 'post' => $post['id']));
                $later = $stmt->fetchAll();

                $sql = '
                SELECT p.*, u.name, u.profile FROM posts p INNER JOIN users u ON u.id = p.id_user
                WHERE p.id_user <> :user  ORDER BY p.date DESC LIMIT 5
                ';
                $stmt = $conn->prepare($sql);
                $stmt->execute(array('user' => $post['id_user']));
                $posts = $stmt->fetchAll();

                $sql = '
            SELECT * FROM users WHERE id = :id
                ';
                $stmt = $conn->prepare($sql);
                $stmt->execute
                    (array('id' => $post['id_user']));
                $user = $stmt->fetch();

                $sql = '
                SELECT c.*, u.name, u.profile FROM comments c INNER JOIN users u ON u.id = c.id_user
                WHERE c.id_post = :post AND c.id_father IS NULL  ORDER BY c.likes DESC LIMIT 5
                ';
                $stmt = $conn->prepare($sql);
                $stmt->execute(array('post' => $post['id']));
                $comments = $stmt->fetchAll();

                $model = $this->getDoctrine()
                    ->getRepository(Posts::class)
                    ->findOneBy(['id' => $post['id']]);
                $model->setViews($model->getViews() + 1);

                $em = $this->getDoctrine()->getManager();
                $em->persist($model);
                $em->flush();

                $post['views']++;

                $sql = '
                SELECT * FROM users WHERE id = :id
                ';
            $stmt = $conn->prepare($sql);
            $stmt->execute
                (array('id' => $form['user']));
            $user = $stmt->fetch();

                $data = ["status" => 200, "post" => $post, "posts" => $posts, "user" => $user,
                    "comments" => $comments, 'comments_count' => count($comments),
                    "state" => $state, "subs" => $subs, "later" => $later];

            }

        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }

    /**
     * @Route("/post/upload", name="upload")
     */
    public function upload(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {

            $img = $request->files->get('img');

            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['id' => $request->get('user_id')]);

            $post = new Posts();

            $title = $request->get('title');
            if ($title) {
                $post->setTitle($title);
            }

            $subtitle = $request->get('subtitle');
            if ($subtitle) {
                $post->setSubtitle($subtitle);
            }

            $content = $request->get('content');
            if ($content) {
                $post->setContent($content);
            }

            $post->setDate(new \DateTime());
            $post->setLikes(0);
            $post->setDislikes(0);
            $post->setViews(0);

            $post->setUser($user);

            $path = $this->getParameter('img_dir');
            if ($img) {
                $name_img = md5(uniqid()) . '.' . $img->guessExtension();
                $img->move($path, $name_img);
                $post->setImg($name_img);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $data = ['status' => 200, 'message' => "El Post \"" . $post->getTitle() . "\" ha sido subido.",
                'post' => $post->getId()];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }
    /**
     * @Route("/post/update", name="update.post")
     */
    public function update(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {

            $img = $request->files->get('img');

            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['id' => $request->get('user_id')]);

            $post = $this->getDoctrine()
                ->getRepository(Posts::class)
                ->findOneBy(['id' => $request->get('id')]);

            $title = $request->get('title');
            if ($title) {
                $post->setTitle($title);
            }

            $subtitle = $request->get('subtitle');
            if ($subtitle) {
                $post->setSubtitle($subtitle);
            }

            $content = $request->get('content');
            if ($content) {
                $post->setContent($content);
            }

            $post->setDate(new \DateTime());

            $post->setUser($user);

            $path = $this->getParameter('img_dir');
            if ($img) {
                $name_img = md5(uniqid()) . '.' . $img->guessExtension();
                $img->move($path, $name_img);
                $post->setImg($name_img);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $data = ['status' => 200, 'message' => "El Post \"" . $post->getTitle() . "\" ha sido actualizado.",
                'post' => $post->getId()];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }
}
