<?php
// src/Controller/PuzzlesController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Form\PuzzleType;
use App\Entity\Puzzle;
use App\EventListener\RequestListener;
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
        $repository = $this->getDoctrine()->getRepository(Puzzle::class);

        $puzzles = $repository->findAll();

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
        $puzzle = new Puzzle();
        $puzzle->setTitle('No Name');

        $form = $this->createForm(PuzzleType::class, $puzzle);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
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
     * @Route("/puzzles/edit/{id<\d+>}")
     */
    public function edit(Request $request, Puzzle $puzzle)
    {
        $form = $this->createForm(PuzzleType::class, $puzzle);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('your_puzzles_list');
        }

        return $this->render('puzzles/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/puzzles/edit_modal/{id<\d+>}")
     */
    public function edit_modal(Request $request, Puzzle $puzzle)
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