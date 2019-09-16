<?php
// src/Controller/FormController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ArticleType;
use App\Entity\Article;

class FormController extends AbstractController
{
    /**
     * @Route("/form/new")
     */
    public function new(Request $request)
    {
        $article = new Article();
        $article->setTitle('Hello World');
        $article->setContent('Un très court article.');
        $article->setAuthor('Zozor');

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($article);
            $em->flush();
        }

        $repository = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repository->findAll();

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
            'articles' => $articles,
        ));
    }
}