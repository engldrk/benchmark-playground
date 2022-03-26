# benchmark-playground
Tiny test cases to compare the runtimes and the memory consumptions of modern scripting languages.
The algorithm is generating and sorting one million pseudo random integer numbers.

The test was run on my aged Linux desktop computer: Ubuntu Linux 21.10 & Intel Core i5-3470S CPU @ 2.90GHz.

Runtime and memory peak usage are measured using good old /usr/bin/time:
1) Elapsed (wall clock) time in seconds
2) Maximum resident set size in megabytes

| Interpreter                 | Time (seconds) | Size (megabytes) |
|-----------------------------|----------------|------------------|
|  Python 3.10.2              |   4.0          |     94.4         |
|  PyPy 7.3.8 (≙ Python 3.7)  |   0.7          |     181.0        |
|  PHP 8.1.4                  |   1.7          |     211.5        |
|  node 17.8.0                |   0.8          |     251.6        |
|  deno 1.20.3 (≙ node 16.11) |   1.1          |     197.9        |