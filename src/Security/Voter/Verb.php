<?php

namespace App\Security\Voter;

interface Verb
{
    const CREATE = 'create';
    const DELETE = 'delete';
    const READ = 'read';
    const UPDATE = 'update';
    const WRITE_IN = 'write_in';
    const LIST_USERS = 'list_users';
    const LIST_ISSUE_TRACKER_CONFIGURATIONS = 'list_issue_tracker_configurations';
    const CREATE_TOKEN = 'create_token';
    const READ_TOKEN = 'read_token';
    const PULL = 'pull';

    const SYNC = 'sync';
}
