<?php

for ($i=1; $i<=100; $i++) {
    $out = $i;
    if ($i % 3 ===0) {
        $out = "foo";
    }
    if ($i% 5 === 0) 
        $out = "bar";

    if ($i% 5 === 0 && $i%3 === 0) {
        $out = 'foobar';
    } 
    echo $out;
    if ($i !== 100) {
        echo ", ";
    }
}