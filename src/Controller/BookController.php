<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\SearchBookType;
use App\Repository\BookRepository;
use App\Repository\RentRepository;
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
     * BookController constructor.
     * @param RequestStack $request
     */
    public function __construct(RequestStack $request)
    {
        $this->request = $request->getCurrentRequest();
    }


    /**
     * @Route("/book", methods="GET", name="book_index")
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function index(BookRepository $bookRepository): Response
    {
        $query = $this->request->get('q', '');
        $field = $this->request->get('field', 'title');

        switch ($field) {
            case 'isbn' :
                $books = $bookRepository->findByField($query, 'isbn');
                break;
            case 'title' :
                $books = $bookRepository->findByField($query, 'title');
                break;
            default:
                $books = $bookRepository->findAll();
        }

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'query' => $query,
        ]);
    }

    /**
     * @Route("/book/{id<\d+>}")
     * @param Book $book
     * @param RentRepository $rentRepository
     * @return Response
     */
    public function show(Book $book, RentRepository $rentRepository): Response
    {


        return $this->render('book/show.html.twig', [
            'book' => $book
        ]);
    }
}
