<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
/**************************************************************************************************/
class CategoriesController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }
    
    public function index()
	{
	    $layoutTitle = 'Admin::ManageCategory';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');
	    
	    $limit = 10;
	    
	    // Set pagination options
	    $this->paginate = [
	        'limit' => $limit,
	        'order' => [
	            'Categories.created' => 'DESC'
	        ],
	    ];   
	    
	    // Initialize conditions for filtering
	    $conditions = [];
	    
	    // Get the search keyword from the query parameters
	    $keyword = $this->request->getQuery('keyword');
	    if (!empty($keyword)) {
	        $conditions = [
	            'OR' => [
	                'Categories.name LIKE' => '%' . $keyword . '%',
	                'ParentCategory.name LIKE' => '%' . $keyword . '%',
	            ]
	        ];
	    }
	    
	    // Build the query with named arguments instead of options array
	    $query = $this->Categories->find()
	        ->where($conditions)
	        ->contain(['ParentCategory']);
	    
	    // Paginate the results
	    $categories = $this->paginate($query);
	    
	    // Set the categories and limit for the view
	    $this->set(compact('categories', 'limit'));
	}

    public function add()
	{
	    $layoutTitle = 'Admin::AddCategory';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $categoriesTable = $this->fetchTable('Categories');
	    $categories = $categoriesTable->newEmptyEntity();

	    // Get all categories with their parentId to determine main/sub status
	    $allCategories = $categoriesTable->find()
	        ->select(['id', 'name', 'parentId'])
	        ->order(['Categories.name' => 'ASC'])
	        ->all()
	        ->toArray();

	    // Create parentClientList with all categories, marking main/sub
	    $parentClientList = [];
	    $disabledSubCategories = []; // Array to store sub-category IDs
	    foreach ($allCategories as $category) {
	        $suffix = ($category->parentId === null || $category->parentId == 0) ? ' (Main)' : ' (Sub)';
	        $parentClientList[$category->id] = $category->name . $suffix;
	        // If it's a sub-category, add its ID to disabled list
	        if ($category->parentId !== null && $category->parentId != 0) {
	            $disabledSubCategories[] = $category->id;
	        }
	    }

	    if ($this->request->is('post')) {
	        try {
	            $data = $this->request->getData();

	            // Handle parent category selection
	            if ($data['isTopLevel'] == 1) {
	                $data['parentId'] = 0;
	            } elseif (!empty($data['parentId'])) {
	                // Validate that the selected parentId is not a sub-category
	                $selectedParent = $categoriesTable->find()
	                    ->where(['id' => $data['parentId']])
	                    ->select(['parentId'])
	                    ->first();
	                if ($selectedParent && $selectedParent->parentId !== null && $selectedParent->parentId != 0) {
	                    throw new \Exception('Sub-categories cannot be selected as parents.');
	                }
	            } else {
	                unset($data['parentId']); // Prevent empty string
	            }

	            $categories = $categoriesTable->patchEntity($categories, $data, [
	                'validate' => false,
	                'fieldList' => ['name', 'parentId', 'status']
	            ]);

	            $categories->created = date('Y-m-d H:i:s');
	            $categories->status = 1;

	            if ($categoriesTable->save($categories)) {
	                $this->Flash->success(__('Category added successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            
	            $this->Flash->error(__('Unable to add new category. Please fill all required details.'));
	        } catch (\Exception $e) {
	            $this->Flash->error(__($e->getMessage()));
	        }
	    }
	    
	    $this->set(compact('categories', 'parentClientList', 'disabledSubCategories'));
	}
    
    public function edit($id)
	{
	    $layoutTitle = 'Admin::EditCategory';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $categoriesTable = $this->fetchTable('Categories');
	    $categories = $categoriesTable->find()
	        ->where(['Categories.id' => $id])
	        ->firstOrFail();

	    // Get all categories except the current one for parent selection
	    $allCategories = $categoriesTable->find()
	        ->select(['id', 'name', 'parentId'])
	        ->where(['Categories.id !=' => $id])
	        ->order(['Categories.name' => 'ASC'])
	        ->all()
	        ->toArray();

	    // Create parentClientList with all categories, marking main/sub
	    $parentClientList = [];
	    $disabledSubCategories = []; // Array to store sub-category IDs
	    foreach ($allCategories as $category) {
	        $suffix = ($category->parentId === null || $category->parentId == 0) ? ' (Main)' : ' (Sub)';
	        $parentClientList[$category->id] = $category->name . $suffix;
	        // If it's a sub-category, add its ID to disabled list
	        if ($category->parentId !== null && $category->parentId != 0) {
	            $disabledSubCategories[] = $category->id;
	        }
	    }

	    if ($this->request->is(['patch', 'post', 'put'])) {
	        try {
	            $data = $this->request->getData();

	            // Handle parent category selection
	            if ($data['isTopLevel'] == 1) {
	                $data['parentId'] = 0;
	            } elseif (!empty($data['parentId'])) {
	                // Validate that the selected parentId is not a sub-category
	                $selectedParent = $categoriesTable->find()
	                    ->where(['id' => $data['parentId']])
	                    ->select(['parentId'])
	                    ->first();
	                if ($selectedParent && $selectedParent->parentId !== null && $selectedParent->parentId != 0) {
	                    throw new \Exception('Sub-categories cannot be selected as parents.');
	                }
	            } else {
	                unset($data['parentId']); // Prevent empty string
	            }

	            $categories = $categoriesTable->patchEntity($categories, $data, [
	                'validate' => false,
	                'fieldList' => ['name', 'parentId', 'status']
	            ]);

	            if ($categoriesTable->save($categories)) {
	                $this->Flash->success(__('Category has been updated successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            
	            $this->Flash->error(__('Category could not be saved. Please try again later.'));
	        } catch (\Exception $e) {
	            $this->Flash->error(__($e->getMessage()));
	        }
	    }
	    
	    $this->set(compact('categories', 'parentClientList', 'disabledSubCategories'));
	}
    
    public function delete($id)
	{
	    // Fetch the category by ID
	    $category = $this->Categories->get($id);
	    
	    // Attempt to delete the category
	    if ($this->Categories->delete($category)) {
	        $this->Flash->success(__('Category has been deleted.'));
	    } else {
	        $this->Flash->error(__('Category could not be deleted. Please, try again.'));
	    }
	    
	    // Redirect to the index action
	    return $this->redirect(['action' => 'index']);
	}
    
    public function deleteall()
	{
	    // Retrieve the IDs from the request data
	    $ids = $this->request->getData('ids');

	    if (!empty($ids)) {
	        // Attempt to delete the categories
	        $deletedCount = $this->Categories->deleteAll(['Categories.id IN' => $ids]);

	        if ($deletedCount) {
	            $this->Flash->success(__('Categories have been deleted.'));
	        } else {
	            $this->Flash->error(__('No categories were deleted. Please try again.'));
	        }
	    } else {
	        $this->Flash->error(__('No categories selected for deletion.'));
	    }

	    // Redirect to the index action
	    return $this->redirect(['action' => 'index']);
	}
    
    public function status($id)
	{
	    // Fetch the category by ID
	    $category = $this->Categories->get($id);
	    
	    // Toggle the status
	    $newStatus = $category->status == '1' ? '0' : '1';
	    $msg = $newStatus == '1' ? 'activated' : 'deactivated';

	    // Use the Categories table to update the status
	    $categoriesTable = $this->fetchTable('Categories');
	    
	    // Update the status
	    $category->status = $newStatus; // Set the new status
	    if ($categoriesTable->save($category)) {
	        // Set a success message
	        $this->Flash->success(__('Category has been ' . $msg . ' successfully.'));
	    } else {
	        $this->Flash->error(__('Unable to update the category status.'));
	    }
	    
	    // Redirect to the index action
	    return $this->redirect(['action' => 'index']);
	}
	
}