/*
Copyright (c) 2022 Dirk Engel

NAME
    rattle.js - Demo script to measure tech. efficiency of node and deno
SYNOPSIS
    node rattle.js
    deno run --compat --allow-read --unstable rattle.js
DESCRIPTION
    Generation and (quick) sorting of one million pseudo random numbers.
    Results are spot checked internally to ensure comparability.
    Minimal Node version is checked upfront. Compare this code with
    the code of its siblings rattle.py and rattle.php.
LICENSE
    This file is licensed under the MIT License.
    License text available at https://opensource.org/licenses/MIT
*/

'use strict';

// print actual node version and exit if version is too old
const MINIMAL_MAJOR_VERSION = 12;
const MINIMAL_MINOR_VERSION = 0;
const vermatch = process.version.match(/(\d+)\.(\d+)\./);
const [major, minor] = vermatch.slice(1).map((_) => parseInt(_));
console.log(`Node version: ${major}.${minor}`);
if (
  major < MINIMAL_MAJOR_VERSION ||
  (major == MINIMAL_MAJOR_VERSION && minor < MINIMAL_MINOR_VERSION)
) {
  console.error(
    `Exit: Run code with NodeJS version ` +
      `${MINIMAL_MAJOR_VERSION}.${MINIMAL_MINOR_VERSION} or higher`
  );
  process.exit(1);
}

// global parameters
const ARRAY_SIZE = 1_000_000;
const REF_RND_DICT = { 0: 1845, 500_000: 280_806_764, 999_999: 225_052_250 };
const REF_SRT_DICT = { 0: 0, 500_000: 200_729_000, 999_999: 1_072_791_760 };

// pseudo random number generator
// comparte https://rosettacode.org/wiki/Linear_congruential_generator
const prng = (sd) => {
  return () => {
    return (sd = (214013 * sd + 2531011) % (1 << 31)) >> 16;
  };
};

// classic quicksort function
function quicksort(arr) {
  if (arr.length <= 1) {
    return arr;
  } else {
    const left = [];
    const center = [];
    const right = [];
    const pivot = arr[0];
    for (const number of arr) {
      if (number < pivot) {
        left.push(number);
      } else if (number > pivot) {
        right.push(number);
      } else {
        center.push(pivot);
      }
    }
    return [...quicksort(left), ...center, ...quicksort(right)];
  }
}

// function to check content of array
function check_array(arr, ref) {
  for (const [k, v] of Object.entries(ref)) {
    if (arr[k] !== v) {
      return 'Failed';
    }
  }
  return 'Passed';
}

// init generators
const rand1 = prng(1);
const rand2 = prng(2);

// create an array of unsortes int numbers
const rnd_arr = [];
for (let _ of Array(ARRAY_SIZE)) {
  rnd_arr.push(rand1() * rand2());
}

// sort the unsorted array
const srt_arr = quicksort(rnd_arr);

// check contents of unsorted and sorted array
console.log(`Check of unsorted array: ${check_array(rnd_arr, REF_RND_DICT)}`);
console.log(`Check of sorted array: ${check_array(srt_arr, REF_SRT_DICT)}`);
