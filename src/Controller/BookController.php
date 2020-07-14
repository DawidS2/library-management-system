<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\SearchBookType;
use App\Repository\BookRepository;
use App\Repository\RentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
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
     * BookController constructor.
     * @param RequestStack $request
     * @param PaginatorInterface $paginator
     */
    public function __construct(RequestStack $request, PaginatorInterface $paginator)
    {
        $this->request = $request->getCurrentRequest();
        $this->paginator = $paginator;
    }


    /**
     * List of books
     *
     * @Route("/", methods="GET", name="book_index")
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function index(BookRepository $bookRepository): Response
    {
        $page = $this->request->query->getInt('page', 1);
        $query = $this->request->get('q', '');
//        dd($bookRepository->findSpecimensByBookId(7));

        $books = $bookRepository->findAllWithSearch($query);

        $books = $this->paginator->paginate($books, $page, 10);

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'query' => $query,
        ]);
    }

    /**
     * SHow book details
     *
     * @Route("/book/{id<\d+>}")
     * @param int $id
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function show(int $id, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->findBookById($id);
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
}
