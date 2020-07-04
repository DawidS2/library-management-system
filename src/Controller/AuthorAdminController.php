<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorAdminController extends AbstractController
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
     * AuthorAdminController constructor.
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
     * List of all authors
     *
     * @Route("admin/author", methods="GET", name="admin_author_index")
     * @param AuthorRepository $authorRepository
     * @return Response
     */
    public function index(AuthorRepository $authorRepository): Response
    {
        $page = $this->request->query->getInt('page', 1);

        $authors = $authorRepository->findAll();
        $authors = $this->paginator->paginate($authors, $page, 10);

        return $this->render('author_admin/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    /**
     * New author
     *
     * @Route("admin/author/new", methods="GET|POST", name="admin_author_new")
     */
    public function new(): Response
    {
        $form = $this->createForm(AuthorType::class);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Author $data */
            $data = $form->getData();

            $this->entityManager->persist($data);
            $this->entityManager->flush();

            $this->addFlash('success', sprintf("Pomyślnie dodano rekord o ID: %d", $data->getId()));

            return $this->redirectToRoute('admin_author_index');
        }

        return $this->render('author_admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit author
     *
     * @Route("admin/author/{id<\d+>}/edit", methods="GET|POST", name="admin_author_edit")
     * @param Author $author
     * @return Response
     */
    public function edit(Author $author): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', sprintf("Pomyślnie zmieniono rekord o ID: %d", $author->getId()));

            return $this->redirectToRoute('admin_author_index');
        }

        return $this->render('author_admin/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
