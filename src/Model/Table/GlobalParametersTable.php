<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class GlobalParametersTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('global_parameters');
    }
}
