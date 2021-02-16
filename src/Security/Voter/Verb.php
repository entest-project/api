<?php

namespace App\Security\Voter;

interface Verb
{
    const CREATE = 'create';
    const DELETE = 'delete';
    const READ = 'read';
    const UPDATE = 'update';
    const LIST_USERS = 'list_users';
}
