<?php

namespace App\Security;

interface OrganizationPermission
{
    const ADMIN = 'admin'; // can create projects, and become admin of created projects, and update / delete organization
    const PROJECT_CREATE = 'project_create'; // can create projects and become admin of them
    const PROJECT_WRITE = 'project_write'; // can't create projects but have write rights on them
    const READ = 'read';

    // Being in an organization without dedicated permissions grants view rights on its internal and public projects
}
