<?php

namespace App\Controller;
use App\Entity\Utilisateur;
use App\Controller\UtilisateurController;
use App\Form\ResetPassType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class SecurityController extends AbstractController
{

    
    /**
     * @Route("/security", name="app_security")
     */
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    
    public function login(AuthenticationUtils $authenticationUtils): Response
    {          
       


        if ($this->IsGranted('ROLE_CLIENT')){
             if($this->getUser()->getBloquer()==1)
            return $this->redirectToRoute('app_bloque');

            return $this->redirectToRoute('app_home_page');  
        }

        else if ($this->IsGranted('ROLE_PRESTATAIRE') )
        {
            if($this->getUser()->getBloquer()==1)
            return $this->redirectToRoute('app_bloque');

            return $this->redirectToRoute('app_prest_back'); 
        }
        
        else if ($this->IsGranted('ROLE_ADMIN') )
        {
            if($this->getUser()->getBloquer()==1)
            return $this->redirectToRoute('app_bloque');

            return $this->redirectToRoute('app_prestataire'); 
        
        }
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', 
        ['last_username' => $lastUsername, 
        'error' => $error]);
    }





    
    /**
     * @Route("/oubli-pass", name="app_forgotten_password")
     */
    public function oubliPass(Request $request, UtilisateurRepository $utilisateur, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator
    ): Response
    {
        // On initialise le formulaire
        $form = $this->createForm(ResetPassType::class);

        // On traite le formulaire
        $form->handleRequest($request);

        // Si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les données
            $donnees = $form->getData();

            // On cherche un utilisateur ayant cet e-mail
            $utilisateur = $utilisateur->findOneBy(['email'=> $donnees]);

            // Si l'utilisateur n'existe pas
            if ($utilisateur === null) {
                // On envoie une alerte disant que l'adresse e-mail est inconnue
                $this->addFlash('danger', 'Cette adresse e-mail est inconnue');

                // On retourne sur la page de connexion
                return $this->redirectToRoute('app_login');
            }

            // On génère un token
            // token sécurisé dispositif nécessaire à un utilisateur pour accéder à une application  de manière plus sécurisée.
            $token = $tokenGenerator->generateToken();

            // On essaie d'écrire le token en base de données
            try{
                $utilisateur->setResetToken($token);
             //un objet Doctrine qui va nous permettre de Gérer nos entités,
                $entityManager = $this->getDoctrine()->getManager();
                //envoyer les donnes a la base
                $entityManager->persist($utilisateur);
             //Doctrine vérifie tous les champs de toutes les données récupérées 
             //et effectue une transaction vers la base de données   
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            // On génère l'URL de réinitialisation de mot de passe
            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            // On génère l'e-mail
            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom('shesaidyes.pidev@gmail.com')
                ->setTo($utilisateur->getEmail())
                ->setBody(
                    "Bonjour,<br><br>Une demande de réinitialisation de mot de passe a été effectuée pour le site She Said Yes. Veuillez cliquer sur le lien suivant : " . $url,
                    'text/html'
                )
            ;

            // On envoie l'e-mail
            $mailer->send($message);

            // On crée le message flash de confirmation
            $this->addFlash('message', 'E-mail de réinitialisation du mot de passe envoyé !');

            // On redirige vers la page de login
            return $this->redirectToRoute('app_login');
        }

        // On envoie le formulaire à la vue
        return $this->render('security/forgotten_password.html.twig',['emailForm' => $form->createView()]);
    }

    /**
     * @Route("/reset_pass/{token}", name="app_reset_password")
     */
    
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        // On cherche un utilisateur avec le token donné
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['reset_token' => $token]);

        // Si l'utilisateur n'existe pas
        if ($utilisateur === null) {
            // On affiche une erreur
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('app_login');
        }

           // Si le formulaire est envoyé en méthode post
            if ($request->isMethod('POST')) {
            // On supprime le token
            $utilisateur->setResetToken(null);

            // On chiffre le mot de passe
            $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $request->request->get('password')));

            // On stocke
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            // On crée le message flash
            $this->addFlash('message', 'Mot de passe mis à jour');

            // On redirige vers la page de connexion
            return $this->redirectToRoute('app_login');
        }else {
            // Si on n'a pas reçu les données, on affiche le formulaire
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    
    }
}
