<?php declare(strict_types=1);

namespace Sorting;

class Usort
{
    /**
     * @return void
     */
    public static function testSortExecutionFiveTimesFor11Numbers(): void
    {
        $sumExecutedCodes = 0;

        for ($i = 0; $i < 5; ++$i) {
            $data = [99, 91, 98, 92, 97, 93, 96, 94, 95, 89, 90];
            $startTime = microtime(true);
            usort($data, fn (int $first, int $second) => $first <=> $second);
            $sumExecutedCodes += microtime(true) - $startTime;
        }

        echo sprintf('time spend %.10fs', $sumExecutedCodes / 5) . PHP_EOL;
    }

    /**
     * @return void
     */
    public static function testSortExecutionFor10000Powers(): void
    {
        $data = new \ArrayIterator();
        for ($i = 0; $i < 10000; ++$i) {
            $a = rand(100, 10000);
            $b = rand(100, 10000);
            $data->append($a ** $b);
        }

        $data = iterator_to_array($data, true);
        $startTime = microtime(true);
        usort($data, fn ($first, $second) => $first <=> $second);
        $timeSpent = microtime(true) - $startTime;

        echo sprintf('time spend %.10fs', $timeSpent) . PHP_EOL;
    }
}

Usort::testSortExecutionFiveTimesFor11Numbers();
Usort::testSortExecutionFor10000Powers();
