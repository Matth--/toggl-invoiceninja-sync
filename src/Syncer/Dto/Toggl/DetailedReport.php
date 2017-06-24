<?php

namespace Syncer\Dto\Toggl;

/**
 * Class DetailedReport
 * @package Syncer\Dto\Toggl
 *
 * @author Matthieu Calie <matthieu@calie.be>
 */
class DetailedReport
{
    /**
     * @var integer
     */
    private $totalGrand;

    /**
     * @var integer
     */
    private $totalBillable;

    /**
     * @var integer
     */
    private $totalCount;

    /**
     * @var integer
     */
    private $perPage;

    /**
     * @var array
     */
    private $data;

    /**
     * @return int
     */
    public function getTotalGrand()
    {
        return $this->totalGrand;
    }

    /**
     * @param int $totalGrand
     */
    public function setTotalGrand($totalGrand)
    {
        $this->totalGrand = $totalGrand;
    }

    /**
     * @return int
     */
    public function getTotalBillable()
    {
        return $this->totalBillable;
    }

    /**
     * @param int $totalBillable
     */
    public function setTotalBillable($totalBillable)
    {
        $this->totalBillable = $totalBillable;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @param int $totalCount
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

    /**
     * @return array|TimeEntry[]
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
