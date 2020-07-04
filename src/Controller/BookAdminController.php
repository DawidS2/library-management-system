<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Specimen;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookAdminController extends AbstractController
{
    /**
     * @var RequestStack
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
     * BookAdminController constructor.
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
     * List of all books
     *
     * @Route("admin/book", methods="GET", name="admin_book_index")
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function index(BookRepository $bookRepository): Response
    {
        $page = $this->request->query->getInt('page', 1);

        $books = $bookRepository->findAll();
        $books = $this->paginator->paginate(
            $books, $page, 10
        );

        return $this->render('book_admin/index.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * New book
     *
     * @Route("admin/book/new", methods="GET|POST", name="admin_book_new")
     * @return Response
     */
    public function new(): Response
    {
        $form = $this->createForm(BookType::class);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Book $data */
            $data = $form->getData();
            $numberOfSpecimen = $form->get('numberOfSpecimen')->getData();

            $this->entityManager->persist($data);

            /**
             * Create $i specimens of book
             */
            for ($i = 0; $i<$numberOfSpecimen; $i++) {
                $specimen = new Specimen();
                $specimen
                    ->setBook($data)
                    ->setForRent(true)
                ;
                $this->entityManager->persist($specimen);
            }

            $this->entityManager->flush();

            $this->addFlash('success', sprintf("Pomyślnie dodano rekord o ID: %d", $data->getId()));

            return $this->redirectToRoute('admin_book_index');
        }
        return $this->render('book_admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit book
     *
     * @Route("/admin/book/{id<\d+>}/edit", methods="GET|POST", name="admin_book_edit")
     * @param Book $book
     * @return Response
     */
    public function edit(Book $book): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', sprintf("Pomyślnie zmieniono rekord o ID: %d", $book->getId()));

            return $this->redirectToRoute('admin_book_index');
        }

        return $this->render('book_admin/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Details of book
     *
     * @Route("admin/book/{id<\d+>}", methods="GET", name="admin_book_show")
     * @param Book $book
     * @return Response
     */
    public function show(Book $book): Response
    {
        return $this->render('book_admin/show.html.twig', [
            'book' => $book,
        ]);
    }
}
