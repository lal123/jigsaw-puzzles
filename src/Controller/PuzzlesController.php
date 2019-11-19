<?php
// src/Controller/PuzzlesController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Form\PuzzleType;
use App\Entity\Puzzle;
//use App\EventListener\RequestListener;
use App\Services\UrlTranslator;

class PuzzlesController extends AbstractController
{
    /**
     * @Route({
     *      "en": "/your-puzzles",
     *      "fr": "/vos-puzzles"
     * }, name="your_puzzles_list")
     */
    public function list(Request $request, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator)
    {
        $locale = $request->getLocale();

        $session = $request->getSession();

        $repository = $this->getDoctrine()->getRepository(Puzzle::class);

        $puzzles = $repository->findLocaleExt("'%', '@'", $locale);

        //echo $request->getSession()->get('a');die();

        return $this->render('puzzles/list.html.twig', array(
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

        $form = $this->createForm(PuzzleType::class, $puzzle);

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
    public function edit(Request $request, Puzzle $puzzle, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator)
    {
        $form = $this->createForm(PuzzleType::class, $puzzle);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $now = new \DateTime();
            $puzzle->setUpdated($now);

            $em->flush();

            return $this->redirectToRoute('your_puzzles_list');
        }

        return $this->render('puzzles/edit.html.twig', array(
            'form' => $form->createView(),
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
        ));
    }

    /**
     * @Route({
     *      "en": "/your-puzzles/edit-modal/{id<\d+>}",
     *      "fr": "/vos-puzzles/editer-modal/{id<\d+>}"
     * }, name="your_puzzles_edit_modal")
     */
    public function edit_modal(Request $request, Puzzle $puzzle, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator)
    {
        $form = $this->createForm(PuzzleType::class, $puzzle, [
            'action' => $request->getUri()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('your_puzzles_list');
        }

        return $this->render('puzzles/edit_modal.html.twig', array(
            'form' => $form->createView(),
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
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
}