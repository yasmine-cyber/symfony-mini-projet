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
    
        // ✅ Validate before saving
        $errors = $validator->validate($evenement);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }
    
        // ✅ Persist (prepare for saving)
        $em->persist($evenement);
    
        // ✅ Flush (actually save to DB)
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
    public function index(EvenementRepository $evenementRepository): Response
    {
        $evenements = $evenementRepository->findAll();

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/evenement/new', name: 'evenement_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementTypeForm::class, $evenement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('evenement_success'); // À créer ou adapter
        }

        return $this->render('evenement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    }
