#!/bin/bash

echo 'Warming up...'
php src/Benchmark/src/Benchmark/GlueAddToCart.php 5 > /dev/null

time php src/Benchmark/src/Benchmark/GlueAddToCart.php ${1:-100}

cd src/Benchmark
time vendor/bin/phpbench run src/Benchmark --iterations=${1:-100} --report=total --report=summary --time-unit=seconds
