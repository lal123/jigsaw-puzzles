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
     * Index
     *
     * @Route("/",  name="index")
     */
    public function index(Request $request, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator, ComplexObject $foo1, Environment $twig)
    {

        $template = $request->isXmlHttpRequest() ? 'index.content.html.twig' : 'index.html.twig';

        return $this->render($template, array(
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
        ));
    }

    /**
     * @Route("/top-navbar",  name="top-navbar")
     */
    public function topNavbar(Request $request, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator)
    {
        return $this->render('top-navbar.html.twig', array(
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
        ));
    }

    /**
     * Homepage
     *
     * @Route({
     *      "en": "/home",
     *      "fr": "/accueil"
     * },  name="homepage")
     */
    public function home(Request $request, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator, ComplexObject $foo1, Environment $twig)
    {
        $template = $request->isXmlHttpRequest() ? 'homepage.content.html.twig' : 'homepage.html.twig';

        return $this->render($template, array(
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
        ));
    }

}