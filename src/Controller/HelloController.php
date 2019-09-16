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

        $foo2 = new ComplexObject('toto', $_ENV['APP_ENV']);

        $content = $twig->render('index.html.twig',
            [
                'name' => $name
            . ' (' . $foo1->getFoo() . ')'
            . ' {' . $foo1->getEnv() . '}'
            . ' (' . $foo2->getFoo() . ')'
            . ' {' . $foo2->getEnv() . '}'
            . ' [' .__METHOD__ . ']'
            ]
        );

        $response = new Response($content);

        /*$response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/html');*/

        return $response;
    }

    // without annotation, see config/routes.yaml
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