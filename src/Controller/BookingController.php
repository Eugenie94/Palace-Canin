<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Room;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                'label'  => "Renseignez une description",
                'attr' => ['class' => 'demande']
            ])
            ->add('image', FileType::class, [
                'label' => "Choisissez votre image d'illustration",
            ])
            ->add('price', NumberType::class, [
                'label' => "Renseigner un prix",
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Publier",
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
            $this->addFlash('success', 'Félicitations, votre chambre est en ligne.');


            # Redirection vers la nouvelle chambre
            return $this->redirectToRoute('default_category', [
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
     * Page Reservation
     * http://localhost:8000/reservation
     * @Route("/reservation", name="booking_reservation", methods={"GET"})
     * Le alias du dessus agira sur la fonction d'apres
     */
    public function reservation()
    {
        return $this->render('booking/reservation.html.twig');
    }



}


