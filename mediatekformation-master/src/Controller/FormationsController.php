<?php
namespace App\Controller;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of FormationsController
 *
 * @author emds
 */
class FormationsController extends AbstractController {
    
    private const PAGEFORMATIONS = "pages/formations.html.twig";

    /**
     *
     * @var FormationRepository
     */
    private $repository;

    /**
     * le contructeur du contoleur
     * @param FormationRepository $repository
     */
    function __construct(FormationRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * permet de créer la vue
     * @Route("/formations", name="formations")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->repository->findAll();
        return $this->render(self::PAGEFORMATIONS, [
            'formations' => $formations
        ]);
    }
    
    /**
     * permet de trier sur les formations dans lordre choisi
     * @Route("/formations/tri/{champ}/{ordre}", name="formations.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        $formations = $this->repository->findAllOrderBy($champ, $ordre);
        return $this->render(self::PAGEFORMATIONS, [
           'formations' => $formations
        ]);
    }
    /**
     * permet de trier sur le niveau
     * @Route("/formations/triNiveau/{valeur}", name="formations.niveau")
     * @param type $valeur
     * @return Response
     */
    public function sortNiveau($valeur): Response{
        $formations = $this->repository->findAllOrderBy('title', 'DESC');
        $formation  = new Formation();
        $lesFormations = array();
        foreach($formations as $formation){
            if($formation->getIdNiveau()->getNiveauDifficulte() == $valeur){
                array_push($lesFormations, $formation);
            };
        }; 
        return $this->render(self::PAGEFORMATIONS, [
           'formations' => $lesFormations
        ]);
    }
    
        
    /**
     * permet de récupérer toutes les formations qui contiennent la valeur cherché
     * @Route("/formations/recherche/{champ}", name="formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContain($champ, Request $request): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){
            $valeur = $request->get("recherche");
            $formations = $this->repository->findByContainValue($champ, $valeur);
            return $this->render(self::PAGEFORMATIONS, [
                'formations' => $formations
            ]);
        }
        return $this->redirectToRoute("formations");
    }  
    
    /**
     * pemret de récuperer une seul formation par id
     * @Route("/formations/formation/{id}", name="formations.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $formation = $this->repository->find($id);
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation
        ]);        
    }    
}
