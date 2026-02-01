<?php
namespace App\Model\Table;

use Cake\ORM\Table;
 
class StatesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('states');
    }
}
