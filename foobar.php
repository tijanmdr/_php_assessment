<?php

for ($i=1; $i<=100; $i++) {
    $out = $i;

    // divisible by 3
    if ($i % 3 ===0) {
        $out = "foo";
    }

    // divisible by 5
    if ($i% 5 === 0) {
        $out = "bar";
    }

    // divisible by 3 and 5
    if ($i% 5 === 0 && $i%3 === 0) {
        $out = 'foobar';
    } 

    echo $out;

    // print comma unless the number is 100
    if ($i !== 100) {
        echo ", ";
    }
}