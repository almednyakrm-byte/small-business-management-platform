<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\المبيعاتController;
use App\Repository\المبيعاتRepository;
use App\Entity\المبيعات;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class Testالمبيعات extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(المبيعاتRepository::class);
        $this->controller = new المبيعاتController($this->repository, $this->entityManager);

        $this->pdo->method('prepare')->willReturn($this->createMock('PDOStatement'));
        $this->entityManager->method('getRepository')->willReturn($this->repository);
        $this->repository->method('findAll')->willReturn([]);
        $this->repository->method('find')->willReturn(null);
        $this->repository->method('save')->willReturn(null);
        $this->repository->method('remove')->willReturn(null);
    }

    public function testGetAll(): void
    {
        $response = $this->controller->getAll();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne(): void
    {
        $this->repository->method('find')->willReturn(new المبيعات());
        $response = $this->controller->getOne(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        $data = ['name' => 'Test Name'];
        $this->repository->method('save')->willReturn(new المبيعات());
        $response = $this->controller->create($data);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $data = ['name' => 'Test Name'];
        $this->repository->method('find')->willReturn(new المبيعات());
        $this->repository->method('save')->willReturn(new المبيعات());
        $response = $this->controller->update(1, $data);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $this->repository->method('find')->willReturn(new المبيعات());
        $response = $this->controller->delete(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}



// App\Controller\المبيعاتController.php
namespace App\Controller;

use App\Repository\المبيعاتRepository;
use App\Entity\المبيعات;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class المبيعاتController
{
    private $repository;
    private $entityManager;

    public function __construct(المبيعاتRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function getAll(): JsonResponse
    {
        $data = $this->repository->findAll();
        return new JsonResponse($data);
    }

    public function getOne(int $id): JsonResponse
    {
        $data = $this->repository->find($id);
        return new JsonResponse($data);
    }

    public function create(array $data): JsonResponse
    {
        $entity = new المبيعات();
        $entity->setName($data['name']);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return new JsonResponse($entity, Response::HTTP_CREATED);
    }

    public function update(int $id, array $data): JsonResponse
    {
        $entity = $this->repository->find($id);
        $entity->setName($data['name']);
        $this->entityManager->flush();
        return new JsonResponse($entity);
    }

    public function delete(int $id): JsonResponse
    {
        $entity = $this->repository->find($id);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
        return new JsonResponse();
    }
}