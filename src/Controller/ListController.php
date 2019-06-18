<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Entity\Image;
use App\Entity\User;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use App\Form\ProduitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;


class ListController extends AbstractController
{
    
    public $produit;
    public $categorie;

    public function __construct(ProduitRepository $produit, CategorieRepository $categorie)
    {
        $this->produit = $produit;
        $this->categorie = $categorie;
    }
    
    /**
     * @Route("/", name="index")
     */
    
    public function index()
    {
        $produits = $this->produit -> findAll();

        $categories = $this->categorie -> findAll();


        return $this->render('pages/index.html.twig', [
            'produits' => $produits,
            'categories' => $categories
        ]);
    }


    /**
     * @Route("/produit/{id}", name="details")
     */

    public function details($id)
    {
        $produit = $this->produit-> find($id);

        return $this->render('pages/produit.html.twig', [
            'produit' => $produit,
        ]);
    }


    /**
     * @Route("/recherche", name="recherche")
     */

    public function recherche(Request $request)
    {

        $recherche = $request->query->get('Search');
        
        $produit = $this->produit ->findByName( $recherche );

        if($produit == NULL)
        {   
            return $this->render('pages/notFound.html.twig', [
                'recherche' => $recherche
            ]);
        }

        else

        {
            return $this->render('pages/recherche.html.twig', [
                'produit' => $produit,
            ]);

        }
    }


     /**
     * @Route("/categorie/{id}", name="tri")
     */

    public function tri(Categorie $id)
    {

        $categories = $this->categorie-> findAll();

        //$categories2 = $this->categorie->find($id);

        $produits = $this->produit->findBy(['categorie' => $id]);

        return $this->render('pages/index.html.twig', [
            'produits' => $produits,
            'categories' => $categories
        ]);
    }

    
}
