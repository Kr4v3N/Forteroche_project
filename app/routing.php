<?php

/**
 * This file hold all routes definitions.
 *
 */
$routes = [

    'Admin' => [ // Controller
        ['showDashboard', '/admin/dashboard', 'GET'], //show dashboard for admin
        ['adminShow', '/admin/article/{id:\d+}', 'GET'], //show article for admin
        ['add', '/admin/createArticle', ['GET', 'POST']], //add an article admin
        ['edit', '/admin/article/edit/{id:\d+}', ['GET', 'POST']], //modify an article admin
        ['delete', '/admin/article/delete/{id:\d+}', 'GET'], //delete an article admin
        ['indexAdmin', '/admin/articles', 'GET'], //show index for admin
        ['logAdmin', '/admin/logAdmin', ['GET', 'POST']], //action, url, method
        ['logout', '/admin/logout', 'GET'], //logout
        ['addUser', '/admin/user/createUser', ['GET', 'POST']], //add user by admin
        ['userShow', '/admin/user/{id:\d+}', 'GET'], //action, url, method
        ['usersIndex', '/admin/users', 'GET'], //action, url, method
        ['userDelete', '/admin/user/delete/{id:\d+}', 'GET'], //action, url, method
    ],

    'AdminComment' => [ // Controller
        ['indexAdminComments', '/admin/comments', 'GET'], //show index comment for admin
        ['add', '/article/{id:\d+}/comment', 'POST'],    //add comment by user
        ['addCommentSignal', '/article/comment/signal/{id:\d+}', 'GET'], //add comment signal by user
        ['delete', '/admin/comment/delete/{id:\d+}', 'GET'], //remove comment
        ['indexAdminCommentsSignals', '/admin/comments/signals', 'GET'], //show index commentsSignal for admin
        ['resetSignal', '/admin/comment/reset/{id:\d+}', 'GET'], //reset signal by user
    ],

    'User' => [ // Controller

        ['suscribeUser', '/register', ['GET','POST']], //register page
        ['logUser', '/login', ['GET','POST']], //login page
        ['logoutUser', '/logout', 'GET'], //logout
        ['error', '/error/404', 'GET'], // action, url, method
    ],

    'Article' => [ // Controller
        ['indexAccueil', '/', 'GET'], //show homepage to users
        ['index', '/articles', 'GET'], //show index to users
        ['show', '/article/{id:\d+}', 'GET'], //show article to users
        ['showbycat', '/article/category/{id:\d+}', 'GET'], //show article to users by category
        ['mentionsLegals', '/mentionsLegals', 'GET'], //mention légals
    ],
];

