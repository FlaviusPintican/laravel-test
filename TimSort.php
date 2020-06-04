<?php declare(strict_types=1);

namespace Sorting;

class TimSort
{
    /**
     * @var int
     */
    private const RUN = 32;

    /**
     * @return void
     */
    public static function testSortExecutionFiveTimesFor11Numbers(): void
    {
        $sumExecutedCodes = 0;

        for ($i = 0; $i < 5; ++$i) {
            $startTime = microtime(true);
            self::sort([99, 91, 98, 92, 97, 93, 96, 94, 95, 89, 90]);
            $sumExecutedCodes += microtime(true) - $startTime;
        }

        echo sprintf('time spend %.10fs', $sumExecutedCodes / 5) . PHP_EOL;
    }

    /**
     * @return void
     */
    public static function testSortExecutionFor10000Powers(): void
    {
        $data = [];

        for ($i = 0; $i < 10000; ++$i) {
            $a = rand(100, 10000);
            $b = rand(100, 10000);
            $data[] = $a ** $b;
        }

        $startTime = microtime(true);
        self::sort($data);
        $timeSpent = microtime(true) - $startTime;

        echo sprintf('time spend %.10fs', $timeSpent) . PHP_EOL;
    }

    /**
     * Iterative Timsort function to sort the array[0...n-1] (similar to merge sort)
     *
     * @param array $data
     *
     * @return array
     */
    private static function sort(array $data): array
    {
        $n = count($data);
        // Sort individual subarrays of size RUN
        for ($i = 0; $i < $n; $i += self::RUN) {
            self::insertionSort($data, $i, min(($i + 31), ($n - 1)));
        }

        // start merging from size RUN (or 32). It will merge
        // to form size 64, then 128, 256 and so on ....
        for ($size = self::RUN; $size < $n; $size = 2 * $size) {
            // pick starting point of left sub array. We
            // are going to merge arr[left..left+size-1]
            // and arr[left+size, left+2*size-1]
            // After every merge, we increase left by 2*size
            for ($left = 0; $left < $n; $left += 2 * $size) {
                // find ending point of left sub array
                // mid+1 is starting point of right sub array
                $middle = $left + $size - 1;
                $right = min(($left + 2 * $size - 1), ($n - 1));

                // merge sub array arr[left.....mid] &
                // arr[mid+1....right]
                static::merge($data, $left, $middle, $right);
            }
        }

        return $data;
    }

    /**
     * This function sorts array from left index to to right index which is of size atmost RUN
     *
     * @param int[] $data
     * @param int   $left
     * @param int   $right
     *
     * @return array
     */
    private static function insertionSort(array &$data, int $left, int $right) : array
    {
        for ($i = $left + 1; $i <= $right; $i++) {
            $temp = $data[$i];
            $j = $i - 1;

            while ($j >= $left && $data[$j] > $temp) {
                $data[$j + 1] = $data[$j];
                $j--;
            }

            $data[$j + 1] = $temp;
        }

        return $data;
    }

    /**
     * merges the sorted runs
     * @param int[] $data
     * @param int   $left
     * @param int   $middle
     * @param int   $right
     *
     * @return array
     */
    private static function merge(array &$data, int $left, int $middle, int $right): array
    {
        // original array is broken in two parts left and right array
        $leftLength = $middle - $left + 1;
        $rightLenght = $right - $middle;
        $leftData = [];
        $rightData = [];

        for ($i = 0; $i < $leftLength; $i++) {
            if (!isset($data[$left + $i])) {
                continue;
            }

            $leftData[$i] = $data[$left + $i];
        }

        for ($i = 0; $i < $rightLenght; $i++) {
            $rightData[$i] = $data[$middle + 1 + $i];
        }

        $i = 0;
        $j = 0;
        $k = $left;

        // after comparing, we merge those two array in larger sub array
        while ($i < $leftLength && $j < $rightLenght) {
            if ($leftData[$i] <= $rightData[$j]) {
                $data[$k] = $leftData[$i];
                $i++;
            } else {
                $data[$k] = $rightData[$j];
                $j++;
            }
            $k++;
        }

        // copy remaining elements of left, if any
        while ($i < $rightLenght) {
            $data[$k] = $leftData[$i];
            $k++;
            $i++;
        }

        // copy remaining element of right, if any
        while ($j < $rightLenght) {
            $data[$k] = $rightData[$j];
            $k++;
            $j++;
        }

        return $data;
    }
};

TimSort::testSortExecutionFiveTimesFor11Numbers();
TimSort::testSortExecutionFor10000Powers();
