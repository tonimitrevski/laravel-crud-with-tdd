<?php
/**
 * Created by PhpStorm.
 * User: toni
 * Date: 14.6.16
 * Time: 13:31
 */

namespace App\Repositories\Criteria\InterfaceCriteria;

use App\Repositories\Criteria\AbstractClass\Criteria;

interface CriteriaInterface
{
    /**
     * @return $this
     */
    public function resetScope();

    /**
     * @param bool $status
     * @return $this
     */
    public function skipCriteria($status = true);

    /**
     * @return mixed
     */
    public function getCriteria();

    /**
     * @param $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria);

    /**
     * @param $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria);

    /**
     * @return $this
     */
    public function applyCriteria();

    /**
     * @return mixed
     */
    public function removeCriteriaAll();
}