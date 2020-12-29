<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class APIController extends AbstractController
{
    /**
     * @Route("/api/articles", name="apiArticles")
     */
    public function index(EntityManagerInterface $em): JsonResponse
   	{
      $articles = $em->getRepository(Article::class)->find5lastArticles();
      
      $serializedArticles = [];
      foreach ($articles as $article){
        $serializedArticles[] = [
          'id' => $article->getId(),
          'title' => $article->getTitle(),
          'url_alias' => $article->getSlug(),
          'content' => $article->getContent(),
          'publication_date' => $article->getPublished(),
        ];
      }
      return new JsonResponse(['data' => $serializedArticles, 'items' => count($serializedArticles)]);
   	}
}
