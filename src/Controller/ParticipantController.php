<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ParticipantController extends AbstractController
{
    #[Route('/evenements', name: 'evenement_index')]
    public function index(EvenementRepository $evenementRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PARTICIPANT');
        $evenements = $evenementRepository->findAll();

        return $this->render('participant/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }
}