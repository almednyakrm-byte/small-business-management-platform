<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ موظفينController;
use App\Repository\موظفينRepository;
use App\Entity\موظفين;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Testموظفين extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $router;
    private $tokenStorage;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MوظفينRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->controller = new موظفينController($this->repository, $this->entityManager, $this->router, $this->tokenStorage);
    }

    public function testGetAll(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new موظفين()]);

        $response = $this->controller->getAll();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetOne(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new موظفين());

        $response = $this->controller->getOne($id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreate(): void
    {
        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Mوظفين::class));
        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->create($data);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdate(): void
    {
        $id = 1;
        $data = ['name' => 'Jane Doe', 'email' => 'jane@example.com'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new موظفين());
        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->update($id, $data);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new موظفين());
        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($this->isInstanceOf(Mوظفين::class));
        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->delete($id);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// App\Controller\موظفينController.php

namespace App\Controller;

use App\Repository\موظفينRepository;
use App\Entity\موظفين;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class موظفينController
{
    private $repository;
    private $entityManager;
    private $router;
    private $tokenStorage;

    public function __construct(
        موظفينRepository $repository,
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }

    public function getAll(): Response
    {
        $employees = $this->repository->findAll();
        return new Response(json_encode($employees), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function getOne(int $id): Response
    {
        $employee = $this->repository->find($id);
        return new Response(json_encode($employee), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function create(array $data): Response
    {
        $employee = new موظفين();
        $employee->setName($data['name']);
        $employee->setEmail($data['email']);
        $this->entityManager->persist($employee);
        $this->entityManager->flush();
        return new Response('', Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    public function update(int $id, array $data): Response
    {
        $employee = $this->repository->find($id);
        $employee->setName($data['name']);
        $employee->setEmail($data['email']);
        $this->entityManager->flush();
        return new Response('', Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function delete(int $id): Response
    {
        $employee = $this->repository->find($id);
        $this->entityManager->remove($employee);
        $this->entityManager->flush();
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}