<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use App\Form\EvenementTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Organisateur; 



final class EvenementController extends AbstractController
{
    #[Route('/evenement/create', name: 'evenement_create')]
    public function create(EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        $evenement = new Evenement();
        $evenement->setTitre("Salon Tech 2025");
        $evenement->setDate(new \DateTime('2025-07-20'));
        $evenement->setLieu("Tunis Expo");
        $evenement->setPlacesDisponibles(500);
        $evenement->setDescription("Salon international des technologies de demain.");
    
    
        $errors = $validator->validate($evenement);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }
    

        $em->persist($evenement);
    
    
        $em->flush();
    
        return new Response('Événement enregistré avec succès, ID: '.$evenement->getId());
    }
    #[Route('/evenement/{id<\d+>}', name: 'evenement_show')]
    public function show(EvenementRepository $evenementRepository, int $id): Response
    {
        $evenement = $evenementRepository->find($id);

        if (!$evenement) {
            throw $this->createNotFoundException('Événement non trouvé pour l\'ID '.$id);
        }

        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }
    #[Route('/evenement', name: 'evenement_index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $page = max(1, $request->query->getInt('page', 1)); 
        $limit = 5; 
        $offset = ($page - 1) * $limit;
    
    
        $query = $em->createQuery(
            'SELECT e FROM App\Entity\Evenement e ORDER BY e.date DESC'
        )
        ->setFirstResult($offset)
        ->setMaxResults($limit);
    
        $evenements = $query->getResult();
    
    
        $total = $em->createQuery(
            'SELECT COUNT(e.id) FROM App\Entity\Evenement e'
        )->getSingleScalarResult();
    
        $totalPages = ceil($total / $limit);
    
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
    #[Route('/evenement/generate', name: 'evenement_generate')]
public function generate(EntityManagerInterface $em): Response
{
    for ($i = 1; $i <= 23; $i++) {
        $evenement = new Evenement();
        $evenement->setTitre("Événement $i");
        $evenement->setDate(new \DateTime("+$i days"));
        $evenement->setLieu("Lieu $i");
        $evenement->setDescription("Description de l'événement numéro $i.");
        $evenement->setPlacesDisponibles(100);


        $organisateur = $em->getRepository(Organisateur::class)->find(1);
        if (!$organisateur) {
            dd('Organisateur non trouvé');
        } else {
            dd($organisateur);
        }
        $evenement->setOrganisateur($organisateur);

    

        $em->persist($evenement);
    }

    $em->flush();

    return new Response('23 événements générés avec succès.');
}


    }
