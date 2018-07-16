<?php namespace culv3r\XF2Bridge\Contracts;

interface VisitorInterface
{

    public function getCurrentVisitor();

    public function isBanned();

    public function isAdmin();

    public function isSuperAdmin();

    public function isLoggedIn();

    public function hasPermission($group, $permission);

    public function getUserId();
}