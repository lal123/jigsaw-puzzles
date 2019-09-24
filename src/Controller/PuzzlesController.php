<?php
// src/Controller/PuzzlesController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PuzzleType;
use App\Entity\Puzzle;

class PuzzlesController extends AbstractController
{
    /**
     * @Route("/puzzles")
     */
    public function list(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Puzzle::class);

        $puzzles = $repository->findAll();

        return $this->render('puzzles/list.html.twig', array(
            'puzzles' => $puzzles,
        ));
    }

    /**
     * @Route("/puzzles/create")
     */
    public function create(Request $request)
    {
        $article = new Puzzle();
        $article->setTitle('No Name');

        $form = $this->createForm(PuzzleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('app_puzzles_list');
        }

        return $this->render('puzzles/create.html.twig', array(
            'form' => $form->createView(),
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

            return $this->redirectToRoute('app_puzzles_list');
        }

        return $this->render('puzzles/edit.html.twig', array(
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

        return $this->redirectToRoute('app_puzzles_list');
    }
}