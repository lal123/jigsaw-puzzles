<?php
// src/Controller/UserController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/user/create-account")
     */
    public function create_account(Request $request)
    {
        $user = new User();
        $user->setName('No Name');

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $repository = $this->getDoctrine()->getRepository(User::class);

            if(!$repository->existsUser($user->getName())) 
            {
                
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('app_puzzles_list');
            }
            else
            {
                $this->addFlash(
                    'error',
                    'User name already exists!'
                );
                // $this->addFlash() is equivalent to $request->getSession()->getFlashBag()->add()
            }
        }

        return $this->render('user/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}