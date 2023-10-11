<?php

namespace Tests\Unit;

use App\Services\GoogleSheetsService;
use Mockery;
use Tests\TestCase;

class CalculateMovingAverageTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCommandHandleSuccess()
    {
        $testSheetId = 'testSheetId';
        $testHeader = [['Date', 'Visitors', 'Moving Average']];
        $testData = [
            [100],
            [200],
            [300],
        ];
        $expectedMovingAverage = [
            ['Moving Average'],
            [0],
            [150],
            [250],
        ];

        $sheetsService = Mockery::mock(GoogleSheetsService::class);

        $sheetsService->shouldReceive('getValues')
            ->once()
            ->andReturn($testHeader);

        $sheetsService->shouldReceive('getValues')
            ->once()
            ->andReturn($testData);

        $sheetsService->shouldReceive('updateValues')
            ->once()
            ->andReturn($expectedMovingAverage);


        $this->app->instance(GoogleSheetsService::class, $sheetsService);

        $this->artisan('calculate:average', ['sheetId' => $testSheetId])
            ->expectsOutput('Moving average updated successfully.')
            ->assertExitCode(0);
    }

    public function testCommandHandleError()
    {
        $testSheetId = 'testSheetId';
        $testHeader = [];

        $sheetsService = Mockery::mock(GoogleSheetsService::class);

        $sheetsService->shouldReceive('getValues')
            ->once()
            ->andReturn($testHeader);

        $this->app->instance(GoogleSheetsService::class, $sheetsService);

        $this->artisan('calculate:average', ['sheetId' => $testSheetId])
            ->expectsOutput('Header row not found.')
            ->assertExitCode(0);
    }
}
