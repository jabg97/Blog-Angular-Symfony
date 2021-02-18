<?php

namespace App\Controller;

use App\Entity\Messages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
  
    /**
     * @Route("/message/send", name="send")
     */
    public function send(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $form = json_decode($request->getContent(), true);
            $msg = new Messages();
            $msg->setEmail($form['email']);
            $msg->setContent($form['content']);
            $msg->setName($form['name']);
            $msg->setDate(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($msg);
            $em->flush();
            $data = ['status' => 200, 'message' => "El Mensaje de \"" . $msg->getName() . "\" ha sido enviado."];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }
 /**
     * @Route("/message/delete", name="delete.message")
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
            DELETE FROM messages WHERE id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute
            (array('id' => $form['id']));
       
            $data = ['status' => 200, 'message' => "Mensaje eliminado."];
        } catch (Throwable $e) {
            $data = ['status' => 500, 'message' => $e->getMessage() . "."];
        }
        $response->setContent(json_encode($data));
        return $response;
    }
}
