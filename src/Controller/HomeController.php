<?php

// src/Controller/HelloController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Services\ComplexObject;
use App\Services\UrlTranslator;
use Twig\Environment;

class HomeController extends AbstractController
{
    /**
     * Homepage
     *
     * @Route("/",  name="homepage")
     * @Route({
     *      "en": "/home",
     *      "fr": "/accueil"
     * },  name="locale_homepage")
     */
    public function home(Request $request, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator, ComplexObject $foo1, Environment $twig)
    {
        //return $this->redirectToRoute('your_puzzles_list');

        $template = $request->isXmlHttpRequest() ? 'index.content.html.twig' : 'index.html.twig';

        return $this->render($template, array(
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
        ));

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