<?php declare(strict_types=1);

namespace Matth\Synchronizer\Dto\Toggl;

/**

 * @author Matthieu Calie <matthieu@calie.be>
 */
class DetailedReport
{
    /**
     * @var ?integer
     */
    private $totalGrand;

    /**
     * @var ?integer
     */
    private $totalBillable;

    /**
     * @var ?integer
     */
    private $totalCount;

    /**
     * @var integer
     */
    private $perPage;

    /**
     * @var DetailedReportLine[]
     */
    private $data = [];

//    /**
//     * DetailedReport constructor.
//     * @param int|null $total_grand
//     * @param int|null $total_billable
//     * @param int|null $total_count
//     * @param int $per_page
//     * @param TimeEntry $data
//     */
//    public function __construct(?int $total_grand, ?int $total_billable, ?int $total_count, int $per_page, array $data)
//    {
//        $this->totalGrand = $total_grand;
//        $this->totalBillable = $total_billable;
//        $this->totalCount = $total_count;
//        $this->perPage = $per_page;
//        $this->data = $data;
//    }

    /**
     * @return int
     */
    public function getTotalGrand(): int
    {
        return $this->totalGrand;
    }

    /**
     * @param int $totalGrand
     */
    public function setTotalGrand(?int $totalGrand)
    {
        $this->totalGrand = $totalGrand;
    }

    /**
     * @return int
     */
    public function getTotalBillable(): int
    {
        return $this->totalBillable;
    }

    /**
     * @param int $totalBillable
     */
    public function setTotalBillable(?int $totalBillable)
    {
        $this->totalBillable = $totalBillable;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @param int $totalCount
     */
    public function setTotalCount(?int $totalCount)
    {
        $this->totalCount = $totalCount;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(?int $perPage)
    {
        $this->perPage = $perPage;
    }

    /**
     * @return DetailedReportLine[]
     */
    public function getData()
    {
        return $this->data;
    }

    public function addData(DetailedReportLine $data): void
    {
        $this->data[] = $data;
    }

    public function removeData(DetailedReportLine $data): void
    {
        foreach ($this->data as $key => $line) {
            if ($line->getId() === $data->getId()) {
                unset($this->data[$key]);
            }
        }
    }
}
