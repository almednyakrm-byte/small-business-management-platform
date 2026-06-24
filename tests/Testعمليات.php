<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\عملياتController;
use App\Repository\عملياتRepository;
use App\Entity\عمليات;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;

class Testعمليات extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(عملياتRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new عملياتController($this->repository, $this->entityManager);
    }

    public function testGetAll(): void
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetById(): void
    {
        $id = 1;
        $expectedResponse = ['data' => new عمليات()];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getById($id);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetByIdNotFound(): void
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->controller->getById($id);
    }

    public function testCreate(): void
    {
        $data = ['name' => 'Test'];
        $expectedResponse = ['data' => new عمليات()];
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($expectedResponse['data']);
        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->create($data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate(): void
    {
        $id = 1;
        $data = ['name' => 'Test'];
        $expectedResponse = ['data' => new عمليات()];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse['data']);
        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->update($id, $data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateNotFound(): void
    {
        $id = 1;
        $data = ['name' => 'Test'];
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->controller->update($id, $data);
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new عمليات());
        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with(new عمليات());
        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $response = $this->controller->delete($id);
        $this->assertEquals(null, $response);
    }

    public function testDeleteNotFound(): void
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->controller->delete($id);
    }
}


This test file covers the following scenarios:

1.  `testGetAll`: Verifies that the `getAll` method returns the expected response when fetching all operations.
2.  `testGetById`: Tests the `getById` method to ensure it returns the expected response when fetching an operation by ID.
3.  `testGetByIdNotFound`: Verifies that the `getById` method throws a `NotFoundHttpException` when trying to fetch an operation that does not exist.
4.  `testCreate`: Tests the `create` method to ensure it creates a new operation and returns the expected response.
5.  `testUpdate`: Verifies the `update` method updates an existing operation and returns the expected response.
6.  `testUpdateNotFound`: Tests the `update` method to ensure it throws a `NotFoundHttpException` when trying to update a non-existent operation.
7.  `testDelete`: Tests the `delete` method to ensure it deletes an operation and returns the expected response.
8.  `testDeleteNotFound`: Verifies the `delete` method throws a `NotFoundHttpException` when trying to delete a non-existent operation.

These tests cover the CRUD operations for the `عمليات` module using mocked PDO statements.