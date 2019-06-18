<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\PanierType;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use App\Repository\PanierRepository;
use App\Repository\PanierProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @IsGranted("ROLE_USER")
 */

class UtilisateurController extends AbstractController
{

    public $produit;
    public $categorie;
    public $session;
    public $panier;
    public $panierProduit;

    public function __construct(ProduitRepository $produit, CategorieRepository $categorie, SessionInterface $session, PanierRepository $panier, PanierProduitRepository $panierProduit)
    {
        $this->produit = $produit;
        $this->categorie = $categorie;
        $this->session = $session;
        $this->panier = $panier;
        $this->panierProduit = $panierProduit;
    }
    
    /**
     * @Route("/add", name="add")
     */

    public function add(Request $request)
    {   

        $em = $this->getDoctrine()->getManager();

        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

              $produit->setDate(new \DateTime());
              $produit-> setUtilisateur($this->getUser());
              $em->persist($produit);
              $em->flush();
              $this->addFlash('success', 'Votre produit à été correctement ajoué aux articles.');

             return $this->redirectToRoute('index');
        }

        return $this->render('pages/ajout.html.twig', ['form'=>$form->createView()]);
    }


    /**
     * @Route("/historique", name="historique")
     */ 

    public function historique()
    {
        $produits = $this->produit ->findBy(['utilisateur' => $this->getUser()]);

        return $this->render('pages/historique.html.twig', [
            'produits' => $produits,
        ]);
    } 


    /** 
     * @Route("/update/{id}", name="update")
     */

     public function update(Request $request, $id)
     {

        $em = $this->getDoctrine()->getManager();

        $produit = $this->produit->find($id);

        $form = $this->createForm(ProduitType::class, $produit);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

              $produit->setDate(new \DateTime());
              $em->flush();
            
              $this->addFlash('success', 'Votre produit à été correctement modifié.');

             return $this->redirectToRoute('index');
        }

        return $this->render('pages/update.html.twig', [
            'form'=>$form->createView(),
            'produit' => $produit
            ]);
     }


     /**
      * @Route("/delete/{id}", name="delete")
      */

      public function delete($id)
      {
        $em = $this->getDoctrine()->getManager();

        $produit = $this->produit->find($id);

        $em->remove($produit);

        $em-> flush();
        
        $this->addFlash('danger', 'Votre produit à été correctement supprimé.');

        return $this->redirectToRoute('historique');
      }

      /**
       * @Route("/commandes", name="commandes")
       */

       public function commandes()
       {
        
         $commandes = $this->panier->findBy(['user' => $this->getUser()]);

         return $this->render('pages/commandes.html.twig', [
                    'infos' => $commandes
            ]);
       }

       /**
       * @Route("/detailsCommandes/{id}", name="detailsCommandes")
       */

      public function detailsCommandes($id)
      {

       $d = $this->panier->find($id);
        $commandes = $this->panierProduit->findBy(['panier' => $d]);
        return $this->render('pages/detailsCommandes.html.twig', [
                   'infos' => $commandes
           ]);
      }

   

        
     
}
