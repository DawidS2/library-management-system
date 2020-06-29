<?php

namespace App\Controller;

use App\Repository\RentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RentController extends AbstractController
{
    /**
     * List of all active rents
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/rent", methods="GET", name="rent_index_active")
     * @param RentRepository $rentRepository
     * @return Response
     */
    public function indexActive(RentRepository $rentRepository): Response
    {
        $activeRents = $rentRepository->findBy(['reader' => $this->getUser(), 'isReturned' => false]);

        return $this->render('rent/index.html.twig', [
            'activeRents' => $activeRents,
        ]);
    }
}
