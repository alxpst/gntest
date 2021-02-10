<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookApiController extends AbstractController
{
    /**
     * Retrive all instanses of Book
     * @Route("/book/all", name="book_show_all")
     */
    public function showAll(): Response
    {
        $query = $this->getDoctrine()
            ->getRepository(Book::class)
            ->showAllBooks();

        $books = [];

        foreach ($query as $key => $value) {
            $books[] = $value->getName();
        }

        return $this->json($books);
    }

    /**
     * Retrive one instanse of Book
     * @Route("/{_locale}/book/{id}", name="book_show")
     */
    public function show(Request $request, $id): Response
    {
        $locale = $request->getLocale();
        
        $query = $this->getDoctrine()
            ->getRepository(Book::class)
            ->find($id);

        $book = explode('|', $query->getName())[($locale === 'en') ? 0 : 1];

        return $this->json($book);
    }

    /**
     * @Route("/api/book/create", name="api_book_create")
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $sc = new StoreApiController();
        $result = $sc->storeBook($data, $em);
        if ($result['status'] === 200) {
            return $this->json($result['id']);
        } else {
            return $this->json($result['errors']);
        }
    }

    /**
     * @Route("/book/search/{text}", name="book_search_by_name")
     */
    public function searchByName($text)
    {
        $query = $this->getDoctrine()
            ->getRepository(Book::class)
            ->searchByName($text);

        $books = [];

        foreach ($query as $key => $value) {
            $books[] = $value;
        }

        return $this->json($books);
    }
}
