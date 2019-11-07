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

        $em = $this->getDoctrine()->getManager();
        
        $repository = $this->getDoctrine()->getRepository(Player::class);

        $player = new Player();

        $form = $this->createForm(PlayerCreateType::class, $player);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form->get('name')->getData();
            if(strlen($name) < 4) {
                $form->get('name')->addError(new FormError($translator->trans('player.error.name.invalid')));
            } else {
                if($repository->existsPlayer($name)) {
                    $form->get('name')->addError(new FormError($translator->trans('player.error.name.exists')));
                }
            }

            $password = $form->get('password')->getData();
            if(strlen($password) < 4 ) {
                $form->get('password')->addError(new FormError($translator->trans('player.error.password.invalid')));
            } else {
                $confirm = $form->get('confirm')->getData();
                if($confirm != $password) {
                    $form->get('confirm')->addError(new FormError($translator->trans('player.error.confirm.invalid')));
                } else {
                    $player->setPassword($password);
                }
            }

            $email = $form->get('email')->getData();
            if($repository->existsEmail($email)) {
                $form->get('email')->addError(new FormError($translator->trans('player.error.email.exists')));
            }

            if(0 === count($form->getErrors(true, true))) {

                $em->persist($player);
                $em->flush();

                $session->set('player', $player);
            
                return $this->redirectToRoute('homepage');
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

        $form = $this->createForm(PlayerUpdateType::class, $player);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form->get('name')->getData();
            if(strlen($name) < 4) {
                $form->get('name')->addError(new FormError($translator->trans('player.error.name.invalid')));
            } else {
                if($repository->existsPlayerName($player->getName(), $player->getId())) {
                    $form->get('name')->addError(new FormError($translator->trans('player.error.name.exists')));
                }
            }

            $password = $form->get('password')->getData();
            if(NULL !== $password) {
                if(strlen($password) < 4 ) {
                    $form->get('password')->addError(new FormError($translator->trans('player.error.password.invalid')));
                } else {
                    $confirm = $form->get('confirm')->getData();
                    if($confirm != $password) {
                        $form->get('confirm')->addError(new FormError($translator->trans('player.error.confirm.invalid')));
                    } else {
                        $player->setPassword($password);
                    }
                }
            }

            $email = $form->get('email')->getData();
            if($repository->existsEmailAddress($email, $player->getId())) {
                $form->get('email')->addError(new FormError($translator->trans('player.error.email.exists')));
            }

            if(0 === count($form->getErrors(true, true))) {

                $em->merge($player);
                $em->flush();

                $session->set('player', $player);

                $this->addFlash('notice', $translator->trans('player.update.success'));
            }
        }

        return $this->render('player/update.html.twig', array(
            'form' => $form->createView(),
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator)
        ));
    }

    /**
     * @Route({
     *      "en": "/player/delete-account",
     *      "fr": "/joueur/supprimer-le-compte"
     * }, name="player_delete_account")
     */
    public function delete_account(Request $request)
    {
        $session = $request->getSession();

        if(!$session->has('player')) {
            return $this->redirectToRoute('player_create_account');
        }

        $em = $this->getDoctrine()->getManager();
        
        $repository = $this->getDoctrine()->getRepository(Player::class);

        $player = $repository->getPlayerFromId($session->get('player')->getId());

        $em->remove($player);
        $em->flush();

        $session->clear();

        return $this->redirectToRoute('homepage');
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

        $em = $this->getDoctrine()->getManager();
        
        $repository = $this->getDoctrine()->getRepository(Player::class);

        $player = new Player();

        $form = $this->createForm(PlayerLoginType::class, $player);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $player_obj = $repository->existsPlayerWithPassword($player->getName(), $player->getPassword());

            if($player_obj) 
            {
                $session->set('player', $player_obj);
                /*
                $remember_me = $form->get('remember_me')->getData();

                if(true === $remember_me) {

                }
                */
                return $this->redirectToRoute('homepage');
            }
            else
            {
                $this->addFlash('error', $translator->trans('player.error.login.invalid'));
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