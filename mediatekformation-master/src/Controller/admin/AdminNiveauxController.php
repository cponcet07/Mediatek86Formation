<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\admin;

use App\Entity\Niveau;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminNiveauxController extends AbstractController{
    private const PAGEFORMATIONS = "admin/admin.niveaux.html.twig";

    /**
     *
     * @var NiveauRepository
     */
    private $repository;
    /**
     * 
     * @var EntityManagerInterface
     */
    private $om;
    /**
     * le constructeur du controleur
     * @param NiveauRepository $repository
     */
    function __construct(NiveauRepository $repository, EntityManagerInterface $om) {
        $this->repository = $repository;
        $this->om = $om;
    }

    /**
     * Permet d'ouvrir la vue
     * @Route("/admin/niveaux", name="admin.niveaux")
     * @return Response
     */
    public function index(): Response{
        $niveaux = $this->repository->findAll();
        return $this->render(self::PAGEFORMATIONS, [
            'niveaux' => $niveaux
        ]);
    }
    /**
     * permet de supprimer un niveau
     * @Route("/admin/niveau/suppr/{id}", name="admin.niveau.suppr")
     * @param Niveau $niveau
     * @return Response
     */
    public function suppr(Niveau $niveau): Response{
        if($niveau->getFormations()->count() == 0){
            $this->om->remove($niveau);
            $this->om->flush();
        }
        return $this->redirectToRoute('admin.niveaux');
    }
    
    /**
     * permet d'ajouter un niveau
     * @Route("/admin/niveaux/ajout", name="admin.niveau.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajoutNiveau(Request $request): Response{
        if($this->isCsrfTokenValid('token_ajouter', $request->get('_token'))){
            if($request != ""){
                $niveau = new Niveau();
                $valeur = $request->get("ajouter");
                $niveau->setNiveauDifficulte($valeur);
                $this->om->persist($niveau);
                $this->om->flush();
            }
        }
        return $this->redirectToRoute("admin.niveaux");
    }
    
}
