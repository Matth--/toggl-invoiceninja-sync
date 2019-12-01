<?php

declare(strict_types=1);

namespace Matth\Synchronizer\Toggl;

use Matth\Synchronizer\Dto\Toggl\Client;
use Matth\Synchronizer\Dto\Toggl\Project;
use Matth\Synchronizer\Dto\Toggl\Workspace;

interface TogglClientInterface
{
    /**
     * @return Workspace[]
     */
    public function getWorkSpaces(): array;

    /**
     * @param int $workspaceId
     *
     * @return Project[]
     */
    public function getProjectsByWorkSpace(int $workspaceId): array;

    /**
     * @param int $workspaceId
     *
     * @return Client[]
     */
    public function getClientsByWorkspaceId(int $workspaceId): array;
}
