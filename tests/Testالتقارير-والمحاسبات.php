<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\ReportsAndAccountingRepository;
use App\Service\ReportsAndAccountingService;
use PHPUnit\Framework\MockObject\MockObject;

class Testالتقارير_ومحاسبات extends TestCase
{
    private $reportsAndAccountingRepository;
    private $reportsAndAccountingService;

    protected function setUp(): void
    {
        $this->reportsAndAccountingRepository = $this->createMock(ReportsAndAccountingRepository::class);
        $this->reportsAndAccountingService = $this->createMock(ReportsAndAccountingService::class);
    }

    public function testGetReportsAndAccounting(): void
    {
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);

        $response = $this->reportsAndAccountingService->getReportsAndAccounting();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetReportsAndAccountingById(): void
    {
        $id = 1;
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse);

        $response = $this->reportsAndAccountingService->getReportsAndAccountingById($id);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateReportsAndAccounting(): void
    {
        $data = ['report' => 'example report'];
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedResponse);

        $response = $this->reportsAndAccountingService->createReportsAndAccounting($data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateReportsAndAccounting(): void
    {
        $id = 1;
        $data = ['report' => 'example report'];
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($expectedResponse);

        $response = $this->reportsAndAccountingService->updateReportsAndAccounting($id, $data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteReportsAndAccounting(): void
    {
        $id = 1;
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('delete')
            ->with($id);

        $response = $this->reportsAndAccountingService->deleteReportsAndAccounting($id);
        $this->assertTrue($response);
    }

    public function testGetReportsAndAccountingApi(): void
    {
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);

        $response = $this->reportsAndAccountingService->getReportsAndAccountingApi();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetReportsAndAccountingByIdApi(): void
    {
        $id = 1;
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse);

        $response = $this->reportsAndAccountingService->getReportsAndAccountingByIdApi($id);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateReportsAndAccountingApi(): void
    {
        $data = ['report' => 'example report'];
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedResponse);

        $response = $this->reportsAndAccountingService->createReportsAndAccountingApi($data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateReportsAndAccountingApi(): void
    {
        $id = 1;
        $data = ['report' => 'example report'];
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($expectedResponse);

        $response = $this->reportsAndAccountingService->updateReportsAndAccountingApi($id, $data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteReportsAndAccountingApi(): void
    {
        $id = 1;
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('delete')
            ->with($id);

        $response = $this->reportsAndAccountingService->deleteReportsAndAccountingApi($id);
        $this->assertTrue($response);
    }

    public function testGetReportsAndAccountingController(): void
    {
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);

        $response = $this->reportsAndAccountingService->getReportsAndAccountingController();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetReportsAndAccountingByIdController(): void
    {
        $id = 1;
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse);

        $response = $this->reportsAndAccountingService->getReportsAndAccountingByIdController($id);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateReportsAndAccountingController(): void
    {
        $data = ['report' => 'example report'];
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedResponse);

        $response = $this->reportsAndAccountingService->createReportsAndAccountingController($data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateReportsAndAccountingController(): void
    {
        $id = 1;
        $data = ['report' => 'example report'];
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($expectedResponse);

        $response = $this->reportsAndAccountingService->updateReportsAndAccountingController($id, $data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteReportsAndAccountingController(): void
    {
        $id = 1;
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('delete')
            ->with($id);

        $response = $this->reportsAndAccountingService->deleteReportsAndAccountingController($id);
        $this->assertTrue($response);
    }

    public function testGetReportsAndAccountingApiGet(): void
    {
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);

        $response = $this->reportsAndAccountingService->getReportsAndAccountingApiGet();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetReportsAndAccountingByIdApiGet(): void
    {
        $id = 1;
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse);

        $response = $this->reportsAndAccountingService->getReportsAndAccountingByIdApiGet($id);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateReportsAndAccountingApiPost(): void
    {
        $data = ['report' => 'example report'];
        $expectedResponse = ['data' => ['report' => 'example report']];
        $this->reportsAndAccountingRepository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedResponse);

        $response =