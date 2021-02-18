<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

 /**
     * @Route("/comment/publish", name="publish")
     */
    public function publish(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {

            $form = json_decode($request->getContent(), true);
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['id' => $request->get('user_id')]);

                $post = $this->getDoctrine()
                ->getRepository(Posts::class)
                ->findOneBy(['id' => $request->get('post_id')]);

            $comment = new Comments();

            $comment->setDate(new \DateTime());
            $comment->setContent($request->get('comment_text'));
            $comment->setLikes(0);
            $comment->setDislikes(0);

            $comment->setPost($post);
            $comment->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            $conn = $this->getDoctrine()->getManager()
            ->getConnection();
            $sql = '
                SELECT c.*, u.name, u.profile FROM comments c INNER JOIN users u ON u.id = c.id_user
                WHERE c.id = :id AND c.id_father IS NULL  ORDER BY c.likes DESC LIMIT 5
                ';
                $stmt = $conn->prepare($sql);
                $stmt->execute(array('id' => $comment->getId()));
                $info = $stmt->fetch();

            $data = ['status' => 200, 'message' => "El Comentario ha sido publicado.",
             "comment"=>$info];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }

     /**
     * @Route("/comment/delete", name="comment.delete")
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
                    DELETE FROM comments WHERE id = :id';
                $stmt = $conn->prepare($sql);
                $stmt->execute
                    (array('id' => $form['id']));
               
                    $data = ['status' => 200, 'message' => "Comentario eliminado."];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }
 
    /**
     * @Route("/comment/like", name="like.comment")
     */
    public function like(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $form = json_decode($request->getContent(), true);
            $model = $this->getDoctrine()
                    ->getRepository(Comments::class)
                    ->findOneBy(['id' => $form['id']]);
                $model->setLikes($model->getLikes() + 1);

                $em = $this->getDoctrine()->getManager();
                $em->persist($model);
                $em->flush();
            $data = ['status' => 200, 'message' => "Like al comentario."];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }

    /**
     * @Route("/comment/dislike", name="dislike.comment")
     */
    public function dislike(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $form = json_decode($request->getContent(), true);
            $model = $this->getDoctrine()
                    ->getRepository(Comments::class)
                    ->findOneBy(['id' => $form['id']]);
                $model->setDislikes($model->getDislikes() + 1);

                $em = $this->getDoctrine()->getManager();
                $em->persist($model);
                $em->flush();
            $data = ['status' => 200, 'message' => "Dislike al comentario."];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }

   
}
