<?php

namespace App\Security;

interface Permission
{
    const PROJECT_ADMIN = 'PROJECT_ADMIN'; // can add users or delete project
    const PROJECT_WRITE = 'PROJECT_WRITE'; // can create features, folders and edit scenarios
    const PROJECT_PULL = 'PROJECT_PULL'; // can pull features from project
    const ORGANIZATION_ADMIN = 'ORGANIZATION_ADMIN'; // can create projects, and become admin of created projects
    const ORGANIZATION_PROJECT_CREATE = 'ORGANIZATION_PROJECT_CREATE'; // can create projects and have write rights on them
    const ORGANIZATION_PROJECT_WRITE = 'ORGANIZATION_PROJECT_WRITE'; // can't create projects but have write rights on them

    /**
     * Being in a private project without dedicated permission grants view rights on it
     * Being in an organization without dedicated permissions grants view rights on its internal projects
     */
}
