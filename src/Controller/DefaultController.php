<?php

# DefaultController a utiliser pour toutes les pages principales
namespace App\Controller;

# Mettre curseur sur Response et import et le lien qui a en bas
use App\Entity\Category;
use App\Entity\Room;
use App\Repository\CategoryRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * Page d'Accueil
     * http://localhost:8000/
     */
    public function index()
    {

        # Récupérer depuis notre model (entité) les articles de la BDD.
        # $rooms = $this->getDoctrine()
           # ->getRepository(Room::class)
           # ->findAll();

        # On retourne au client une réponse HTTP.
        # return new Response("<h1>Page Accueil</h1>");
        return $this->render('default/index.html.twig');
    }

    /**
     * Page category : Affiche les catégories de chambre
     * http://localhost:8000/room
     * @Route("/room", name="default_room", methods={"GET"})
     */
    public function category(CategoryRepository $categoryRepository)
    {
        return $this->render('default/category.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }


    /* ____________________________________________________________________________________
    PAGES SERVICES
    ____________________________________________________________________________________ */


    /**
     * Page COMPLEXE : Affiche le service complexe
     * http://localhost:8000/complexe
     * @Route("/complexe", name="default_complexe", methods={"GET"})
     * le alias du dessus agira sur la fonction d'apres
     */
    public function service1(ServiceRepository $serviceRepository)
    {

        return $this->render('default/complexe.html.twig', [
            'service' => $serviceRepository->findOneBy(['name' => 'complexe'])
        ]);

    }

    /**
     * Page COMPLEXE : Affiche le service bien-être
     * http://localhost:8000/bien-etre
     * @Route("/bien-etre", name="default_bien-etre", methods={"GET"})
     * le alias du dessus agira sur la fonction d'apres
     */
    public function service2(ServiceRepository $serviceRepository)
    {

        return $this->render('default/bien-etre.html.twig', [
            'service2' => $serviceRepository->findOneBy(['name' => 'bienetre'])

        ]);

    }

    /**
     * Page COMPLEXE : Affiche le service toilettage
     * http://localhost:8000/toilettage
     * @Route("/toilettage", name="default_toilettage", methods={"GET"})
     * le alias du dessus agira sur la fonction d'apres
     */
    public function service3(ServiceRepository $serviceRepository)
    {

        return $this->render('default/toilettage.html.twig', [
            'service3' => $serviceRepository->findOneBy(['name' => 'toilettage'])

        ]);

    }

    /**
     * Page COMPLEXE : Affiche le service dressage
     * http://localhost:8000/dressage
     * @Route("/dressage", name="default_dressage", methods={"GET"})
     * le alias du dessus agira sur la fonction d'apres
     */
    public function service4(ServiceRepository $serviceRepository)
    {

        return $this->render('default/dressage.html.twig', [
            'service4' => $serviceRepository->findOneBy(['name' => 'dressage'])

        ]);

    }

    /**
     * Page COMPLEXE : Affiche le service transport animalier
     * http://localhost:8000/transport-animalier
     * @Route("/transport-animalier", name="default_transport-animalier", methods={"GET"})
     * le alias du dessus agira sur la fonction d'apres
     */
    public function service5(ServiceRepository $serviceRepository)
    {

        return $this->render('default/transport-animalier.html.twig', [
            'service5' => $serviceRepository->findOneBy(['name' => 'transportanimalier'])

        ]);

    }

    /**
     * Page COMPLEXE : Affiche le service boutique
     * http://localhost:8000/boutique
     * @Route("/boutique", name="default_boutique", methods={"GET"})
     * le alias du dessus agira sur la fonction d'apres
     */
    public function service6(ServiceRepository $serviceRepository)
    {

        return $this->render('default/boutique.html.twig', [
            'service6' => $serviceRepository->findOneBy(['name' => 'boutique'])

        ]);

    }




}

