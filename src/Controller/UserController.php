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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


/**
 * @Route("/user")
 */

class UserController extends AbstractController
{

    /**
     * Créer un Formulaire d'inscription
     * @Route ("/register", name="user_register", methods={"GET|POST"})
     * ex. http://localhost:8000/user/register
     * @param Request $request
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): Response

    {
        # Création d'un nouveau user VIDE
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        # $user = new User(); A VOIR COMMENT ON PEUT AUSSI CREER DES ADMIN VIA FORMULAIRE OU PAS
        # $user->setRoles(['ROLE_ADMIN']);

        # Création d'un Formulaire d'Inscription
        $form = $this->createFormBuilder( $user )
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('dog_name', TextType::class, [
                'label' => 'Nom du chien',
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
            ->add('email', EmailType::class, [
                'label' => "E-mail"
            ])
            ->add('password', PasswordType::class, [
                'label' => "Mot de passe",
            ])
            ->add('telephone', TelType::class, [
                'label' => "Téléphone",
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Inscription",
            ])
            ->getForm();

        # Permet à Symfony de gérer les données saisies par l'utilisateur
        $form->handleRequest( $request );

        # Si le formulaire est soumis et valide => C'est comme en procédural quand on écrit if Post(empty) etc etc
        if($form->isSubmitted() && $form->isValid()) {

            # Encodage du mot de passe
            $user->setPassword(
                $encoder->encodePassword(
                    $user, $user->getPassword()
                )
            );

            # Insertion dans la BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            # Redirection
            return $this->redirectToRoute('index');
        }

        # Passer le formulaire à la vue
        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

}