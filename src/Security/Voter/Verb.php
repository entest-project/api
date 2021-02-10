<?php

namespace App\Security\Voter;

interface Verb
{
    const CREATE = 'create';
    const DELETE = 'delete';
    const READ = 'read';
    const UPDATE = 'update';
}
