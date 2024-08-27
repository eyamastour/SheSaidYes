<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use App\Form\EditProfileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_profile")
     */
    public function index(): Response
    {

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }


    /**
     * @Route("/profile/modifier", name="app_modifier_profile")
     */
    public function editProfile(Request $request, FlashyNotifier $flashyNotifier)
    {

        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);
        //  $old_password = $user->getPassword();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //  $user -> setCreatedDate(new DateTime());
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                //$safeFilename = $slugger->slug($originalFilename);
                $safeFilename = $originalFilename;
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setImage($newFilename);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $flashyNotifier->success('Succes');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/editProfile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/pass/modifier", name="app_modifier_pass")
     */
    public function editPass(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, FlashyNotifier $flashyNotifier)
    {

        // Modifier mot de passe

        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();
            $p = $this->getUser()->getPassword();

            // On vérifie si les 2 mots de passe sont identiques
            //dd($request->request->get('pass'));
        
            if ($request->request->get('pass') == $request->request->get('pass2')) {
                $user->setPassword($userPasswordEncoder->encodePassword($user,$request->request->get('pass2')));
                $em->flush();
                $this->addFlash('message', 'Mot de passe mis à jour avec succès');

               
            } else {
                $this->addFlash('error', 'Ancien mdp incorect ou Les deux mots de passe ne sont pas identiques ');
            }
        }


        return $this->render('profile/editPass.html.twig', array());
    }
}
