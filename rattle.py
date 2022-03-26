#
# Copyright (c) 2022 Dirk Engel
#
# NAME
#     rattle.py - Demo script to measure tech. efficiency of Python and PyPy
# SYNOPSIS
#     python rattle.py
#     pypy rattle.py
# DESCRIPTION
#     Generation and (quick) sorting of one million pseudo random numbers.
#     Results are spot checked internally to ensure comparability.
#     Minimal Python version is checked upfront. Compare this code with
#     the code of its siblings rattle.js and rattle.php.
# LICENSE
#     This file is licensed under the MIT License.
#     License text available at https://opensource.org/licenses/MIT
#

import sys
import re
from typing import List
from typing import Dict

# print actual Python version and exit if version is too old
MINIMAL_MAJOR_VERSION = 3
MINIMAL_MINOR_VERSION = 7
m = re.match(r"(\d+)\.(\d+)\.", sys.version)
major = int(m.group(1))  # type: ignore
minor = int(m.group(2))  # type: ignore
print("Python version: {major}.{minor}".format(major=major, minor=minor))
if major < MINIMAL_MAJOR_VERSION or (
    major == MINIMAL_MAJOR_VERSION and minor < MINIMAL_MINOR_VERSION
):
    print(
        "Exit: Run code with Python version "
        + "{MINIMAL_MAJOR_VERSION}.{MINIMAL_MINOR_VERSION} or higher".format(
            MINIMAL_MAJOR_VERSION=MINIMAL_MAJOR_VERSION,
            MINIMAL_MINOR_VERSION=MINIMAL_MINOR_VERSION,
        )
    )
    sys.exit(1)

# global parameters
ARRAY_SIZE = 1_000_000
REF_RND_DICT = {0: 1845, 500_000: 280_806_764, 999_999: 225_052_250}
REF_SRT_DICT = {0: 0, 500_000: 200_729_000, 999_999: 1_072_791_760}

# pseudo random number generator
# compare https://rosettacode.org/wiki/Linear_congruential_generator
def prng(seed: int):
    def rand():
        nonlocal seed
        seed = (214013 * seed + 2531011) & 0x7FFFFFFF
        return seed >> 16

    return rand


# classic quicksort function
def quicksort(arr: List[int]) -> List[int]:
    if len(arr) <= 1:
        return arr
    else:
        left: List[int] = []
        center: List[int] = []
        right: List[int] = []
        pivot = arr[0]
        for number in arr:
            if number < pivot:
                left.append(number)
            elif number > pivot:
                right.append(number)
            else:
                center.append(number)
        return quicksort(left) + center + quicksort(right)


# function to check content of array
def check_array(arr: List[int], ref: Dict[int, int]) -> str:
    for k, v in ref.items():
        if arr[k] != v:
            return "Failed"
    return "Passed"


# init generators
rand1 = prng(1)
rand2 = prng(2)

# create an array of unsortes int numbers
rnd_arr: List[int] = []
for _ in range(ARRAY_SIZE):
    rnd_arr.append(rand1() * rand2())

# sort the unsorted array
srt_arr = quicksort(rnd_arr)

# check contents of unsorted and sorted array
print(f"Check of unsorted array: {check_array(rnd_arr, REF_RND_DICT)}")
print(f"Check of sorted array: {check_array(srt_arr, REF_SRT_DICT)}")
