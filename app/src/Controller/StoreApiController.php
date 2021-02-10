<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StoreApiController extends AbstractController
{
    /**
     * Сохранение книги
     * 
     * Автор может быть передан как число или строка, которая может быть  
     * преобразована в число, а так же как название автора.
     * 
     * @param  array $data набор данных для сохранения
     * @param  mixed $em EntityManager из вызывающего метода класса
     * @return array
     */
    public function storeBook($data, $em)
    {
        try {
            // Простейшая проверка на наличие данных в наборе
            if (!$data['nameus'] || !$data['nameru'] || !$data['author']) {
                throw new \Exception();
            }

            $authorId = $this->getAuthorId($data['author'], $em);

            if ($authorId === 0) {
                throw new \Exception();
            }

            $author = $em->getRepository(Author::class)
                ->findById($authorId);

            $book = new Book();
            $book->setName("{$data['nameus']}|{$data['nameru']}");
            $book->setAuthor($author);
    
            $em->persist($book);
            $em->flush();
            

            $result = [
                'status' => 200,
                'success' => "Book added successfully",
                'id' => $book->getId()
            ];
            return $result;
        } catch (\Exception $e) {
            $result = [
                'status' => 422,
                'errors' => $e->getMessage(),
            ];
            return $result;
        }
    }

    /**
     * Сохранение автора
     */
    public function storeAuthor($data, $em)
    {
        try {
            // Простейшая проверка на наличие данных в наборе
            if (!$data['name']) {
                throw new \Exception();
            }

            $author = new Author();
            $author->setName($data['name']);

            $em->persist($author);
            $em->flush();

            $result = [
                'status' => 200,
                'success' => "Author added successfully",
                'id' => $author->getId()
            ];
            return $result;
        } catch (\Exception $e) {
            $result = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return $result;
        }
    }

    /**
     * Возвращает идентификатор преобразуя переданное  
     * значение в число, а если передана строка с именем,  
     * то проверяет наличие автора в базе. Если автор найден,  
     * то возвращает его идентификатор, иначе сохраняет  
     * автора и возвращает его идентификатор
     * @param  int|string $author
     * @param  $em
     * @return int
     */
    private function getAuthorId($author, $em)
    {
        if (is_numeric($author)) {
            return intval($author);
        } else {
            $query = $em->getRepository(Author::class)
                ->findByName($author);
            if (count($query) === 1) {
                return $query[0]['id'];
            } else {
                $data = ['name' => $author];
                $result = $this->storeAuthor($data, $em);
                return ($result['status'] === 200) ? $result['id'] : 0;
            }
        }
    }
}
