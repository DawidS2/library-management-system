<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublisherAdminController extends AbstractController
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
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * PublisherAdminController constructor.
     * @param RequestStack $request
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RequestStack $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $this->request = $request->getCurrentRequest();
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }


    /**
     * @Route("/admin/publisher", methods="GET|POST", name="admin_publisher_index")
     * @param PublisherRepository $publisherRepository
     * @return Response
     */
    public function index(PublisherRepository $publisherRepository)
    {
        $page = $this->request->query->getInt('page', 1);

        $publishers = $publisherRepository->findAll();
        $publishers = $this->paginator->paginate($publishers, $page, 10);

        return $this->render('publisher_admin/index.html.twig', [
            'publishers' => $publishers,
        ]);
    }

    /**
     * @Route("/admin/publisher/new", methods="GET|POST", name="admin_publisher_new")
     */
    public function new()
    {
        $form = $this->createForm(PublisherType::class);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->entityManager->persist($data);
            $this->entityManager->flush();

            $this->addFlash('success', 'Poprawnie dodano wydawcę');

            return $this->redirectToRoute('admin_publisher_index');
        }

        return $this->render('publisher_admin/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/publisher/{id<\d+>}/edit", methods="GET|POST", name="admin_publisher_edit")
     * @param Publisher $publisher
     */
    public function edit(Publisher $publisher)
    {
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Pomyślnie zmieniono wydawcę');

            return $this->redirectToRoute('admin_publisher_index');
        }
        return $this->render('publisher_admin/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
