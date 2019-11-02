<?php
// src/Controller/PlayerController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Form\PlayerCreateType;
use App\Form\PlayerLoginType;
use App\Entity\Player;
//use App\EventListener\RequestListener;
use App\Services\UrlTranslator;

class PlayerController extends AbstractController
{
    /**
     * @Route({
     *      "en": "/player/create-an-account",
     *      "fr": "/joueur/creer-un-compte"}, name="player_create_account")
     */
    public function create_account(Request $request, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator, TranslatorInterface $translator)
    {
        $session = $request->getSession();

        $player = new Player();
        $player->setName($translator->trans('Anonymous'));

        $form = $this->createForm(PlayerCreateType::class, $player);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $repository = $this->getDoctrine()->getRepository(Player::class);

            if(!$repository->existsPlayer($player->getName())) 
            {
                $session->set('player', $player);
            
                $em->persist($player);
                $em->flush();

                return $this->redirectToRoute('homepage');
            }
            else
            {
                $this->addFlash(
                    'error',
                    'Player name already exists!'
                );
            }
        }

        return $this->render('player/create.html.twig', array(
            'form' => $form->createView(),
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
        ));
    }

    /**
     * @Route({
     *      "en": "/player/sign-in",
     *      "fr": "/joueur/se-connecter"
     * }, name="player_sign_in")
     */
    public function sign_in(Request $request, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator, TranslatorInterface $translator)
    {
        $session = $request->getSession();

        $player = new Player();
        $player->setName($translator->trans('Anonymous'));

        $form = $this->createForm(PlayerLoginType::class, $player);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            
            $repository = $this->getDoctrine()->getRepository(Player::class);

            if($repository->existsPlayer($player->getName())) 
            {
                $session->set('player', $player);
            
                return $this->redirectToRoute('homepage');
            }
            else
            {
                $this->addFlash(
                    'error',
                    'Player name does no exist!'
                );
            }
        }

        return $this->render('player/login.html.twig', array(
            'form' => $form->createView(),
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
        ));
    }

    /**
     * @Route({
     *      "en": "/player/sign-out",
     *      "fr": "/joueur/se-deconnecter"
     * }, name="player_sign_out")
     */
    public function sign_out(Request $request)
    {
        $session = $request->getSession();

        $session->clear();

        return $this->redirectToRoute('homepage');
    }
}