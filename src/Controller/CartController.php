<?php


namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


/**
 * @Route("/booking")
 */

class CartController extends AbstractController
{

    /**
     * Créer un Formulaire de contact
     * @Route ("/pay", name= "pay", methods={"GET|POST"})
     * ex. http://localhost:8000/booking/pay
     * @param Request $request
     * @return Response
     */
    public function register(Request $request, SluggerInterface $slugger): Response
    {

        # Création d'un nouveau user VIDE
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        # il peut valider le formulaire de paiement seulement si il est user, SINON redirectoRoute inscription puis redirectToRoute paiement

        # Création d'un Formulaire de Paiement
        $form = $this->createFormBuilder( $user )
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('address', TextType::class, [
                'label' => "Adresse"
            ])
            ->add('city', TextType::class, [
                'label' => "Ville"
            ])
            ->add('zipcode', NumberType::class, [
                'label' => "Code Postal"
            ])
            ->add('telephone', TelType::class, [
                'label' => "Téléphone",
            ])
            ->add('email', EmailType::class, [
                'label' => "E-mail"
            ])
            ->add('cardnumber', NumberType::class, [
                'label' => "Numéro de Carte",
            ])
            ->add('cardname', TextType::class, [
                'label' => "Titulaire de la carte",
            ])
            ->add('expireddate', NumberType::class, [
                'label' => "Date d'expiration",
            ])
            ->add('cvv', NumberType::class, [
                'label' => "CVV",
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Payer et finaliser",
            ])
            ->getForm();

        # Permet à Symfony de gérer les données saisies par l'utilisateur
        $form->handleRequest( $request );

        # Si le formulaire est soumis et valide => C'est comme en procédural quand on écrit if Post(empty) etc etc
        if($form->isSubmitted() && $form->isValid()) {


            # Insertion dans la BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            # Redirection
            return $this->redirectToRoute('index'); #alert Votre reservation a bien été effectué vous allez recevoir dans vos mail le récapitulatif de votre reservation
        }

        # Passer le formulaire à la vue
        return $this->render('default/pay.html.twig', [
            'form' => $form->createView()
        ]);
    }

}