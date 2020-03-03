<?php
// src/Controller/PuzzlesController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\FormError;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Form\PuzzleCreateType;
use App\Form\PuzzleEditType;
use App\Entity\Puzzle;
use App\Services\UrlTranslator;

class PuzzlesController extends AbstractController
{
    /**
     * @Route({
     *      "en": "/your-puzzles/{page<\d+>}",
     *      "fr": "/vos-puzzles/{page<\d+>}"
     * }, name="your_puzzles_list")
     */
    public function list(Request $request, int $page = 1, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator)
    {
        $locale = $request->getLocale();

        $session = $request->getSession();

        $session->set('listUrl', $request->getRequestUri());

        $query = $request->get('query');
        
        $repository = $this->getDoctrine()->getRepository(Puzzle::class);

        $limit = 10;
        $first = ($page - 1) * $limit;

        $puzzles = $repository->findLocaleExt("'%', '@', 'P'", $locale, $first, $limit, $query, $count);

        $pages = ceil($count / $limit);

        $template = $request->isXmlHttpRequest() ? 'puzzles/list.content.html.twig' : 'puzzles/list.html.twig';
        
        return $this->render($template, array(
            'count' => $count,
            'pages' => $pages,
            'page' => $page,
            'query' => $query,
            'puzzles' => $puzzles,
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
        ));
    }

    /**
     * @Route({
     *      "en": "/your-puzzles/create-a-puzzle",
     *      "fr": "/vos-puzzles/creer-un-puzzle"
     * }, name="your_puzzles_create")
     */
    public function create(Request $request, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator)
    {
        $locale = $request->getLocale();

        $puzzle = new Puzzle();
        $puzzle->setTitle('No Name');

        $form = $this->createForm(PuzzleCreateType::class, $puzzle);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $puzzle->setTitle(json_encode([$locale => $puzzle->getTitle()]));

            $puzzle->setPartner('@');

            $puzzle->setLocale($locale);

            $puzzle->setFilename('test');

            $now = new \DateTime();
            $puzzle->setCreated($now);
            $puzzle->setUpdated($now);
            //$puzzle->setPublished($now);
            $em->persist($puzzle);
            $em->flush();

            return $this->redirectToRoute('your_puzzles_list');
        }

        return $this->render('puzzles/create.html.twig', array(
            'form' => $form->createView(),
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
        ));
    }

    /**
     * @Route({
     *      "en": "/your-puzzles/edit/{id<\d+>}",
     *      "fr": "/vos-puzzles/editer/{id<\d+>}"
     * }, name="your_puzzles_edit")
     */
    public function edit(Request $request, Puzzle $puzzle, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator, TranslatorInterface $translator)
    {
        $locale = $request->getLocale();

        $session = $request->getSession();

        $form = $this->createForm(PuzzleEditType::class, $puzzle, [
            'action' => $request->getRequestUri()
        ]);

        $form->handleRequest($request);

        if ($request->getMethod() == 'POST') {

            $em = $this->getDoctrine()->getManager();

            $puzzle->setTitle(json_encode($request->get('title')));
            
            $keywords = $form->get('keywords')->getData();
            if(strlen($keywords) < 5) {
                $form->get('keywords')->addError(new FormError($translator->trans('puzzle.edit.keywords.error')));
            } else {
                $puzzle->setKeywords($keywords);
            }

            if(0 === count($form->getErrors(true, true))) {
                $now = new \DateTime();
                $puzzle->setUpdated($now);

                $em->flush();
                
                if($request->isXmlHttpRequest()) {
                    $response = new Response("page.call('{$session->get('listUrl')}');");
                    $response->headers->set('Content-Type', 'text/javascript');
                    return $response;
                } else {
                    return $this->redirect($session->get('listUrl'));               
                }

            } else {
                $form->addError(new FormError($translator->trans('puzzle.edit.global.error')));
                if($request->isXmlHttpRequest()) {
                    $data =  $this->renderView('puzzles/edit.content.html.twig', array(
                        'form' => $form->createView(),
                        'puzzle' => $puzzle,
                    ));
                    $response = new Response("try{\n\$('#central-content').html(decodeURIComponent('" . rawurlencode($data). "'));\n}catch(e){\nconsole.log(e);\n}\n");
                    $response->headers->set('Content-Type','text/javascript');
                    return $response;
                }
            }

        }

        $template = $request->isXmlHttpRequest() ? 'puzzles/edit.content.html.twig' : 'puzzles/edit.html.twig';
        
        return $this->render($template, array(
            'form' => $form->createView(),
            'puzzle' => $puzzle,
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
        ));
    }

    /**
     * @Route({
     *      "en": "/your-puzzles/edit-modal/{id<\d+>}",
     *      "fr": "/vos-puzzles/editer-modal/{id<\d+>}"
     * }, name="your_puzzles_edit_modal")
     */
    public function edit_modal(Request $request, Puzzle $puzzle)
    {
        $form = $this->createForm(PuzzleCreateType::class, $puzzle, [
            'action' => $request->getUri()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //return $this->redirectToRoute('your_puzzles_list');
        }

        return $this->render('puzzles/edit_modal.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/puzzles/delete/{id<\d+>}", methods={"GET", "POST"})
     */
    public function delete(Request $request, Puzzle $puzzle)
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($puzzle);
        $em->flush();

        return $this->redirectToRoute('your_puzzles_list');
    }

    /**
     * @Route("/puzzles/preview/{filename<.+>}")
     */
    public function preview(Request $request, string $filename)
    {
        $filepath = $this->getParameter('kernel.project_dir') . '/public/data/puzzles/' . $filename . '.jpg';
        $filename = basename($filepath);

        list($src_width, $src_height, $src_format, $html_code) = getimagesize($filepath);
        $ratio = $src_width / $src_height;
        if($ratio > 1.0){
            $dst_width = 100;
            $dst_height = intval(100 / $ratio);
        }else{
            $dst_width = intval(100 * $ratio);
            $dst_height = 100;
        }
        
        $src_img = @imagecreatefromjpeg($filepath);
        
        $dst_img = imagecreatetruecolor($dst_width, $dst_height);
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
        
        ob_start();
        imagejpeg($dst_img);
        $image = ob_get_clean();

        $response = new Response();
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $filename);
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'image/jpeg');
        $response->setContent($image);

        return $response;
    }
}