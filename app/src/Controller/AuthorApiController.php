<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use App\Controller\StoreApiController;

class AuthorApiController extends AbstractController
{
    /**
     * @Route("/author/all", name="author_show_all", methods={"GET"} )
     */
    public function showAll(): Response
    {
        $query = $this->getDoctrine()
            ->getRepository(Author::class)
            ->showAllAuthors();

        $authors = [];

        foreach ($query as $key => $value) {
            $authors[] = [
                'id' => $value->getId(),
                'name' => $value->getName()
            ];
        }

        return $this->json($authors);
    }

    /**
     * @Route("/author/find", name="author_find_by_name", methods={"POST"} )
     */
    public function findByName(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $author = $this->getDoctrine()
            ->getRepository(Author::class)
            ->findByName($data['name']);
        
        return $this->json($author);
    }

    /**
     * @param Request $request
     * @Route("/author/create", name="author_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $sc = new StoreApiController();
        $result = $sc->storeAuthor($data, $em);
        if ($result['status']) {
            return $this->json($result['id']);
        } else {
            return $this->json($result['errors']);
        }
    }
}
