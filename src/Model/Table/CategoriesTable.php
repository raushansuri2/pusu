<?php
namespace App\Model\Table;

use Cake\ORM\Table;
 
class CategoriesTable extends Table
{
	public function initialize(array $config): void
    {
        $this->setTable('categories');

        $this->belongsTo('ParentCategory', [
            'className' => 'Categories',
			'foreignKey' => ['parentId'],
			'dependent' => true,
			'cascadeCallbacks' => true,
        ]);
        
		$this->hasMany('Products', [
            'className' => 'Products',
			'foreignKey' => ['categoryId'],
			//'dependent' => true,
			//'cascadeCallbacks' => true,
        ]);
        
		$this->hasMany('Subproducts', [
            'className' => 'Products',
			'foreignKey' => ['subCategory'],
			//'dependent' => true,
			//'cascadeCallbacks' => true,
        ]);
    }
}