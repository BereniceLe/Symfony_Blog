<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
  /**
   * @Route("/", name="index")
   */
   public function index(): Response
   {
     return $this->render('index.html.twig', []);
   }

   /**
    * @Route("/post/{id}", name="view_post")
    */
    public function view_post(int $id): Response
    {
      $articles = [["title" => "Titre de l'article", "description" => "Description de l'article",
      "date" => "5 Novembre 2020", "body" => "Cet article est une présentation d'un article. C'est un petit exemple de test tout pourri mais bon faut bien commencer quelque part. :)","genre" => " Test"],
      ["title" => "Titre de l'article 2", "description" => "Description de l'article",
      "date" => "5 Octorbre 2020", "body" => "Cet article est une présentation d'un article. C'est un petit exemple de test tout pourri mais bon faut bien commencer quelque part. :)","genre" => " Test"],
      ["title" => "Titre de l'article 3", "description" => "Description de l'article",
      "date" => "4 Novembre 2020", "body" => "Cet article est une présentation d'un article. C'est un petit exemple de test tout pourri mais bon faut bien commencer quelque part. :)","genre" => " Test"]];
      return $this->render('post.html.twig',['id' => $id, 'articles' => $articles]);
    }
}

?>
