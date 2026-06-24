<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;

class Testمنتجات extends TestCase
{
    private $pdo;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetAllمنتجات()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Product 1'],
                ['id' => 2, 'name' => 'Product 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn('{"products": [{"id": 1, "name": "Product 1"}, {"id": 2, "name": "Product 2"}]}');

        $منتجاتController = new منتجاتController($this->pdo);
        $response = $منتجاتController->getAllمنتجات($this->request, $this->response);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetمنتجاتById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Product 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');
        $this->request->expects($this->once())
            ->method('getAttribute')
            ->willReturn(1);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn('{"product": {"id": 1, "name": "Product 1"}}');

        $منتجاتController = new منتجاتController($this->pdo);
        $response = $منتجاتController->getمنتجاتById($this->request, $this->response);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateمنتجات()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'New Product']);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn('{"message": "Product created successfully"}');

        $منتجاتController = new منتجاتController($this->pdo);
        $response = $منتجاتController->createمنتجات($this->request, $this->response);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateمنتجات()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('PUT');
        $this->request->expects($this->once())
            ->method('getAttribute')
            ->willReturn(1);
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Updated Product']);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn('{"message": "Product updated successfully"}');

        $منتجاتController = new منتجاتController($this->pdo);
        $response = $منتجاتController->updateمنتجات($this->request, $this->response);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteمنتجات()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('DELETE');
        $this->request->expects($this->once())
            ->method('getAttribute')
            ->willReturn(1);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn('{"message": "Product deleted successfully"}');

        $منتجاتController = new منتجاتController($this->pdo);
        $response = $منتجاتController->deleteمنتجات($this->request, $this->response);

        $this->assertEquals(200, $response->getStatusCode());
    }
}