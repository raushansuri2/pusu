<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class PagesTable extends Table
{    
    public function initialize(array $config): void
    {
        $this->setTable('pages');
    }
}