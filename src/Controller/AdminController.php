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
        $count['L']['en'] = $repository->migratePuzzles('%', '*', 3);
        $count['@']['en'] = $repository->migratePuzzles('@', 'en', 3);
        $count['@']['fr'] = $repository->migratePuzzles('@', 'fr', 3);
        $count['P']['fr'] = $repository->migratePuzzles('P', 'fr', 3);

        return $this->render('admin/puzzles_migration.html.twig', array(
            'count' => $count,
        ));
    }

}