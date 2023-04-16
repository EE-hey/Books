<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\AuthorRepository;
use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\BookRepository;


class AuthorController extends AbstractController
{
    #[Route('/api/authors', name: 'app_author', methods: ['GET'])]
    public function getAllAuthor(AuthorRepository $authorRepository, SerializerInterface $serializer ): JsonResponse
    {

        $author = $authorRepository->findAll();
        $jsonAuthorList = $serializer->serialize($author, 'json', ['groups' => 'getAuthor']);
        return new JsonResponse($jsonAuthorList, Response::HTTP_OK, ['accept' => 'json'], true);


    }

    #[Route('/api/authors/{id}', name: 'author-one', methods: ['GET'])]
    public function getAuthor(Author $author, SerializerInterface $serializer)
    {
        $json = $serializer->serialize($author, 'json', ["groups" => "getAuthor"]);
        return new JsonResponse($json, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/authors', name: 'createAuthor', methods: ['POST'])]
    public function createAuthor(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, BookRepository $bookRepository)
    {
        $author = $serializer->deserialize($request->getContent(), Author::class, 'json');
        
        $em->persist($author);
        $em->flush();

        $jsonAuthor = $serializer->serialize($author, 'json', ['groups' => 'getAuthor']);


        return new JsonResponse($jsonAuthor, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/authors/{id}', name: 'updateAuthor', methods: ['PUT'])]
    public function updateAuthor(Request $request, Author $currentAuthor, SerializerInterface $serializer, EntityManagerInterface $em, AuthorRepository $authorRepository)
    {

        $updatedAuthor = $serializer->deserialize($request->getContent(), Author::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentAuthor]);

        $em->persist($updatedAuthor);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);



    }


    #[Route('/api/authors/{id}', name: 'deleteAuthor', methods: ['DELETE'])]
    public function deleteAuthor(Author $author, EntityManagerInterface $em)
    {
        $em->remove($author);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);


    }


}
