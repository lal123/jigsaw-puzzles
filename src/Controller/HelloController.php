<?php

// src/Controller/HelloController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ComplexObject;
use Twig\Environment;

class HelloController extends AbstractController
{
    /**
     * Page d'accueil
     *
     * @Route("/", name="accueil")
     */
    public function home(ComplexObject $foo1, Environment $twig)
    {

        $request = Request::createFromGlobals();
       
        $name = $request->get('name');

        $foo2 = new ComplexObject('toto');

        $content = $twig->render('Advert/index.html.twig', ['name' => $name
            . ' (' . $foo1->getFoo() . ')'
            . ' (' . $foo2->getFoo() . ')'
            . ' [' .__METHOD__ . ']']);

        return new Response($content);
    }

    /**
     * Page d'accès à un article
     *
     * @Route("/article/{postId<\d+>}", name="article")
     */
    public function showBlogPost($postId = 1)
    {

        $request = Request::createFromGlobals();

        $url = $request->getPathInfo();
        
        $response = new Response();

        $response->setContent('<html><body>Article '
            . $postId
            . ' (' . $url . ')'
            .'</body></html>'
        );
       
        return $response;
    }
}