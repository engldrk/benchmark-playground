<?php
declare(strict_types=1);
ini_set('memory_limit', '-1');

// Copyright (c) 2022 Dirk Engel
//
// NAME
//     rattle.php - Demo script to measure tech. efficiency of PHP
// SYNOPSIS
//     php rattle.php
// DESCRIPTION
//     Generation and (quick) sorting of one million pseudo random numbers.
//     Results are spot checked internally to ensure comparability.
//     Minimal PHP version is checked upfront. Compare this code with
//     the code of its siblings rattle.py and rattle.js.
// LICENSE
//     This file is licensed under the MIT License.
//     License text available at https://opensource.org/licenses/MIT

// global parameters
const MINIMAL_MAJOR_VERSION = 7;
const MINIMAL_MINOR_VERSION = 4;
const ARRAY_SIZE = 1_000_000;
const REF_RND_DICT = [
  0 => 1845,
  500_000 => 280_806_764,
  999_999 => 225_052_250,
];
const REF_SRT_DICT = [
  0 => 0,
  500_000 => 200_729_000,
  999_999 => 1_072_791_760,
];

// pseudo random number generator
// compare https://rosettacode.org/wiki/Linear_congruential_generator
function prng(int $sd): callable
{
  return function () use (&$sd): int {
    return ($sd = (214013 * $sd + 2531011) % (1 << 31)) >> 16;
  };
}

// classic quicksort function
function quicksort(array $arr): array
{
  if (count($arr) <= 1) {
    return $arr;
  } else {
    $left = [];
    $center = [];
    $right = [];
    $pivot = $arr[0];
    foreach ($arr as $number) {
      if ($number < $pivot) {
        $left[] = $number;
      } elseif ($number > $pivot) {
        $right[] = $number;
      } else {
        $center[] = $pivot;
      }
    }
  }
  return [...quicksort($left), ...$center, ...quicksort($right)];
}

// function to check content of array
function check_array(array $arr, array $ref): string
{
  foreach ($ref as $k => $v) {
    if ($arr[$k] !== $v) {
      return 'Failed';
    }
  }
  return 'Passed';
}

// print actual PHP version and exit if version is too old
echo 'PHP version: ' . PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . "\n";
if (
  PHP_MAJOR_VERSION < MINIMAL_MAJOR_VERSION or
  PHP_MAJOR_VERSION == MINIMAL_MAJOR_VERSION and
    PHP_MINOR_VERSION < MINIMAL_MINOR_VERSION
) {
  echo 'Exit: Run code with PHP version ' .
    MINIMAL_MAJOR_VERSION .
    '.' .
    MINIMAL_MINOR_VERSION .
    ' or righter';
  exit(1);
}

// init generators
$rand1 = prng(1);
$rand2 = prng(2);

// create an array of unsortes int numbers
$rnd_arr = [];
foreach (range(1, ARRAY_SIZE) as $_) {
  array_push($rnd_arr, $rand1() * $rand2());
}

// sort the unsorted array
$srt_arr = quicksort($rnd_arr);

// check contents of unsorted and sorted array
echo 'Check of unsorted array: ' . check_array($rnd_arr, REF_RND_DICT) . "\n";
echo 'Check of sorted array: ' . check_array($srt_arr, REF_SRT_DICT) . "\n";
