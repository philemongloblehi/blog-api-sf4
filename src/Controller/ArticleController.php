<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ArticleController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/articles/{id}",
     *     name = "app_article_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View()
     * @param Article $article
     * @return Article
     */
    public function showAction(Article $article) {
       return $article;
   }

    /**
     * @Rest\Post(
     *     path = "/articles",
     *     name = "app_article_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("article", converter="fos_rest.request_body")
     * @param Request $request
     * @return View
     */
   public function createAction(Request $request) {
       $data = $this->get('jms_serializer')->deserialize($request->getContent(), 'array', 'json');
       $article = new Article();
       $form = $this->get('form.factory')->create(ArticleType::class, $article);
       $form->submit($data);

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return $this->view($article, Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('app_article_show',
                    [
                        'id' => $article->getId(), UrlGeneratorInterface::ABSOLUTE_URL
                    ])
            ]);
    }

    /**
     * @Rest\Get("/articles", name="app_article_list")
     */
    public function listAction() {
        return $this->getDoctrine()->getRepository(Article::class)->findAll();
    }
}
