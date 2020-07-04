<?php

namespace App\Controller;

use App\Repository\RentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RentController extends AbstractController
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * RentController constructor.
     * @param RequestStack $request
     * @param PaginatorInterface $paginator
     */
    public function __construct(RequestStack $request, PaginatorInterface $paginator)
    {
        $this->request = $request->getCurrentRequest();
        $this->paginator = $paginator;
    }


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
        $page = $this->request->query->getInt('page', 1);

        $activeRents = $rentRepository->findBy(['reader' => $this->getUser(), 'isReturned' => false]);
        $activeRents = $this->paginator->paginate($activeRents, $page, 10);

        return $this->render('rent/index.html.twig', [
            'activeRents' => $activeRents,
        ]);
    }
}
