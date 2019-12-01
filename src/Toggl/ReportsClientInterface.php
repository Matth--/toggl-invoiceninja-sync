<?php

declare(strict_types=1);

namespace Matth\Synchronizer\Toggl;

use Matth\Synchronizer\Dto\Toggl\DetailedReport;

interface ReportsClientInterface
{
    /**
     * @param int $workspaceId
     *
     * @return DetailedReport
     */
    public function getDetailedReport(int $workspaceId): DetailedReport;
}
