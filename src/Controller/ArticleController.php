<?php

namespace App\Controller;

use App\Entity\Article;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles/{id}", name="article_show", methods={"GET"})
     * @param Article $article
     * @return Response
     */
    public function showAction(Article $article) {

        $data = $this->get('jms_serializer')->serialize($article, 'json', SerializationContext::create()->setGroups(array('details')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/articles", name="article_create", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request) {
        $data = $request->getContent();
        $article = $this->get('jms_serializer')->deserialize($data, Article::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return new Response('', Response::HTTP_CREATED);
    }

    /**
     * @Route("/articles", name="article_list", methods={"GET"})
     */
    public function listAction() {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $data = $this->get('jms_serializer')->serialize($articles, 'json', SerializationContext::create()->setGroups(array('list')));

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
