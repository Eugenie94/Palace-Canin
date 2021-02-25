<?php

# DefaultController a utiliser pour toutes les pages principales
namespace App\Controller;

# Mettre curseur sur Response et import et le lien qui a en bas
use App\Entity\Category;
use App\Entity\Room;
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
        $rooms = $this->getDoctrine()
            ->getRepository(Room::class)
            ->findAll();

        # On retourne au client une réponse HTTP.
        # return new Response("<h1>Page Accueil</h1>");
        return $this->render('default/index.html.twig');
    }

    /**
     * Page category : Affiche les catégories de chambre
     * http://localhost:8000/chambres
     * @Route("/{alias}", name="default_categor", methods={"GET"})
     */
    public function category(Category $category)
    {
        return $this->render('default/category.html.twig', [
            'category' => $category
        ]);
    }


    /**
     * Page afficher page complexe
     * http://localhost:8000/service/complexe
     * @Route("/{category}/{alias}.html", name="service_complexe", methods={"GET"})
     * le alias du dessus agira sur la fonction d'apres
     */
    public function service( $service)
    {
        # return new Response("<h1>PAGE ARTICLE : $alias - $id</h1>");
        return $this->render('service/complexe.html.twig', [
            'service' => $service
        ]);
    }

}