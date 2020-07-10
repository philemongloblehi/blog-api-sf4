<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @Route("/authors/{id}", name="author_show", methods={"GET"})
     * @param Author $author
     * @return Response
     */
    public function showAction(Author $author) {

        $data = $this->get('serializer')->serialize($author, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param Request $request
     * @Route("/authors", name="author_create", methods={"POST"})
     * @return Response
     */
    public function createAction(Request $request) {
        $data = $request->getContent();
        $author = $this->get('serializer')->deserialize($data, Author::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($author);
        $em->flush();

        return new Response('', Response::HTTP_CREATED);
    }
}
