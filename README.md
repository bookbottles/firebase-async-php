firebase-php
============

# Firebase PHP Client

Based on Firebase REST API: https://www.firebase.com/docs/rest-api.html

Fork of `ktamas77/firebase-php` with async option using `curl` and `exec`. Use at your own risk. Potentially unsafe.

Example
=================
```
<?php

const DEFAULT_URL = 'https://kidsplace.firebaseio.com/';
const DEFAULT_TOKEN = 'MqL0c8tKCtheLSYcygYNtGhU8Z2hULOFs9OKPdEp';
const DEFAULT_PATH = '/firebase/example';

$firebase = new Firebase(DEFAULT_URL, DEFAULT_TOKEN);

$test = array(
    "foo" => "bar",
    "i_love" => "lamp",
    "id" => 42
);

$dateTime = new DateTime();

$firebase->set(DEFAULT_PATH . '/' . $dateTime->format('c'), $test, true);

```


# The MIT License (MIT)

Copyright (c) 2015 Anid Monsur

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
