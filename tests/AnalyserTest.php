<?php
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocSignatureInspection */

namespace webignition\SfsResultAnalyser\Tests;

use PHPUnit\Framework\TestCase;
use webignition\SfsResultAnalyser\Analyser;
use webignition\SfsResultFactory\ResultFactory;
use webignition\SfsResultInterfaces\ResultInterface;

class AnalyserTest extends TestCase
{
    /**
     * @var Analyser
     */
    private $analyser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->analyser = new Analyser();
    }

    /**
     * @dataProvider calculateTrustworthinessDataProvider
     */
    public function testCalculateTrustworthiness(ResultInterface $result, float $expectedTrustworthiness)
    {
        $this->assertSame($expectedTrustworthiness, $this->analyser->calculateTrustworthiness($result));
    }

    public function calculateTrustworthinessDataProvider(): array
    {
        $resultFactory = ResultFactory::createFactory();
        $now = new \DateTime();

        return [
            'not seen' => [
                'result' => $resultFactory->create(
                    [
                        'value' => '127.0.0.1',
                        'frequency' => 0,
                        'appears' => 0,
                    ],
                    'ip'
                ),
                'expectedTrustworthiness' => 1.0,
            ],
            'blacklisted' => [
                'result' => $resultFactory->create(
                    [
                        'value' => '127.0.0.1',
                        'lastseen' => $now->format('Y-m-d H:i:s'),
                        'frequency' => 255,
                        'appears' => 1,
                    ],
                    'ip'
                ),
                'expectedTrustworthiness' => 0.0,
            ],
            'confidence 0.11' => [
                'result' => $resultFactory->create(
                    [
                        'value' => '127.0.0.1',
                        'lastseen' => $now->format('Y-m-d H:i:s'),
                        'frequency' => 2,
                        'appears' => 1,
                        'confidence' => 0.11
                    ],
                    'ip'
                ),
                'expectedTrustworthiness' => 0.9989,
            ],
        ];
    }

    /**
     * @dataProvider isUntrustworthyDataProvider
     */
    public function testIsUntrustworthy(ResultInterface $result, bool $expectedIsUntrustworthy)
    {
        $this->assertSame($expectedIsUntrustworthy, $this->analyser->isUntrustworthy($result));
    }

    public function isUntrustworthyDataProvider(): array
    {
        $resultFactory = ResultFactory::createFactory();
        $now = new \DateTime();

        return [
            'not seen' => [
                'result' => $resultFactory->create(
                    [
                        'value' => '127.0.0.1',
                        'frequency' => 0,
                        'appears' => 0,
                    ],
                    'ip'
                ),
                'expectedIsUntrustworthy' => false,
            ],
            'blacklisted' => [
                'result' => $resultFactory->create(
                    [
                        'value' => '127.0.0.1',
                        'lastseen' => $now->format('Y-m-d H:i:s'),
                        'frequency' => 255,
                        'appears' => 1,
                    ],
                    'ip'
                ),
                'expectedIsUntrustworthy' => true,
            ],
            'confidence 79.0' => [
                'result' => $resultFactory->create(
                    [
                        'value' => '127.0.0.1',
                        'lastseen' => $now->format('Y-m-d H:i:s'),
                        'frequency' => 2,
                        'appears' => 1,
                        'confidence' => 79.0
                    ],
                    'ip'
                ),
                'expectedIsUntrustworthy' => false,
            ],
            'confidence 80.0' => [
                'result' => $resultFactory->create(
                    [
                        'value' => '127.0.0.1',
                        'lastseen' => $now->format('Y-m-d H:i:s'),
                        'frequency' => 2,
                        'appears' => 1,
                        'confidence' => 80.0
                    ],
                    'ip'
                ),
                'expectedIsUntrustworthy' => true,
            ],
        ];
    }
}
