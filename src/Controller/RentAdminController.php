<?php

namespace App\Controller;

use App\Entity\Rent;
use App\Form\Model\RentFormModel;
use App\Form\RentType;
use App\Repository\BookRepository;
use App\Repository\RentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RentAdminController extends AbstractController
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * RentController constructor.
     * @param RequestStack $request
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(RequestStack $request, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
    {
        $this->request = $request->getCurrentRequest();
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Add new rent
     *
     * @Route("/admin/rent/new", methods="GET|POST", name="admin_rent_new")
     */
    public function new()
    {
        $form = $this->createForm(RentType::class, null, [
            'attr' => [
                'data-url' => $this->urlGenerator->generate('admin_rent_get_speciments_input')
            ]
        ]);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Rent $rent */
            $rent = $form->getData();
            $rent
                ->setIsReturned(false)
                ->setRentAt(new DateTime())
                ->setRentTo(new DateTime('now + 30 days'))
                ;

            $this->entityManager->persist($rent);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_rent_edit', ['id' => $rent->getId()]);

        }
        return $this->render('rent_admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit rent
     *
     * @Route("admin/rent/{id<\d+>}/edit", methods="GET|POST", name="admin_rent_edit")
     * @param Rent $rent
     * @return Response
     */
    public function edit(Rent $rent)
    {
        $rentFormModel = new RentFormModel();
        $rentFormModel->Specimen = $rent->getSpecimen();
        $rentFormModel->reader = $rent->getReader();

        $form = $this->createForm(RentType::class, $rent, [
            'attr' => [
                'data-url' => $this->urlGenerator->generate('admin_rent_get_speciments_input')
            ]
        ]);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
        }

        return $this->render('rent_admin/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Render specimen field for book
     *
     * @Route("admin/rent/get-specimens-input", methods="POST", name="admin_rent_get_speciments_input")
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function getSpecimensInput(BookRepository $bookRepository)
    {
        $isbn = $this->request->request->getInt('isbn');
        // Find book by ISBN
        $book = $bookRepository->findOneBy(['isbn' => $isbn]);
        // If book exists and have specimen, get one specimen
        if ($book && $book->getSpecimens()) {
            $specimen = $book->getSpecimens()[0];
        }else{
            $specimen = null;
        }
        // Create new Rent object and set specimen
        $rent = new Rent();
        $rent->setSpecimen($specimen);

        $form = $this->createForm(RentType::class, $rent);

        return $this->render('rent_admin/_book_specimen_input.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Get all active rents which are not returned
     *
     * @Route("admin/rent/index/active", methods="GET", name="admin_rent_index_active")
     * @param RentRepository $rentRepository
     * @return Response
     */
    public function activeRentsIndex(RentRepository $rentRepository)
    {
        $rents = $rentRepository->findBy(['isReturned' => false]);

        return $this->render("rent_admin/index.html.twig", [
            'activeRents' => $rents,
        ]);
    }
}
