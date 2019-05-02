<?php

/**
 * This file hold all routes definitions.
 *
 */

$routes = [
    'Article' => [
        ['index', '/articles', 'GET'], //show index to users
        ['show', '/article/{id:\d+}', 'GET'], //show article to users

    ],

];
