<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Category;
use App\Entity\Room;

use App\Repository\BookingRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class BookingController extends AbstractController
{
    /**
     * Créer une chambre via un Formulaire
     * @Route ("/create", name="booking_create", methods={"GET|POST"})
     * ex. http://localhost:8000/create
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function create(Request $request, SluggerInterface $slugger): Response #Slug et alias c'est la même chose
    {

        # Création d'un nouvel article VIDE
        $room = new Room();
        $room->setCreatedAt(new \DateTime());
        $room->setUpdatedAt(new \DateTime());

        # Attribution d'un Auteur à un article
        # Remplacer par l'utilisateur connecté
        $room->setUser( $this->getUser() );


        # Création d'un Formulaire de Création de chambre
        $form = $this->createFormBuilder( $room )
            ->add('name', TextType::class, [
                'label' => "Nom de la chambre"
            ])
            ->add('category', EntityType::class, [
                'label' => "Choisissez une catégorie",
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
            ])
            ->add('image', FileType::class, [
                'label' => "Choisissez votre image d'illustration",
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Publier ma chambre",
            ])
            ->getForm();

        # Permet à Symfony de gérer les données saisies par l'utilisateur
        $form->handleRequest( $request );

        # Si le formulaire est soumis et valide => C'est comme en procédural quand on écrit if Post(empty)
        if($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Une erreur est survenue durant le chargement de votre image.');
                }

                $room->setImage($newFilename);

            } // endif image


            # Génération de l'alias à partir du titre
            $room->setAlias(
                $slugger->slug(
                    $room->getName()
                )
            );


            # Insertion dans la BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($room);
            $em->flush();


            # Notification de confirmation
            $this->addFlash('success', 'Félicitation, votre chambre est en ligne.');


            # Redirection vers la nouvelle chambre
            return $this->redirectToRoute('booking_create', [
                'category' => $room->getCategory()->getAlias(),
                'alias' => $room->getAlias(),
                'id' => $room->getId()
            ]);

        }

        # Passer le formulaire à la vue
        return $this->render('booking/create.html.twig', [
            'form' => $form->createView()
        ]);
    }



    /**
     * Page COMPLEXE : Affiche le service boutique
     * http://localhost:8000/boutique
     * @Route("/boutique", name="default_boutique", methods={"GET"})
     * le alias du dessus agira sur la fonction d'apres
     */
    public function booking(BookingRepository $serviceRepository)
    {

        return $this->render('booking/reservation.html.twig', [
            'reservation' => $serviceRepository->findOneBy(['name' => 'reservation'])

        ]);

    }

    /**
     * Page Reservation
     * @Route ("/reservation", name="booking_reservation", methods={"GET|POST"})
     * ex. http://localhost:8000/reservation
     * @param Request $request
     * @return Response
     */
    public function reservation(Request $request, SluggerInterface $slugger): Response
    {
        # Création d'un nouveau user VIDE
        $booking = new Booking();

        # Création d'un Formulaire
        $form = $this->createFormBuilder( $booking )
            ->add('checkIn', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('checkOut', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('days', EmailType::class, [
                'label' => "E-mail"
            ])
            ->add('status', TelType::class, [
                'label' => "Téléphone",
            ])
            ->add('total', TextType::class, [
                'label' => "Sujet",
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Reservez",
            ])
            ->getForm();

        # Permet à Symfony de gérer les données saisies par l'utilisateur
        $form->handleRequest( $request );

        # Si le formulaire est soumis et valide => C'est comme en procédural quand on écrit if Post(empty) etc etc
        if($form->isSubmitted() && $form->isValid()) {


            # Insertion dans la BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $em->flush();


            # Redirection
            return $this->redirectToRoute('booking_reservation'); #alert message a bien été envoyé
        }

        # Passer le formulaire à la vue
        return $this->render('booking/reservation.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
