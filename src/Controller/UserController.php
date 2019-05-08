<?php

namespace Controller;

use Model\UserManager;

/**
 * Class UserController
 *
 * @package \Controller
 */
class UserController extends AbstractController
{
    public function userShow(int $id)
    {
        $userManager = new UserManager($this->getPdo());
        $user = $userManager->selectOneById($id);

        return $this->twig->render('AdminUser/show.html.twig', ['user' => $user]);
    }

    public function usersIndex()
    {
        $usersManager = new UserManager($this->getPdo());
        $users = $usersManager->selectAllUsers();
        return $this->twig->render('AdminUser/indexUsers.html.twig', ['users' => $users]);
    }

    public function userDelete(int $id)
    {
        $userManager = new UserManager($this->getPdo());
        $userManager->userDelete($id);

    }
}
