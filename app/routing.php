<?php

/**
 * This file hold all routes definitions.
 *
 */
$routes = [

    'Admin' => [
        ['showDashboard', '/admin/dashboard', 'GET'], //show dashboard for admin
        ['adminShow', '/admin/article/{id:\d+}', 'GET'],//show article for admin
        ['add', '/admin/createArticle', ['GET', 'POST']], // add an article admin
        ['edit', '/admin/article/edit/{id:\d+}', ['GET', 'POST']], //modify an article admin
        ['delete', '/admin/article/delete/{id:\d+}', 'GET'], // delete an article admin
        ['indexAdmin', '/admin/articles', 'GET'], //show index for admin
        ['logAdmin', '/admin/logAdmin', ['GET', 'POST']],
        ['logout', '/admin/logout', 'GET'],//logout

        ['userShow', '/admin/user/{id:\d+}', 'GET'],
        ['usersIndex', '/admin/users', 'GET'],
        ['userDelete', '/admin/user/delete/{id:\d+}', 'GET'],
    ],

    'AdminComment' => [
        ['delete', '/admin/comment/delete/{id:\d+}', 'GET'],//add comment by user
        ['add', '/article/{id:\d+}/comment', 'POST'],    //add comment by user
        ['indexAdminComments', '/admin/comments', 'GET'], //show index comment for admin
    ],

    'User' => [

        ['suscribeUser', '/register', ['GET','POST']], // Register page
        ['logUser', '/login', ['GET','POST']], //  Login page
        ['addUser', '/admin/user/createUser', ['GET', 'POST']],
        ['logoutUser', '/logout', 'GET'],//logout
    ],

    'Article' => [
        ['index', '/articles', 'GET'], //show index to users
        ['show', '/article/{id:\d+}', 'GET'], //show article to users
        ['indexAccueil', '/', 'GET'], //show homepage to users
    ],
];

