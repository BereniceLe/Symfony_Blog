<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
  /**
   * @Route("/", name="homepage")
   */
   public function indexAction(): Response
   {
      $repository = $this->getDoctrine()->getRepository(Article::class);
      $articles = $repository->find10lastArticles();
     return $this->render('index.html.twig', ['articles' => $articles]);
   }

   /**
    * @Route("/post/{url}", name="view_post")
    */
    public function view_post(string $url): Response
    {
     $repository = $this->getDoctrine()->getRepository(Article::class);
     $article = $repository->findArticleByUrlAlias($url);
      return $this->render('post.html.twig',['article' => $article]);
    }
}

?>
