<?php


namespace App\Controller;


use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class CartController extends AbstractController
{

    /**
     * Page Panier
     * @Route ("/panier", name="cart_paiement", methods={"GET|POST"})
     * ex. http://localhost:8000/paiement
     */
    public function index()
    {
        return $this->render('cart/paiement.html.twig', []);

    }

    /**
     * @Route("panier/add{id}", name="cart_add")
    */
    public function add($id, Request $request) {
        $session = $request->getSession();
        $panier = $session->get('panier', []);

        $panier[$id] = 1;

        $session->set('panier', $panier);

        dd($session->get('panier'));


    }

}