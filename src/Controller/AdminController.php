<?php

namespace App\Controller;

use App\Entity\Organisateur;
use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function index(ParticipantRepository $participantRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $participants = $participantRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'participants' => $participants,
        ]);
    }

    #[Route('/promote/{id}', name: 'admin_promote')]
    public function promoteToOrganisateur(Participant $participant, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $organisateur = new Organisateur();
        $organisateur->setNom($participant->getNom());
        $organisateur->setEmail($participant->getUserIdentifier());
        $organisateur->setPassword($participant->getPassword());
        $organisateur->setTelephone($participant->getTelephone());

        $em->persist($organisateur);
        $em->remove($participant);
        $em->flush();

        $this->addFlash('success', 'Participant promu avec succès en organisateur.');

        return $this->redirectToRoute('admin_dashboard');
    }
}