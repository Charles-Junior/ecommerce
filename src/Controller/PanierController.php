<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Entity\Panier;
use App\Entity\PanierProduit;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @IsGranted("ROLE_USER")
 */
class PanierController extends AbstractController
{
    
    public $produit;
    public $categorie;
    public $session;

    public function __construct(ProduitRepository $produit, CategorieRepository $categorie, SessionInterface $session)
    {
        $this->produit = $produit;
        $this->categorie = $categorie;
        $this->session = $session;
    }

    /**
     * @Route("/panier", name="panier")
     */
    
     public function panier()
     {
         
        if(!$this->session->has('panier') || empty($this->session->get('panier')))
       {
           $panier = "";
           $infos = "Aucun article dans votre panier";
       }
       else
       {
            $panier = $this->session->get('panier');

            $infos = $this->produit->findByKey(array_keys($panier));
        }

        $totalHt = 0;
        $totalTtc = 0;
        $tva = 0;

        return $this->render('pages/panier.html.twig', [
            'infos' => $infos,
            'quantite' => $panier,
            'totalHt' => $totalHt,
            'totalTtc' => $totalTtc,
            'tva' => $tva
                 ]);
     }


    /**
     * @Route("/panier/add/{id}", name="addPanier")
     */

    public function addPanier(Request $request, $id)
    { 
      
       if(!$this->session->has('panier'))
       {
       
         $this->session->set('panier',[]);
    
       }

       $quantite = $request->request->get('qte');

       $panier = $this->session->get('panier');
      
       if (array_key_exists($id, $panier))
       {
            if($quantite == NULL && $panier[$id] != NULL)
            {
                $quantite = ++$panier[$id];
                
            }

            /*elseif($panier[$id] != NULL)
            {
                $quantite = $quantite;
            }*/

            elseif ($quantite == NULL) 
            {
                $quantite = 1;
            }

       }

       else
       {
            if($quantite == NULL)
            {
                $quantite = 1;
            }
       }
            


        $panier[$id] = $quantite;
        
        $this->session->set('panier',$panier);

        $this->addFlash('success', 'Produit àjouté dans votre panier.');

        return $this->redirectToRoute('panier');
       }


    /**
     * @Route("/deletePanier/{id}", name="deletePanier")
     */

    public function deletePanier($id)
    { 

       $panier = $this->session->get('panier');

       if (array_key_exists($id, $panier))
        {
            unset($panier[$id]);

            $this->session->set('panier', $panier);
        }       
       
       $this->addFlash('danger', 'Produit supprimé de votre panier ');

       return $this->redirectToRoute('panier');

       }


       /**
        *  @Route("/pushPanier", name="pushPanier")
        */
       
        public function pushPanier()
        {
            
            if(!$this->session->has('panier') || empty($this->session->get('panier')))
            {  
                $this->addFlash('danger', 'Votre panier est vide. Veuillez ajouter des articles dans votre panier.');
            }

            else   
            {

                $em = $this->getDoctrine()->getManager();
                $panier = new Panier();

                $panier->setDate(new \DateTime());
                $panier->setUser($this->getUser());

                $sessionPanier = $this->session->get('panier');

                foreach($sessionPanier as $cle => $sP)
                {
                    $panierProduit = new PanierProduit();

                    $panierProduit->setPanier($panier);

                    $panierProduit->setQuantite($sP);

                    $infos = $this->produit->find($cle);
                    $panierProduit->setProduit($infos);
                    
                    $em->persist($panierProduit);
                }

                $em->flush();

                $this->session->remove('panier');

                $this->addFlash('success', 'Votre commande est validée. Merci d\'avoir utilisé nos services ');
            }    

                return $this->redirectToRoute('index');
        }


}
