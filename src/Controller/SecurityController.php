<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Services\UrlTranslator;
use App\Entity\User;
use App\Form\UserRegisterType;
use App\Form\UserAccountType;

class SecurityController extends AbstractController
{
    /**
     * @Route({
     *      "en": "/player/sign-in",
     *      "fr": "/joueur/se-connecter"
     * }, name="app_login")
     */
    public function login(Request $request, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator, TranslatorInterface $translator, AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
        	'last_username' => $lastUsername,
        	'error' => $error,
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator),
        ]);
    }

    /**
     * @Route({
     *      "en": "/player/sign-out",
     *      "fr": "/joueur/se-deconnecter"
     * }, name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route({
     *      "en": "/player/create-an-account",
     *      "fr": "/joueur/creer-un-compte"
     * }, name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator, TranslatorInterface $translator)
    {
        $user = new User();

        $form = $this->createForm(UserRegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

	        $repository = $this->getDoctrine()->getRepository(User::class);

            $username = $form->get('username')->getData();
            if(strlen($username) < 4) {
                $form->get('username')->addError(new FormError($translator->trans('player.error.name.invalid')));
            } else {
                if($repository->existsUsername($username)) {
                    $form->get('username')->addError(new FormError($translator->trans('player.error.name.exists')));
                }
            }

            $password = $form->get('password')->getData();
            if(strlen($password) < 4 ) {
                $form->get('password')->addError(new FormError($translator->trans('player.error.password.invalid')));
            } else {
                $confirm = $form->get('confirm')->getData();
                if($confirm != $password) {
                    $form->get('confirm')->addError(new FormError($translator->trans('player.error.confirm.invalid')));
                }
            }

            $email = $form->get('email')->getData();
            if($repository->existsEmail($email)) {
                $form->get('email')->addError(new FormError($translator->trans('player.error.email.exists')));
            }

            if(0 === count($form->getErrors(true, true))) {

	            $user->setPassword($passwordEncoder->encodePassword($user, $password));
	
	            $em->persist($user);
	            $em->flush();

	            return $this->redirectToRoute('homepage');
	        }
        }

        return $this->render('security/register.html.twig', [
        	'form' => $form->createView(),
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator),
        ]);
    }

    /**
     * @Route({
     *      "en": "/player/your-account",
     *      "fr": "/joueur/votre-compte"
     * }, name="app_account")
     */
    public function account(Request $request, UserPasswordEncoderInterface $passwordEncoder, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator, TranslatorInterface $translator)
    {
		$_user = $this->getUser();

		$user = clone $_user;

        $form = $this->createForm(UserAccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        
	        $em = $this->getDoctrine()->getManager();
	        
	        $repository = $this->getDoctrine()->getRepository(User::class);

            $username = $form->get('username')->getData();
            if(strlen($username) < 4) {
                $form->get('username')->addError(new FormError($translator->trans('player.error.name.invalid')));
            } else {
                if($repository->isAlreadyUsedUsername($username, $user->getId())) {
                    $form->get('username')->addError(new FormError($translator->trans('player.error.name.exists')));
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
                    }
                }
            }

            $email = $form->get('email')->getData();
            if($repository->isAlreadyUsedEmail($email, $user->getId())) {
                $form->get('email')->addError(new FormError($translator->trans('player.error.email.exists')));
            }

            if(0 === count($form->getErrors(true, true))) {

	            if(NULL !== $password) {
	            	$user->setPassword($passwordEncoder->encodePassword($user, $password));
	            }

                $em->merge($user);
                $em->flush();

                $this->addFlash('notice', $translator->trans('player.update.success'));
            }
        }

        return $this->render('security/account.html.twig', [
        	'form' => $form->createView(),
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator),
        ]);
    }

	/**
     * @Route({
     *      "en": "/player/forgotten-password",
     *      "fr": "/joueur/mot-de-passe-oublie"
     * }, name="app_forgotten_password")
     */
    public function forgottenPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator, TranslatorInterface $translator): Response
    {

        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('homepage');
            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('homepage');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Forgot Password'))
                ->setFrom('g.ponty@dev-web.io')
                ->setTo($user->getEmail())
                ->setBody(
                    "blablabla voici le token pour reseter votre mot de passe : " . $url,
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('notice', 'Mail envoyé');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('security/forgotten_password.html.twig', [
            'locale_versions' => $urlTranslator->translate($request, $urlGenerator),
        ]);
    }

	/**
     * @Route({
     *      "en": "/player/reset-password/{token}",
     *      "fr": "/joueur/changement-mot-de-passe/{token}"
     * }, name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder, UrlGeneratorInterface $urlGenerator, UrlTranslator $urlTranslator, TranslatorInterface $translator)
    {

        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(User::class)->findOneByToken($token);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('homepage');
            }

            $user->setToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour');

            return $this->redirectToRoute('homepage');
        }else {

            return $this->render('security/reset_password.html.twig', [
            	'token' => $token,
	            'locale_versions' => $urlTranslator->translate($request, $urlGenerator),
            ]);
        }

    }
}