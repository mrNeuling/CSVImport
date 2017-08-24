<?php

namespace ImportBundle\Report;


/**
 * Interface Reporter
 * @package ImportBundle\Report
 */
interface Reporter
{
    /**
     * Send report conforming to reporter strategy
     *
     * @param ImportReport $report
     * @return mixed
     */
    public function send(ImportReport $report);
}