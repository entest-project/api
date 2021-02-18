<?php

namespace App\Security;

interface ProjectPermission
{
    const ADMIN = 'admin'; // can add users or delete project
    const WRITE = 'write'; // can create features, folders and edit scenarios
    const PULL = 'pull'; // can pull features from project
    const READ = 'read';

    // Being in a private project without dedicated permission grants view rights on it
}
