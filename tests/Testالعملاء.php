<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\العملاءController;
use App\Repository\العملاءRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class Testالعملاء extends TestCase
{
    private $controller;
    private $repository;
    private $router;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(العملاءRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->controller = new العملاءController($this->repository, $this->router);
    }

    public function testGetAll(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        $response = $this->controller->getAll($this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetById(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        $this->request->expects($this->once())
            ->method('attributes')
            ->willReturn(['id' => 1]);

        $response = $this->controller->getById($this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        $this->repository->expects($this->once())
            ->method('save')
            ->with(['name' => 'John Doe']);

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'John Doe']);

        $response = $this->controller->create($this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'John Doe']);

        $this->request->expects($this->once())
            ->method('attributes')
            ->willReturn(['id' => 1]);

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'John Doe']);

        $response = $this->controller->update($this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1);

        $this->request->expects($this->once())
            ->method('attributes')
            ->willReturn(['id' => 1]);

        $response = $this->controller->delete($this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// App\Controller\العملاءController.php

namespace App\Controller;

use App\Repository\العملاءRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class العملاءController
{
    private $repository;
    private $router;

    public function __construct(العملاءRepository $repository, RouterInterface $router)
    {
        $this->repository = $repository;
        $this->router = $router;
    }

    public function getAll(Request $request): Response
    {
        return new Response($this->repository->findAll());
    }

    public function getById(Request $request): Response
    {
        $id = $request->attributes->get('id');
        return new Response($this->repository->find($id));
    }

    public function create(Request $request): Response
    {
        $data = $request->request->all();
        $this->repository->save($data);
        return new Response('', Response::HTTP_CREATED);
    }

    public function update(Request $request): Response
    {
        $id = $request->attributes->get('id');
        $data = $request->request->all();
        $this->repository->update($id, $data);
        return new Response('', Response::HTTP_OK);
    }

    public function delete(Request $request): Response
    {
        $id = $request->attributes->get('id');
        $this->repository->delete($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}