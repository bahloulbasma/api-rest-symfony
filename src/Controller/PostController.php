<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Code\Generator\GeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/posts")
 */
#[AsController]
class PostController

{
    /**
     * @Route(name="api_posts_collection_get",methods={"GET"})
     * @param PostRepository $postRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(PostRepository $postRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize(
                $postRepository->findAll(), 'json', ['groups' => 'get']),
            JsonResponse::HTTP_OK, [],
            true


        );
    }

    /**
     * @Route("/{id}", name="api_posts_item_get",methods={"GET"})
     * @param Post $post
     * @param SerializerInterface $serializer
     * @return void
     */
    public function item(Post $post, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize(
                $post, 'json', ['groups' => 'get']),
            JsonResponse::HTTP_OK, [],
            true


        );
    }

    /**
     * @Route(name="api_posts_item_post",methods={"POST"})
     * @param Request $request
     * @param Post $post
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $urlGenerator
     * @return JsonResponse
     */
    public function post(
        Request $request,
        #[MapEntity] Post $post,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator): JsonResponse
    {

        $post = $serializer->deserialize($request->getContent(), Post::class, 'json');
        $post->setAuthor($entityManager->getRepository(User::class)->findOneBy([]));
        $errors = $validator->validate($post);
        if($errors->count()>0){
            return new JsonResponse($serializer->serialize($errors,'json',[]),JsonResponse::HTTP_BAD_REQUEST,[],true);
        }
        $entityManager->persist($post);
        $entityManager->flush();
        return new JsonResponse(
            $serializer->serialize($post, "json", ["groups" => "get"]),
            JsonResponse::HTTP_CREATED,
            ["location"=>$urlGenerator->generate("api_posts_item_get",["id"=>$post->getId()])],
            true
        );
    }

    /**
     * @Route("/{id}",name="api_posts_item_put",methods={"PUT"})
     * @param $post
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public  function put (Post $post,Request $request,SerializerInterface $serializer,EntityManagerInterface $entityManager):JsonResponse {
        $serializer->deserialize($request->getContent(), Post::class, 'json',[AbstractNormalizer::OBJECT_TO_POPULATE=>$post]);
        $entityManager->flush();
        return new JsonResponse(NULL, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/{id}",name="api_posts_item_delete",methods={"DELETE"})
     * @param Post $post
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public  function delete (Post $post,EntityManagerInterface $entityManager):JsonResponse {
        $entityManager->remove($post);
        $entityManager->flush();
        return new JsonResponse(NULL, JsonResponse::HTTP_NO_CONTENT);
    }
}