<?php
// src/Controller/PlayerController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\FormError;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Form\PlayerCreateType;
use App\Form\PlayerUpdateType;
use App\Form\PlayerLoginType;
use App\Entity\Player;
//use App\EventListener\RequestListener;
use App\Services\UrlTranslator;

class PlayerController extends AbstractController
{
    /**
     * @Route({
     *      "en": "/player/create-an-account",
     *      "fr": "/joueur/creer-un-compte"
     * }, name="player_create_account")
     */
    public function create_account(Request $request, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator, TranslatorInterface $translator)
    {
        $session = $request->getSession();

        $player = $session->get('player');

        if(null !== $player) {
            return $this->redirectToRoute('player_update_account');
        }

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
     *      "en": "/player/your-account",
     *      "fr": "/joueur/votre-compte"
     * }, name="player_update_account")
     */
    public function update_account(Request $request, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator, TranslatorInterface $translator)
    {
        $session = $request->getSession();

        if(!$session->has('player')) {
            return $this->redirectToRoute('player_create_account');
        }

        $em = $this->getDoctrine()->getManager();
        
        $repository = $this->getDoctrine()->getRepository(Player::class);

        $player = $repository->getPlayerFromId($session->get('player')->getId());

        //var_dump($player);

        $form = $this->createForm(PlayerUpdateType::class, $player);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $error = false;

            $password = $form->get('password')->getData();

            if(NULL !== $password) {
                if(strlen($password) < 4 ) {
                    $error = true;
                    $form->get('password')->addError(new FormError('Wrong password'));
                } else {
                    $player->setPassword($password);
                }
            }

            if($repository->existsPlayerName($player->getName(), $player->getId())) {
                $error = true;
                $form->get('name')->addError(new FormError('Player name already exists!'));
            }

            if($error == false) {
                $em->merge($player);
                $em->flush();

                $session->set('player', $player);

                $this->addFlash(
                    'notice',
                    "Updated! (pw: {$password})"
                );
            }
        }

        return $this->render('player/update.html.twig', array(
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

            $player_obj = $repository->existsPlayer($player->getName());

            if($player_obj) 
            {
                $session->set('player', $player_obj);
            
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