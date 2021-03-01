<?php


namespace App\Controller;

use App\Entity\Contact;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/user")
 */

class ContactController extends AbstractController
{

    /**
     * Page Contact
     * @Route ("/contact", name="user_contact", methods={"GET|POST"})
     * ex. http://localhost:8000/user/contact
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        # Création d'un nouveau user VIDE
        $contact = new Contact();

        # Création d'un Formulaire
        $form = $this->createFormBuilder( $contact )
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('email', EmailType::class, [
                'label' => "E-mail"
            ])
            ->add('telephone', TelType::class, [
                'label' => "Téléphone",
            ])
            ->add('subject', TextType::class, [
                'label' => "Sujet",
            ])
            ->add('message', TextType::class, [
                'label' => "Message",
                'attr' => ['class' => 'demande']
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider",
            ])
            ->getForm();

        # Permet à Symfony de gérer les données saisies par l'utilisateur
        $form->handleRequest( $request );

        # Si le formulaire est soumis et valide => C'est comme en procédural quand on écrit if Post(empty) etc etc
        if($form->isSubmitted() && $form->isValid()) {


            $contact->setCreatedAt(new \DateTime);

            # Insertion dans la BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();


            # Notification de confirmation
            $this->addFlash('success', 'Félicitations, votre message nous a bien ete transmis.');


            # Redirection
            # TODO faire redirect to route vers la page PROFIL qu'on va creer apres
            return $this->redirectToRoute('user_contact'); #alert message a bien été envoyé
        }


        # Passer le formulaire à la vue
        return $this->render('user/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}