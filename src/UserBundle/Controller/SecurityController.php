<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Swift_Message;
use UserBundle\Entity\User;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login" )
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('UserBundle:Default:login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error
        ));
    }


    /**
     * @Route("/register", name="register" )
     */
    public function registerAction(Request $request)
    {


        if( $request->isMethod("POST") ) {

            $error = "";

            $nom = $request->get('nom');
            $prenom = $request->get('prenom');
            $email = $request->get('email');
            $password = $request->get('password');


            if ($nom == "") {
                $error = "votre nom est vide";
            } elseif ($prenom == "") {
                $error = "votre prenom est vide";
            } elseif ($password == "") {
                $error = "Votre mot de passe est obligatoire";
            } elseif (strlen($password) < 6) {
                $error = "Votre mot de passe doit contenir plus de 6 caractères";
            } elseif ($email == "") {
                $error = "Vous devez indiquer votre email";
            }


            //Verification de l'email
            $em = $this->getDoctrine()->getManager();
            $traitement = $em->getRepository("UserBundle:User")->findBy(["email" => $email]);
            if ($traitement) {
                $error = 'Cette adresse e-mail est déjà utilisée';
            }


            if ($error !== "") {

                $request->getSession()->getFlashBag()->add('error', $error);
                return $this->render('UserBundle:Default:register.html.twig');

            } else {

                $user = new User();
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $user->setEmail($email);
                $user->setUsername($nom);
                $user->setRoles(['ROLE_USER']);
                $user->setActive(1);
                $user->setBackground("#0000ff");
                $encoder = $this->container->get('security.password_encoder');
                $pass = $encoder->encodePassword($user, $password);
                $user->setPassword($pass);
                $em->persist($user);
                $em->flush();


                $request->getSession()->getFlashBag()->add('success', 'Vous avez été bien Inscris');
                return $this->render('UserBundle:Default:register.html.twig');

            }


        }

        return $this->render('UserBundle:Default:register.html.twig');
    }
    /**
     * @Route("/profile", name="profile")
     */
    public function profileAction(Request $request)
    {
        $ids = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();

        $contentusers = $em->getRepository('UserBundle:User')->findOneBy(['id' => $ids]);

        if ($request->getMethod() == 'POST') {
            $id = $this->getUser()->getId();
            $prenom = $request->get('prenom');
            $nom = $request->get('nom');
            $email = $request->get('email');

            $user = $em->getRepository('UserBundle:User')->find($id);
            $user->setPrenom($prenom);
            $user->setNom($nom);
            $user->setEmail($email);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success3', 'Information(s) modifier');

            return $this->render('UserBundle:Default:profile.html.twig', ['getusers' => $contentusers]);

        }
            return $this->render('UserBundle:Default:profile.html.twig', ['getusers' => $contentusers]);



    }




}
