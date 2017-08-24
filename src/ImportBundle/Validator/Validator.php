<?php

namespace ImportBundle\Validator;


/**
 * Interface Validator
 * @package ImportBundle\Validator
 */
interface Validator
{
    /**
     * @param array $row
     * @return bool
     */
    public function validate(array $row): bool;
}