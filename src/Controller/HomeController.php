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

        if($request->isXmlHttpRequest()) {
            $data = $this->renderView('index.content.html.twig', [
                'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
            ]);
            $response = new Response("
                try{
                    \$('#central-content').html(decodeURIComponent('" . rawurlencode($data). "'));
                } catch(e) {
                    console.log('e', e);
                }
                ");
            $response->headers->set('Content-Type','text/javascript');
            return $response;
        } else {
            return $this->render('index.html.twig', [
                'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
            ]);
        }
    }

    /**
     * @Route("/top-navbar",  name="top-navbar")
     */
    public function topNavbar(Request $request, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator)
    {
        if($request->isXmlHttpRequest()) {
            $data = $this->renderView('top-navbar.html.twig', [
                'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
            ]);
            $response = new Response("
                try{
                    \$('#top-navbar').html(decodeURIComponent('" . rawurlencode($data). "'));
                } catch(e) {
                    console.log('e', e);
                }
                ");
            $response->headers->set('Content-Type','text/javascript');
            return $response;
        } else {
            return $this->render('top-navbar.html.twig', [
                'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
            ]);
        }
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
        if($request->isXmlHttpRequest()) {
            $data = $this->renderView('homepage.content.html.twig', [
                'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
            ]);
            $response = new Response("
                try{
                    \$('#central-content').html(decodeURIComponent('" . rawurlencode($data). "'));
                } catch(e) {
                    console.log('e', e);
                }
                ");
            $response->headers->set('Content-Type','text/javascript');
            return $response;
        } else {
            return $this->render('homepage.html.twig', [
                'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
            ]);
        }
    }

}