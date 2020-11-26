<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
	* @Route("/post/{slug}", name="view_post")
	*/
	public function view_post(string $slug): Response
	{
		$repository = $this->getDoctrine()->getRepository(Article::class);
		$article = $repository->findArticleByUrlAlias($slug);
		return $this->render('post.html.twig',['article' => $article]);
	}

	/**
	* @Route("/adduser", name="addUser")
	*/
	public function addUserForm(Request $request, UserPasswordEncoderInterface $encoder) : Response
	{
		
		$user = new User();
		$form = $this->createFormBuilder($user)
						->add('email', TextType::class)
						->add('password', PasswordType::class)
						->add('create', SubmitType::class, ['label'=>'S\'inscrire'])
						->getForm();
		$form->handleRequest($request);

		
		if($form->isSubmitted() && $form->isValid()){
				$entityManager = $this->getDoctrine()->getManager();
				$hash = $encoder->encodePassword($user,$user->getPassword());
				$user->setRoles($user->getRoles());
        $user->setPassword($hash);

       	$entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('homepage');       
		}
        

		return $this->render('form.html.twig',['form' => $form->createView()]);
	}
}

?>
