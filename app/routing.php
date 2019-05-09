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
  
    'Admin' => [
        ['showDashboard', '/admin/dashboard', 'GET'], //show dashboard for admin
        ['adminShow', '/admin/article/{id:\d+}', 'GET'],//show article for admin
        ['add', '/admin/createArticle', ['GET', 'POST']], // add an article admin
        ['edit', '/admin/article/edit/{id:\d+}', ['GET', 'POST']], //modify an article admin
        ['delete', '/admin/article/delete/{id:\d+}', 'GET'], // delete an article admin
        ['indexAdmin', '/admin/articles', 'GET'], //show index for admin
        ['logAdmin', '/admin/logAdmin', ['GET', 'POST']],
        ['logout', '/admin/logout', 'GET'],//logout
    ],

    'AdminComment' => [
        ['delete', '/admin/comment/delete/{id:\d+}', 'GET'],//add comment by user
        ['add', '/article/{id:\d+}/comment', ['GET','POST']],    //add comment by user
        ['indexAdminComments', '/admin/comments', 'GET'], //show index comment for admin
    ],

    'User' => [
        ['userShow', '/admin/user/{id:\d+}', 'GET'],//show user for admin
        ['usersIndex', '/admin/users', 'GET'],//show index users for admin
        ['userDelete', '/admin/user/delete/{id:\d+}', 'GET'], // delete user admin
        ['addUser', '/admin/user/createUser', ['GET', 'POST']],
        ['suscribeUser', '/register', ['GET','POST']], // Register page
        ['logUser', '/login', ['GET','POST']], //  Login page

    ]

];

