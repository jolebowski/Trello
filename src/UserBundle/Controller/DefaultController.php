<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Swift_Message;

use Symfony\Component\Validator\Constraints\DateTime;
use UserBundle\Entity\Invite;
use UserBundle\Entity\User;
use UserBundle\Entity\Projects;
use UserBundle\Entity\Category;
use UserBundle\Entity\Tasks;
use UserBundle\Entity\Undertasks;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('UserBundle:Default:index.html.twig');
    }

    /**
     * @Route("/connect", name="connect")
     */
    public function connectAction(Request $request)
    {
        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

            $em = $this->getDoctrine()->getManager();

            //Recuperation de l'id connecte
            $id = $this->getUser()->getId();

            //recuperation de tout les projets creer par l'utilisateur
            $lists_projets = $em->getRepository("UserBundle:Projects")->findBy(['userid' => $id] );

            $lists_projets_invite = $em->getRepository("UserBundle:Invite")->findBy(['idinvite' => $id] );

            $array = [];
            foreach($lists_projets_invite as $value){
                array_push($array, $value->getIdprojet());
            }

            $om = $this->getDoctrine()->getManager();
            $query = $om->createQuery(
                'SELECT u FROM UserBundle:Invite u WHERE u.idprojet IN (:idprojects)'
            )->setParameter('idprojects', $array);
            $reponses = $query->getResult();

            $array_ = [];
            foreach($lists_projets_invite as $value){
                array_push($array_, $value->getIdprojet());
            }


            $query = $om->createQuery(
                'SELECT u FROM UserBundle:Projects u WHERE u.id IN (:idprojects)'
            )->setParameter('idprojects', $array_);
            $reponse_ = $query->getResult();


            //Check si un POST est envoyé
            if ($request->isMethod("POST")) {

                $nameprojet = $request->request->get("nameprojet");

                $project = new Projects();
                $project->setUserid($id);
                $project->setNameproject($nameprojet);
                $em->persist($project);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Vous avez bien ajouter votre projet');
                return $this->redirectToRoute('connect' , ['projets' => $lists_projets, 'listpartage' => $reponse_ ]);
            }

            return $this->render('UserBundle:Default:connect.html.twig', ['projets' => $lists_projets, 'listpartage' => $reponse_  ]);
        }else{
            return $this->redirectToRoute('login');
        }

    }


    /**
     * @Route("/projects/{idprojet}", name="projects")
     */
    public function projectstAction(Request $request)
    {
        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

            //Recupere l'id du projet
            $idprojet  = $request->get("idprojet");
            $nameTasks = $request->get("tasks");
            $em = $this->getDoctrine()->getManager();

            //Recuperation de l'id connecte
            $id = $this->getUser()->getId();

            $listsTasks = $em->getRepository("UserBundle:Tasks")->findBy(['idprojet' => $idprojet]);

            $listsUnderTasks = $em->getRepository("UserBundle:Undertasks")->findAll();

            //Check si un POST est envoyé
            if ($request->isMethod("POST")) {

                $content_ = $request->get("content");
                $tache_   = $request->get("tache");
                $invite   = $request->get("membre");


                $delete_content = $request->get("delete_content");
                $key_content    = $request->get("key_content");
                $update_content = $request->get("update_content");

                //Date actuelle
                $date_ = new \DateTime();

                if($content_ != "" && $tache_ != "" ) {

                    $undertasks = new Undertasks();
                    $undertasks->setIdtasks($tache_);
                    $undertasks->setContenu($content_);
                    $undertasks->setCreated($date_);
                    $em->persist($undertasks);
                    $em->flush();

                    $prenom    = $this->getUser()->getPrenom();
                    $email = $this->getUser()->getEmail();

                    $date_ = date("F j, Y, g:i a");


                    // Envoie du mail de confirmation
                    $message = Swift_Message::newInstance()
                        ->setSubject('TREELLO - Nouvelle Tache Ajouter Par '.$prenom.' ')
                        ->setFrom('Courrier@treello.com')
                        ->setTo($email)
                        ->setBody(
                            '<!DOCTYPE html>'.
                            '<html lang="fr">'.
                            '<head>'.
                            '<title>Reparizy</title>'.
                            '<meta charset="utf-8">'.
                            '<meta name="viewport" content="width=device-width, initial-scale=1">'.
                            '<style>'.
                            '.bg-1 {background-color: #25698c;color: #ffffff;}'.
                            '.bg-2 {background-color: #fff;color: #000; border-bottom:1px #aaa solid; }'.
                            '.bg-3 {background-color: #ffffff;color: #555555;}'.
                            '.container-fluid {padding-top: 70px;padding-bottom: 70px;}'.
                            '.img-icon{width: 316px;padding-top: 7px;}'.
                            '.border-nop{ border: none;text-align: left;}'.
                            '.sizefooter{padding: 15px;text-align:center;} .red{color:red; font-weight:bold;}'.
                            '.text-center{text-align:center;}'.
                            '.btnconfig{ background-color: #25698c; border: none;color: white;padding: 15px 32px; text-align: center;text-decoration: none;display: inline-block;font-size: 16px;}'.
                            '</style>'.
                            '</head>'.
                            '<body>'.

                            '<nav class="bg-2 text-center sizefooter">'.
                            '<div style="text-align: center;">'.
                            '<img src="https://d13yacurqjgara.cloudfront.net/users/540920/screenshots/2360020/sans-titre---1_teaser.png" class="img-icon" >'.
                            '</div>'.
                            '</nav>'.

                            '<div class="container-fluid bg-3 text-center">'.
                            '<h3>TACHE TREELLO !</h3>'.
                            '<p> Cher '.$prenom.', <br><br> Une nouvelle tache à été ajouter a un projet sur Treello <br>'.
                            '<p> Contenu Ajouter : '.$content_.', <br> <br>'.
                            '<p> A : '.$date_.', <br> <br>'.
                            '</p>'.
                            '</div>'.
                            '<div class="bg-1 sizefooter">'.
                            '<p>A très bientôt, <br> L\'équipe Treello </p>'.
                            '</div>'.
                            '</body>'.
                            '</html>',
                            'text/html'
                        );
                    $this->get('mailer')->send($message);



                    $request->getSession()->getFlashBag()->add('success', 'Vous avez bien ajouter votre projet');


                    return $this->redirectToRoute('projects' , ['idprojet'=> $idprojet,'tasksid' => $undertasks->getId(),
                        'listsTasks' => $listsTasks,
                        'listsUnderTasks' => $listsUnderTasks ]);

                }else if($delete_content != "") {


                    $om = $this->getDoctrine()->getManager();
                    $query = $om->createQuery(
                        'DELETE FROM  UserBundle:Undertasks u WHERE u.id = (:id)'
                    )->setParameter('id', $delete_content);
                    $reponses = $query->getResult();

                    return $this->redirectToRoute('projects' , ['idprojet'=> $idprojet,'tasksid' => $idprojet,
                            'listsTasks' => $listsTasks,
                            'listsUnderTasks' => $listsUnderTasks ]);


                }else if($key_content != "" && $update_content != "") {

                    $om = $this->getDoctrine()->getManager();
                    $query = $om->createQuery(
                        'UPDATE UserBundle:Undertasks u
                            set u.contenu = :contenu
                            WHERE u.id = :id '
                    )->setParameter('contenu', $update_content)
                    ->setParameter('id', $key_content);
                    $reponses = $query->getResult();

                    return $this->redirectToRoute('projects' , ['idprojet'=> $idprojet,'tasksid' => $idprojet,
                            'listsTasks' => $listsTasks,
                            'listsUnderTasks' => $listsUnderTasks ]);


                } else if($invite != "") {

                    $InfoInvite = $em->getRepository("UserBundle:User")->findBy(['email' => $invite]);


                    if(!empty($InfoInvite)){

                        $idInvite_ = $InfoInvite[0]->getId();
                        $prenom    = $InfoInvite[0]->getPrenom();
                        $postInvitation = $this->getUser()->getPrenom();
                        $email = $invite;


                        // Envoie du mail de confirmation
                        $message = Swift_Message::newInstance()
                            ->setSubject('TREELLO - Invitation Par '.$postInvitation.' ')
                            ->setFrom('Invitation@treello.com')
                            ->setTo($email)
                            ->setBody(
                                '<!DOCTYPE html>'.
                                '<html lang="fr">'.
                                '<head>'.
                                '<title>Reparizy</title>'.
                                '<meta charset="utf-8">'.
                                '<meta name="viewport" content="width=device-width, initial-scale=1">'.
                                '<style>'.
                                '.bg-1 {background-color: #25698c;color: #ffffff;}'.
                                '.bg-2 {background-color: #fff;color: #000; border-bottom:1px #aaa solid; }'.
                                '.bg-3 {background-color: #ffffff;color: #555555;}'.
                                '.container-fluid {padding-top: 70px;padding-bottom: 70px;}'.
                                '.img-icon{width: 316px;padding-top: 7px;}'.
                                '.border-nop{ border: none;text-align: left;}'.
                                '.sizefooter{padding: 15px;text-align:center;} .red{color:red; font-weight:bold;}'.
                                '.text-center{text-align:center;}'.
                                '.btnconfig{ background-color: #25698c; border: none;color: white;padding: 15px 32px; text-align: center;text-decoration: none;display: inline-block;font-size: 16px;}'.
                                '</style>'.
                                '</head>'.
                                '<body>'.

                                '<nav class="bg-2 text-center sizefooter">'.
                                '<div style="text-align: center;">'.
                                '<img src="https://d13yacurqjgara.cloudfront.net/users/540920/screenshots/2360020/sans-titre---1_teaser.png" class="img-icon" >'.
                                '</div>'.
                                '</nav>'.

                                '<div class="container-fluid bg-3 text-center">'.
                                '<h3>INVITATION TREELLO !</h3>'.
                                '<p> Cher '.$prenom.', <br><br> Vous avez reçu une invitation a un projet sur Treello <br>'.
                                '<a class="btnconfig bg-1" href="http://localhost:8000/confirmation/'.$idInvite_.'/'.$idprojet.'" >Rejoindre le projet</a>'.
                                '</p>'.
                                '</div>'.
                                '<div class="bg-1 sizefooter">'.
                                '<p>A très bientôt, <br> L\'équipe Treello </p>'.
                                '</div>'.
                                '</body>'.
                                '</html>',
                                'text/html'
                            );
                            $this->get('mailer')->send($message);

                            $invite = new Invite();
                            $invite->setIdinvite($idInvite_);
                            $invite->setIdprojet($idprojet);
                            $invite->setEtat(0);
                            $em->persist($invite);
                            $em->flush();

                        $request->getSession()->getFlashBag()->add('success2', 'Invitation envoyé');

                        return $this->redirectToRoute('projects' , ['idprojet'=> $idprojet,'tasksid' => $idprojet,
                                'listsTasks' => $listsTasks,
                                'listsUnderTasks' => $listsUnderTasks ]);
                    }else{

                        $request->getSession()->getFlashBag()->add('error2', 'Echec de votre invitation - cet compte n\'est pas inscris ');
                        return $this->redirectToRoute('projects' , ['idprojet'=> $idprojet,'tasksid' => $idprojet,
                            'listsTasks' => $listsTasks,
                            'listsUnderTasks' => $listsUnderTasks ]);
                    }


                }else{

                    if($nameTasks != ""){
                        $tasks = new Tasks();
                        $tasks->setIdprojet($idprojet);
                        $tasks->setTitre($nameTasks);
                        $tasks->setDates($date_);
                        $em->persist($tasks);
                        $em->flush();

                        $request->getSession()->getFlashBag()->add('success', 'Vous avez bien ajouter votre projet');
                        return $this->redirectToRoute('projects' , ['idprojet'=> $idprojet,'tasksid' => $idprojet,
                            'listsTasks' => $listsTasks,
                            'listsUnderTasks' => $listsUnderTasks ]);
                    }else{
                        $request->getSession()->getFlashBag()->add('error_', 'l\'une des Values est vide');
                        return $this->redirectToRoute('projects' , ['idprojet'=> $idprojet,'tasksid' => $idprojet,
                            'listsTasks' => $listsTasks,
                            'listsUnderTasks' => $listsUnderTasks ]);
                    }

                }

            }

            return $this->render('UserBundle:Default:projects.html.twig', ['tasksid' => $idprojet, 'listsTasks' => $listsTasks, 'listsUnderTasks' => $listsUnderTasks]);
        }else{
            return $this->redirectToRoute('login');
        }


    }



    /**
     * @Route("/confirmation/{idinvite}/{idprojet}", name="confirmation")
     */
    public function confirmationAction(Request $request)
    {
        $idinvite = $request->get('idinvite');
        $idprojet =$request->get('idprojet');

        if($idinvite != "" && $idprojet != ""){
                $om = $this->getDoctrine()->getManager();
                $query = $om->createQuery(
                    'UPDATE UserBundle:Invite u
                            set u.etat = :etat
                            WHERE u.idinvite = :idinvite AND u.idprojet = :idprojet'
                )->setParameter('etat', 1)
                 ->setParameter('idinvite', $idinvite)
                ->setParameter('idprojet', $idprojet);
                $query->getResult();
        }

        return $this->render('UserBundle:Default:invite_.html.twig');
    }

}
