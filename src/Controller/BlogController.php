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
      return $this->render('post.html.twig',['id' => $id]);
    }
}

?>
