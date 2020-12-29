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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\DateTime;

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
	public function addUser(Request $request, UserPasswordEncoderInterface $encoder) : Response
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

	/**
	* @Route("/addarticle", name="addArticle")
	*/
	public function addArticle(Request $request, UserPasswordEncoderInterface $encoder) : Response
	{
		// On vérifie l'autorisation
		$this->denyAccessUnlessGranted('ROLE_USER');

		$article = new Article();
		$form = $this->createFormBuilder($article)
						->add('title', TextType::class)
						->add('content', TextareaType::class)
						->add('create', SubmitType::class, ['label'=>'Ajouter'])
						->getForm();
		$form->handleRequest($request);

		
		if($form->isSubmitted() && $form->isValid()){
				$entityManager = $this->getDoctrine()->getManager();
				$mydate = getdate(date("U"));
				$date = "$mydate[year]-$mydate[mon]-$mydate[mday]";
				$article->setPublished($date);
				$article->setSlug($article->getTitle()); // A CHANGER TODO
				

		       	$entityManager->persist($article);
		        $entityManager->flush();
		        return $this->redirectToRoute('homepage');       
		}
        

		return $this->render('newArticles.html.twig',['form' => $form->createView()]);
	}


	/**
	* @Route("/remove/{slug}", name="deleteArticle")
	*/
	public function deleteArticle(string $slug): Response
	{
		// On vérifie l'autorisation
		$this->denyAccessUnlessGranted('ROLE_ADMIN');

		$repository = $this->getDoctrine()->getRepository(Article::class);
		$article = $repository->findArticleByUrlAlias($slug);
		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->remove($article);
		$entityManager->flush();
		return $this->redirectToRoute('homepage'); 
	}

	/**
	* @Route("/update/{slug}", name="updateArticle")
	*/
	public function updateArticle(Request $request, string $slug): Response
	{
		// On vérifie l'autorisation
		$this->denyAccessUnlessGranted('ROLE_ADMIN');

		$repository = $this->getDoctrine()->getRepository(Article::class);
		$article = $repository->findArticleByUrlAlias($slug);
		$entityManager = $this->getDoctrine()->getManager();

		// On crée le formulaire 
		$articleNew = new Article();
		$form = $this->createFormBuilder($articleNew)
						->add('title', TextType::class, ['data' => $article->getTitle()])
						->add('content', TextareaType::class, ['data' => $article->getContent()])
						->add('create', SubmitType::class, ['label'=>'Modifier'])
						->getForm();
		$form->handleRequest($request);
		// Le formulaire est envoyé 

		if($form->isSubmitted() && $form->isValid()){
			$article->setTitle($articleNew->getTitle());
			$article->setContent($articleNew->getContent());
			$entityManager->flush();
		    return $this->redirectToRoute('view_post', array('slug' => $slug));       
		}
		

	
		return $this->render('newArticles.html.twig',['form' => $form->createView()]);
	}
}

?>
