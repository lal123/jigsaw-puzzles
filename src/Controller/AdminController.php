<?php
// src/Controller/AdminController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Form\PuzzleType;
use App\Entity\Puzzle;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/puzzles-migration", name="admin_puzzles_migration")
     */
    public function list(Request $request)
    {

        $repository = $this->getDoctrine()->getRepository(Puzzle::class);

        $count = [];
        $count['L']['en'] = $repository->migratePuzzles('%', '*', 120);
        $count['@']['en'] = $repository->migratePuzzles('@', 'en', 120);
        $count['@']['fr'] = $repository->migratePuzzles('@', 'fr', 120);
        $count['P']['fr'] = $repository->migratePuzzles('P', 'fr', 120);

        return $this->render('admin/puzzles_migration.html.twig', array(
            'count' => $count,
        ));
    }

}