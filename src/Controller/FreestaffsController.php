<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;

/******************************************************************************
******************************************************************************/
class FreestaffsController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
    }

    public function index()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Free Stuffs';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Free Stuffs',
            'URL' => [
                'Home' => $url,
                'Dashboard' => $url.'users/dashboard',
                'Free Stuff' => $url . 'freestaffs/index/'
            ]
        ];

        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Build conditions array
        $conditions = [
            'Products.status' => 1,
            'Products.unitPrice' => 0
        ];

        $keyword = $this->request->getQuery('keyword');
        if (!empty($keyword)) {
            $conditions[] = [
                'OR' => [
                    "Products.productName LIKE" => "%{$keyword}%",
                    "Products.description LIKE" => "%{$keyword}%",
                    "Products.SKU LIKE" => "%{$keyword}%",
                    "Users.firstName LIKE" => "%{$keyword}%",
                    "Users.email LIKE" => "%{$keyword}%",
                    "Categories.name LIKE" => "%{$keyword}%",
                    "Subcategories.name LIKE" => "%{$keyword}%",
                ]
            ];
        }

        $categoryId = $this->request->getQuery('categoryid');
        if (!empty($categoryId)) {
            $conditions['Products.categoryId'] = $categoryId;
        }

        $productsTable = $this->fetchTable('Products');
        $query = $productsTable->find()
            ->where($conditions)
            ->contain([
                'Users',
                'Subcategories',
                'Categories',
                'Reviews' => function ($q) {
                    return $q->where(['Reviews.status' => 1]);
                }
            ])
            ->order(['Products.id' => 'DESC']);

        $freestaffs = $this->paginate($query, [
            'limit' => 9,
            'order' => ['Products.id' => 'DESC'],
        ]);

        $cartsTable = $this->fetchTable('Carts');
        $cartList = $cartsTable->find('list', keyField: 'id', valueField: 'productId')
		    ->where([
		        'Carts.userId' => $this->request->getSession()->read('RitevetUsers.id'),
		        'Carts.orderId IS' => null
		    ])
		    ->order(['Carts.id' => 'ASC'])
		    ->toArray();

        $categoriesTable = $this->fetchTable('Categories');
        $categories = $categoriesTable->find()
            ->where([
                'Categories.status' => 1,
                'Categories.parentId' => 0
            ])
            ->contain([
                'Products' => function ($q) {
                    return $q->where([
                        'Products.unitPrice' => 0,
                        'Products.status' => 1
                    ]);
                }
            ])
            ->select([
                'Categories.id',
                'Categories.name',
                'Categories.image',
            ])
            ->toArray();

        $this->set(compact('layoutTitle', 'breadcum', 'freestaffs', 'categories', 'cartList'));
    }
    
    public function add()
	{
	    $this->viewBuilder()->setLayout('login');
	    $layoutTitle = 'Ritevet - Add Stuff';
	    $url = Router::url('/', true);
	    $breadcum = [
	        'Title' => 'Add Stuff',
	        'URL' => [
	            'Home' => $url,
	            'Free Stuffs' => $url . 'freestaffs/index/',
	            'Add Stuff' => $url . 'freestaffs/add/'
	        ]
	    ];

	    if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }

	    $usersTable = $this->fetchTable('Users');
	    $user = $usersTable->find()
	        ->where(['Users.id' => $this->request->getSession()->read('RitevetUsers.id')])
	        ->first();

	    if (empty($user->profile_ID_image) || $user->verify_Profile_id == 0) {
	        $this->Flash->error(__('Please Upload your Picture ID Proof and wait for admin approval to add and sell products in pet store.'));
	        return $this->redirect(['controller' => 'Users', 'action' => 'editprofile']);
	    }

	    $categoriesTable = $this->fetchTable('Categories');
	    $categories = $categoriesTable->find('list', keyField: 'id', valueField: 'name')
		    ->where(['Categories.status' => 1, 'Categories.parentId' => 0])
		    ->order(['Categories.name' => 'ASC'])
		    ->toArray();

	    $productsTable = $this->fetchTable('Products');
	    $freestaff = $productsTable->newEmptyEntity();

	    if ($this->request->is('post')) 
	    {
	        $userImage = $this->request->getData('image'); // This is now an UploadedFile object
	        $data = $this->request->getData();
	        unset($data['image']);

	        if ($userImage && $userImage->getError() === UPLOAD_ERR_OK) {
	            $fileName = time() . str_replace(
	                [' ', "'", '"', "(", ")", "[", "]", "!", "@", "#", "$", "%", "^", "&", "*"],
	                '',
	                stripslashes($userImage->getClientFilename())
	            );
	            $data['image'] = $fileName;
	        } else {
	            $data['image'] = '';
	        }

	        $freestaff = $productsTable->patchEntity($freestaff, $data, ['validate' => false]);
	        $freestaff->userId = $this->request->getSession()->read('RitevetUsers.id');
	        $freestaff->status = 1;
	        $freestaff->added_from = 'WEB';
	        
	        if ($productsTable->save($freestaff)) {
	            if ($userImage && $userImage->getError() === UPLOAD_ERR_OK) {
	                $path = WWW_ROOT . 'img/uploads/products/';
	                $userImage->moveTo($path . $fileName); // Use moveTo method to move the uploaded file
	                @chmod($path . $fileName, 0777);
	            }

	            $this->Flash->success(__('Stuff added successfully.'));
	            return $this->redirect(['action' => 'own']);
	        } else {
	            $this->Flash->error(__('Unable to add new stuff, Please try again later.'));
	        }
	    }

	    $this->set(compact('layoutTitle', 'breadcum', 'freestaff', 'categories'));
	}
    
    public function own()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'Ritevet - Free Stuff';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Manage Free Stuff',
            'URL' => [
                'Home' => $url,
                'Dashboard' => $url.'users/dashboard',
                'Manage Free Stuff' => $url . 'users/product/'
            ]
        ];

        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Build conditions array
        $conditions = [
            'Products.userId' => $this->request->getSession()->read('RitevetUsers.id'),
            'Products.unitPrice' => 0
        ];

        // Keyword search
        $keyword = $this->request->getQuery('keyword');
        if (!empty($keyword)) {
            $conditions[] = [
                'OR' => [
                    "Products.productName LIKE" => "%{$keyword}%",
                    "Products.description LIKE" => "%{$keyword}%",
                    "Products.SKU LIKE" => "%{$keyword}%",
                    "Categories.name LIKE" => "%{$keyword}%",
                    "Subcategories.name LIKE" => "%{$keyword}%",
                ]
            ];
        }

        // Load Products with query
        $productsTable = $this->fetchTable('Products');
        $query = $productsTable->find()
            ->where($conditions)
            ->contain(['Subcategories', 'Categories'])
            ->order(['Products.id' => 'DESC']);

        // Paginate the query using the Paginator component
        $freestuffs = $this->paginate($query, [
            'limit' => 10,
            'order' => ['Products.id' => 'DESC'],
        ]);

        // Set view variables
        $this->set(compact('layoutTitle', 'breadcum', 'freestuffs'));
    }
	
	public function edit($id)
	{
	    $this->viewBuilder()->setLayout('login');
	    $layoutTitle = 'Ritevet - Edit Stuff';
	    $url = Router::url('/', true);
	    $breadcum = [
	        'Title' => 'Edit Stuff',
	        'URL' => [
	            'Home' => $url,
	            'Free Stuff' => $url . 'freestaffs/index/',
	            'Edit Stuff' => $url . 'freestaffs/edit/' . $id // Include the ID in the URL
	        ]
	    ];

	    // Check if the user is logged in
	    if ($this->request->getSession()->read('RitevetUsers.id') == '') {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }

	    // Load the Categories model
	    $categoriesTable = $this->getTableLocator()->get('Categories');
	    $categories = $categoriesTable->find('list', keyField: 'id', valueField: 'name')
	        ->where(['Categories.status' => 1, 'Categories.parentId' => 0])
	        ->order(['Categories.name' => 'ASC'])
	        ->toArray();

	    // Load the Products model
	    $productsTable = $this->getTableLocator()->get('Products');
	    $freeStaff = $productsTable->get($id); // Use get() to fetch the entity directly

	    // Fetch subcategories based on the selected category
	    $subcategory = $categoriesTable->find('list', keyField: 'id', valueField: 'name')
	        ->where(['Categories.status' => 1, 'Categories.parentId' => $freeStaff->categoryId])
	        ->order(['Categories.name' => 'ASC'])
	        ->toArray();

	    if ($this->request->is(['patch', 'post', 'put'])) {
	        // Get the uploaded file
	        $Uimage1 = $this->request->getData('image');

	        // Initialize the data array
	        $data = $this->request->getData();
	        $data['modified'] = date('Y-m-d H:i:s');

	        // Handle the uploaded image
	        if ($Uimage1 && $Uimage1->getError() === UPLOAD_ERR_OK) {
	            $fileName = time() . str_replace(
	                [' ', "'", '"', "(", ")", "[", "]", "!", "@", "#", "$", "%", "^", "&", "*"],
	                '',
	                stripslashes($Uimage1->getClientFilename())
	            );
	            $data['image'] = $fileName; // Set the sanitized filename

	            // Delete the old file if it exists
	            $oldfilename1 = $freeStaff->image ?? null; // Get the old filename if it exists
	            $path = WWW_ROOT . 'img/uploads/products/';
	            if ($oldfilename1) {
	                @unlink($path . $oldfilename1); // Delete the old file
	            }

	            // Move the uploaded file to the desired location
	            $Uimage1->moveTo($path . $fileName);
	        } else {
	            // If no new image is uploaded, keep the old filename
	            $data['image'] = $freeStaff->image; // Retain the old image filename
	        }

	        // Patch the entity with the new data
	        $freeStaff = $productsTable->patchEntity($freeStaff, $data, ['validate' => false]);

	        if ($productsTable->save($freeStaff)) {
	            $this->Flash->success(__('Stuff updated successfully.'));
	            return $this->redirect(['action' => 'own']);
	        } else {
	            $this->Flash->error(__('Stuff information could not be saved. Please try again later.'));
	        }
	    }

	    $this->set(compact('layoutTitle', 'breadcum', 'freeStaff', 'categories', 'subcategory'));
	}
        
    public function status($id)
	{
	    // Check if the user is logged in
	    if ($this->request->getSession()->read('RitevetUsers.id') == '') {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }

	    // Load the Products model
	    $productsTable = $this->getTableLocator()->get('Products');
	    $freeStuff = $productsTable->find()
	        ->where(['Products.id' => $id, 'Products.userId' => $this->request->getSession()->read('RitevetUsers.id')])
	        ->first();

	    // Check if the item exists
	    if ($freeStuff) {
	        // Toggle the status
	        $status = ($freeStuff->status == '1') ? '0' : '1';
	        $msg = ($freeStuff->status == '1') ? 'deactivated' : 'activated';

	        // Update the status
	        $freeStuff->status = $status;
	        $freeStuff->modified = date('Y-m-d H:i:s');

	        if ($productsTable->save($freeStuff)) {
	            $this->Flash->success(__('Stuff has been ' . $msg . ' successfully.'));
	        } else {
	            $this->Flash->error(__('Unable to update status. Please try again.'));
	        }
	    } else {
	        $this->Flash->error(__('Stuff not found.'));
	    }

	    return $this->redirect(['controller' => 'Freestaffs', 'action' => 'own']);
	}
    
    public function details($id)
	{
	    $this->viewBuilder()->setLayout('pages');
	    $layoutTitle = 'Ritevet - Stuff Details';
	    $url = Router::url('/', true);
	    $breadcum = [
	        'Title' => 'Stuff Details',
	        'URL' => [
	            'Home' => $url,
	            'Stuff Details' => $url . 'freestaffs/index/',
	        ]
	    ];
	    
	    // Check if the user is logged in
	    if ($this->request->getSession()->read('RitevetUsers.id') == '') {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']); 
	    }
	    
	    // Fetch the Products and Reviews models using fetchTable
	    $productsTable = $this->fetchTable('Products');
	    $reviewsTable = $this->fetchTable('Reviews');
	    $freeStaff = $productsTable->find()
	        ->where(['Products.id' => base64_decode($id)])
	        ->contain(['Users'])
	        ->first();
	    
	    // Handle review submission
	    if ($this->request->is('post')) {
	        $reviewFrom = $this->request->getSession()->read('RitevetUsers.id');
	        
	        $reviewData = [
	            'productId' => $freeStaff->id,
	            'reviewFrom' => $reviewFrom,
	            'message' => $this->request->getData('message'),
	            'star' => $this->request->getData('star'),
	            'status' => 1,
	            'added_from' => 'WEB',
	        ];
	        
	        $reviewEntity = $reviewsTable->newEntity();
	        $reviewEntity = $reviewsTable->patchEntity($reviewEntity, $reviewData, ['validate' => false]);
	        
	        // Save the review and check for success
	        if ($reviewsTable->save($reviewEntity)) {
	            // Calculate the average rating
	            $avgRatingQuery = $reviewsTable->find()
	                ->select(['averagerating' => $reviewsTable->find()->func()->avg('star')])
	                ->where(['productId' => $freeStaff->id]);
	            
	            $avgRatingResult = $avgRatingQuery->first();
	            $averageRating = $avgRatingResult ? round($avgRatingResult->averagerating, 1) : 0;
	            
	            // Update the product's average rating
	            $freeStaff->AVGRating = $averageRating;
	            
	            if (!$productsTable->save($freeStaff)) {
	                $this->Flash->error(__('Unable to update product rating.'));
	            }
	        } else {
	            $this->Flash->error(__('Unable to save your review. Please try again.'));
	        }
	        
	        return $this->redirect(['action' => 'details', base64_encode($freeStaff->id)]);
	    }
	    
	    // Fetch the reviews with pagination
	    $query = $reviewsTable->find('all')
	        ->where(['Reviews.productId' => $freeStaff->id, 'Reviews.status' => 1])
	        ->order(['Reviews.created' => 'DESC'])
	        ->contain(['Reviewfroms']); // Use contain here

	    // Paginate the query
	    $this->paginate = [
	        'limit' => 5,
	    ];
	    
	    // Set the paginated data
	    $reviews = $this->paginate($query);
	    $this->set('reviews', $reviews); // Set the paginated reviews for the view
	    $totalReviewCount = $query->count();

	    // Query to count the number of orders
	    $ordersTable = $this->fetchTable('Orderes'); // Assuming the model is named Orders
	    $orderCount = $ordersTable->find()
	        ->contain(['Carts' => ['Products']])
	        ->where(['Orderes.userId' => $this->request->getSession()->read('RitevetUsers.id')])
	        ->andWhere(['Products.userId' => $freeStaff->userId])
	        ->count();
	    
	    if (!empty($freeStaff)) {
	        $categoriesTable = $this->fetchTable('Categories');
	        $categories = $categoriesTable->find('all')
	            ->where([
	                'Categories.status' => 1,
	                'Categories.parentId' => 0
	            ])
	            ->contain([
	                'Products' => function ($q) {
	                    return $q->where([
	                        'Products.unitPrice' => 0,
	                        'Products.status' => 1
	                    ]);
	                }
	            ])
	            ->select([
	                'Categories.id',
	                'Categories.name',
	                'Categories.image',
	            ])
	            ->toArray();
	        
	        $cartsTable = $this->fetchTable('Carts');
	        $cartList = $cartsTable->find('list', keyField: 'id', valueField: 'productId')
			    ->where(['Carts.userId' => $this->request->getSession()->read('RitevetUsers.id'), 'Carts.orderId IS' => null])
			    ->order(['Carts.id' => 'ASC'])
			    ->toArray();
	        
	        $this->set(compact('layoutTitle', 'breadcum', 'freeStaff', 'categories', 'cartList', 'reviews', 'totalReviewCount', 'orderCount'));
	    } else {
	        $this->Flash->error(__('Stuff does not exist.'));
	        return $this->redirect(['controller' => 'Freestaffs', 'action' => 'index']);
	    }
	}
	
}