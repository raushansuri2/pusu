<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\I18n\Time;

/*********************************************************************************************************************/
class ProductsController extends AppController
{
    public function index()
	{
	    $this->viewBuilder()->setLayout('pages');
	    $layoutTitle = 'Ritevet - Pet Store';
	    $url = Router::url('/', true);
	    $breadcum = [
	        'Title' => 'Pet Store',
	        'URL' => [
	            'Home' => $url,
	            'Dashboard' => $url.'users/dashboard',
	            'Pet Store' => $url . 'products/index/'
	        ]
	    ];
	    
	    // Check if the user is logged in
	    if ($this->request->getSession()->read('RitevetUsers.id') == '') {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']); 
	    }
	    
	    // Set pagination settings
	    $this->paginate = [
	        'limit' => 9,
	        'order' => [
	            'Products.created' => 'DESC'
	        ],
	    ];
	    
	    // Build conditions for the query
	    $conditions = [
	        'Products.status' => 1,
	        'Products.unitPrice >' => 0
	    ];

	    // Keyword search
	    $keyword = $this->request->getQuery('keyword');
	    if (!empty($keyword)) {
	        $conditions[] = [
	            'OR' => [
	                "Products.productName LIKE" => '%' . $keyword . '%',
	                "Products.description LIKE" => '%' . $keyword . '%',
	                "Users.firstName LIKE" => '%' . $keyword . '%',
	                "Users.email LIKE" => '%' . $keyword . '%',
	                "Categories.name LIKE" => '%' . $keyword . '%',
	            ]
	        ];
	    }

	    // Category filter
	    $categoryId = $this->request->getQuery('categoryid');
	    if (!empty($categoryId)) {
	        $conditions['Products.categoryId'] = $categoryId;
	    }

	    // Price range filter
	    $minPrice = $this->request->getQuery('min');
	    $maxPrice = $this->request->getQuery('max');
	    if (!empty($minPrice) && !empty($maxPrice)) {
	        $conditions['Products.unitPrice >='] = $minPrice;
	        $conditions['Products.unitPrice <='] = $maxPrice;
	    }

	    // Fetch products with conditions
	    $query = $this->fetchTable('Products')->find('all')
	        ->where($conditions)
	        ->contain(['Users', 'Subcategories', 'Categories', 'Reviews' => function ($q) {
	            return $q->where(['Reviews.status' => 1]);
	        }]);
	    
	    // Paginate the results
	    $products = $this->paginate($query);

	    // Load the Carts model and get the cart list
	    $cartsTable = $this->fetchTable('Carts');
	    $cartList = $cartsTable->find('list', keyField: 'id', valueField: 'productId')
	    ->where(['Carts.userId' => $this->request->getSession()->read('RitevetUsers.id'), 'Carts.orderId IS' => null])
	    ->order(['Carts.id' => 'ASC'])
	    ->toArray();
	    
	    // Load the Categories model and get the categories
	    $categoriesTable = $this->fetchTable('Categories');
	    $categories = $categoriesTable->find('all')
	        ->where(['Categories.status' => 1, 'Categories.parentId' => 0])
	        ->contain([
                'Products' => function ($q) {
                    return $q->where([
                        'Products.unitPrice > ' => 0,
                        'Products.status' => 1
                    ]);
                }
            ])
	        ->select(['Categories.id', 'Categories.name', 'Categories.image'])
	        ->toArray();
	    
	    // Get the maximum price of products
	    $maxPrice = $this->fetchTable('Products')->find()
	        ->select(['max_price' => 'MAX(unitPrice)'])
	        ->first()['max_price'];

	    // Set variables for the view
	    $this->set(compact('layoutTitle', 'breadcum', 'products', 'cartList', 'categories', 'maxPrice'));
	}

	public function own()
	{
	    // Fetch the Products model
	    $productsTable = $this->fetchTable('Products');

	    // Check if the user is logged in
	    if ($this->request->getSession()->read('RitevetUsers.id') == '') {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']); 
	    }

	    $layoutTitle = 'Ritevet - Pet Store';
	    $url = Router::url('/', true);
	    $breadcum = [
	        'Title' => 'Manage Pet Store',
	        'URL' => [
	            'Home' => $url,
	            'Dashboard' => $url.'users/dashboard',
	            'Manage Pet Store' => $url . 'users/product/'
	        ]
	    ];
	    
	    $this->set(compact('layoutTitle', 'breadcum'));
	    $this->viewBuilder()->setLayout('login'); // Set the layout for the view

	    // Build conditions for the query
	    $conditions = [
	        'Products.userId' => $this->request->getSession()->read('RitevetUsers.id'),
	        'Products.unitPrice >' => 0
	    ];

	    // Keyword search
	    $keyword = $this->request->getQuery('keyword');
	    if (!empty($keyword)) {
	        $conditions[] = [
	            'OR' => [
	                "Products.productName LIKE" => '%' . $keyword . '%',
	                "Products.price LIKE" => '%' . $keyword . '%',
	                "Products.description LIKE" => '%' . $keyword . '%',
	                "Subcategories.name LIKE" => '%' . $keyword . '%',
	                "Categories.name LIKE" => '%' . $keyword . '%',
	            ]
	        ];
	    }

	    // Category filter
	    $categoryId = $this->request->getQuery('categoryid');
	    if (!empty($categoryId)) {
	        $conditions['Products.categoryId'] = $categoryId;
	    }

	    // Fetch products with conditions
	    $query = $productsTable->find('all')
	        ->where($conditions)
	        ->contain(['Categories', 'Subcategories']) // Use contain here
	        ->order(['Products.created' => 'DESC']); // Set the order

	    // Paginate the results
	    $products = $this->paginate($query);

	    // Set the products for the view
	    $this->set(compact('products'));
	}
      
    public function add()
	{
	    $this->viewBuilder()->setLayout('login');
	    $layoutTitle = 'Ritevet - Add Product';
	    $url = Router::url('/', true);
	    $breadcum = [
	        'Title' => 'Add Product',
	        'URL' => [
	            'Home' => $url,
	            'Pet Store' => $url . 'products/own/',
	            'Add Product' => $url . 'products/add/'
	        ]
	    ];

	    if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }

	    // Fetch the Users table
	    $usersTable = $this->fetchTable('Users');
	    $user = $usersTable->find()
	        ->where(['Users.id' => $this->request->getSession()->read('RitevetUsers.id')])
	        ->first();

	    if (empty($user->profile_ID_image) || $user->verify_Profile_id == 0) {
	        $this->Flash->error(__('Please Upload your Picture ID Proof and wait for admin approval to add and sell products in the pet store.'));
	        return $this->redirect(['controller' => 'Users', 'action' => 'editprofile']);
	    }

	    // Fetch the Categories table
	    $categoriesTable = $this->fetchTable('Categories');
	    $categories = $categoriesTable->find('list', keyField: 'id', valueField: 'name')
	        ->where(['Categories.status' => 1, 'Categories.parentId' => 0])
	        ->order(['Categories.name' => 'ASC'])
	        ->toArray();

	    $productsTable = $this->fetchTable('Products');
	    $product = $productsTable->newEmptyEntity();

	    if ($this->request->is('post')) {
	        $userImage = $this->request->getData('image'); // This is now an UploadedFile object
	        $data = $this->request->getData();
	        unset($data['image']); // Remove the image from the data array for patching

	        if ($userImage && $userImage->getError() === UPLOAD_ERR_OK) {
	            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $userImage->getClientFilename());
	            $data['image'] = $fileName; // Set the image filename in the data array
	        } else {
	            $data['image'] = ''; // Set to empty if no image is uploaded
	        }

	        // Patch the entity with the data
	        $product = $productsTable->patchEntity($product, $data, ['validate' => false]);
	        $product->userId = $this->request->getSession()->read('RitevetUsers.id');
	        $product->status = 1;
	        $product->added_from = 'WEB';

	        if ($productsTable->save($product)) {
	            // Move the uploaded file if it was uploaded successfully
	            if ($userImage && $userImage->getError() === UPLOAD_ERR_OK) {
	                $path = WWW_ROOT . 'img/uploads/products/';
	                $userImage->moveTo($path . $fileName); // Use moveTo method to move the uploaded file
	                @chmod($path . $fileName, 0777); // Set permissions
	            }

	            $this->Flash->success(__('Product has been added successfully.'));
	            return $this->redirect(['action' => 'own']);
	        } else {
	            $this->Flash->error(__('Unable to add new product, Please try again later.'));
	        }
	    }

	    $this->set(compact('layoutTitle', 'breadcum', 'product', 'categories'));
	}
    
    public function edit($id)
	{
	    $this->viewBuilder()->setLayout('login');
	    $layoutTitle = 'Ritevet - Free Staff';
	    $url = Router::url('/', true);
	    $breadcum = [
	        'Title' => 'Edit Product',
	        'URL' => [
	            'Home' => $url,
	            'Pet Store' => $url . 'products/own/',
	            'Edit Product' => $url . 'products/edit/' . $id
	        ]
	    ];
	    $this->set(compact('layoutTitle', 'breadcum'));

	    // Check if the user is logged in
	    if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }

	    // Fetch the Categories table
	    $categoriesTable = $this->fetchTable('Categories');
	    $categories = $categoriesTable->find('list', keyField: 'id', valueField: 'name')
	        ->where(['Categories.status' => 1, 'Categories.parentId' => 0])
	        ->order(['Categories.name' => 'ASC'])
	        ->toArray();

	    // Fetch the product details
	    $productsTable = $this->fetchTable('Products');
	    $product = $productsTable->find()->where(['Products.id' => $id])->first();

	    // Check if the product exists
	    if (!$product) {
	        $this->Flash->error(__('Product does not exist.'));
	        return $this->redirect(['action' => 'own']);
	    }

	    // Fetch subcategories
	    $subcategory = $categoriesTable->find('list', keyField: 'id', valueField: 'name')
	        ->where(['Categories.status' => 1, 'Categories.parentId' => $product->categoryId])
	        ->order(['Categories.name' => 'ASC'])
	        ->toArray();

	    // Handle form submission
	    if ($this->request->is(['post', 'put'])) {
	        $userImage = $this->request->getData('image'); // This is now an UploadedFile object
	        $oldImage = $product->image; // Store the old image name

	        // Prepare the data for patching
	        $data = $this->request->getData();
	        unset($data['image']); // Remove the image from the data array for patching

	        // Handle file upload
	        if ($userImage && $userImage->getError() === UPLOAD_ERR_OK) {
	            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $userImage->getClientFilename());
	            $data['image'] = $filename; // Set the new filename in the data array
	        } else {
	            // If no new image is uploaded, keep the old image
	            $data['image'] = $oldImage;
	        }

	        // Patch the product entity
	        $product = $productsTable->patchEntity($product, $data, ['validate' => false]);
	        $product->modified = new \Cake\I18n\FrozenTime(); // Use FrozenTime for better date handling

	        // Save the product
	        if ($productsTable->save($product)) {
	            // Move the uploaded file if it was uploaded successfully
	            if ($userImage && $userImage->getError() === UPLOAD_ERR_OK) {
	                $path = WWW_ROOT . 'img/uploads/products/';
	                if ($oldImage) {
	                    @unlink($path . $oldImage); // Remove the old image
	                }
	                $userImage->moveTo($path . $filename); // Use moveTo method to move the uploaded file
	                @chmod($path . $filename, 0777); // Set permissions
	            }
	            $this->Flash->success(__('Product has been updated successfully.'));
	            return $this->redirect(['action' => 'own']);
	        } else {
	            $this->Flash->error(__('Product information could not be saved. Please try again later.'));
	        }
	    }

	    $this->set(compact('product', 'categories', 'subcategory'));
	}

    public function status($id)
	{
	    // Check if the user is logged in
	    if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }

	    // Fetch the Products table
	    $productsTable = $this->fetchTable('Products');

	    // Find the product by ID and user ID
	    $product = $productsTable->find()
	        ->where(['Products.id' => $id, 'Products.userId' => $this->request->getSession()->read('RitevetUsers.id')])
	        ->first();

	    // Check if the product exists
	    if (!$product) {
	        $this->Flash->error(__('Product not found.'));
	        return $this->redirect(['controller' => 'Products', 'action' => 'own']);
	    }

	    // Toggle the status
	    $status = $product->status == '1' ? '0' : '1';
	    $msg = $status == '1' ? 'activated' : 'deactivated';

	    // Update the product status
	    $product->status = $status;
	    $product->modified = new \Cake\I18n\FrozenTime(); // Use FrozenTime for better date handling

	    if ($productsTable->save($product)) {
	        $this->Flash->success(__('Product has been ' . $msg . ' successfully.'));
	    } else {
	        $this->Flash->error(__('Unable to update product status. Please try again.'));
	    }

	    return $this->redirect(['controller' => 'Products', 'action' => 'own']);
	}
    
    public function details($id)
	{
	    $this->viewBuilder()->setLayout('pages');
	    $layoutTitle = 'Ritevet - Product Details';
	    $url = Router::url('/', true);
	    $breadcum = [
	        'Title' => 'Product Details',
	        'URL' => [
	            'Home' => $url,
	            'Product Details' => $url . 'products/index/',
	        ]
	    ];

	    // Check if the user is logged in
	    if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }

	    // Fetch the product details
	    $productsTable = $this->fetchTable('Products');
	    $product = $productsTable->find()
	        ->where(['Products.id' => base64_decode($id)])
	        ->contain(['Users'])
	        ->first();

	    // Check if the product exists
	    if (!$product) {
	        $this->Flash->error(__('Product does not exist.'));
	        return $this->redirect(['controller' => 'Products', 'action' => 'index']);
	    }

	    // Fetch the Reviews table
	    $reviewsTable = $this->fetchTable('Reviews');

	    // Handle review submission
	    if ($this->request->is('post')) {
	        $reviewData = [
	            'productId' => $product->id,
	            'reviewFrom' => $this->request->getSession()->read('RitevetUsers.id'),
	            'message' => $this->request->getData('message'),
	            'star' => $this->request->getData('star'),
	            'status' => 1,
	            'added_from' => 'WED',
	            'created' => new \Cake\I18n\FrozenTime(), // Use FrozenTime for better date handling
	        ];

	        $reviewEntity = $reviewsTable->newEntity();
	        $reviewEntity = $reviewsTable->patchEntity($reviewEntity, $reviewData, ['validate' => false]);

	        // Save the review and check for success
	        if ($reviewsTable->save($reviewEntity)) {
	            // Calculate the average rating
	            $avgRatingQuery = $reviewsTable->find()
	                ->select(['averagerating' => $reviewsTable->find()->func()->avg('star')])
	                ->where(['productId' => $product->id]);

	            $avgRatingResult = $avgRatingQuery->first();
	            $averageRating = $avgRatingResult ? round($avgRatingResult->averagerating, 1) : 0;

	            // Update the product's average rating
	            $product->AVGRating = $averageRating;

	            if (!$productsTable->save($product)) {
	                $this->Flash->error(__('Unable to update product rating.'));
	            }
	        } else {
	            $this->Flash->error(__('Unable to save your review. Please try again.'));
	        }

	        return $this->redirect(['action' => 'details', base64_encode($product->id)]);
	    }

	    // Pagination for reviews
	    $this->paginate = [
	        'limit' => 5,
	        'order' => ['Reviews.created' => 'DESC'],
	    ];

	    $query = $reviewsTable->find('all')
	        ->where(['Reviews.productId' => $product->id, 'Reviews.status' => 1]);

	    $totalReviewCount = $query->count();
	    $reviews = $this->paginate($query);

	    // Query to count the number of orders
	    $orderesTable = $this->fetchTable('Orderes');
	    $orderCount = $orderesTable->find()
	        ->contain(['Carts' => ['Products']])
	        ->where(['Orderes.userId' => $this->request->getSession()->read('RitevetUsers.id')])
	        ->andWhere(['Orderes.orderStatus' => 3])
	        ->andWhere(['Products.userId' => $product->userId])
	        ->count();

	    // Fetch categories
	    $categoriesTable = $this->fetchTable('Categories');
	    $categories = $categoriesTable->find('all')
	        ->where(['Categories.status' => '1', 'Categories.parentId' => 0])
	        ->contain(['Products'])
	        ->select(['Categories.id', 'Categories.name', 'Categories.image', 'Categories.bigImage'])
	        ->toArray();

	    // Fetch cart list
	    $cartsTable = $this->fetchTable('Carts');
	    $cartList = $cartsTable ->find('list', keyField: 'id', valueField: 'productId')
	        ->where(['Carts.userId' => $this->request->getSession()->read('RitevetUsers.id'), 'Carts.orderId IS NULL'])
	        ->order(['Carts.id' => 'ASC'])
	        ->toArray();

	    $this->set(compact('layoutTitle', 'breadcum', 'product', 'categories', 'cartList', 'reviews', 'totalReviewCount', 'orderCount'));
	}
	
    public function cart($id = null)
	{
	    $this->viewBuilder()->setLayout('pages'); // Set the layout for the view
	    $layoutTitle = 'Ritevet - Shopping Cart';
	    $url = Router::url('/', true);
	    $breadcum = [
	        'Title' => 'Shopping Cart',
	        'URL' => [
	            'Home' => $url,
	            'Dashboard' => $url.'users/dashboard',
	            'Shopping Cart' => $url . 'products/cart/',
	        ]
	    ];
	    
	    // Check if the user is logged in
	    if ($this->request->getSession()->read('RitevetUsers.id') == '') {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']); 
	    }
	    
	    // Fetch the Carts model using fetchTable
	    $cartsTable = $this->fetchTable('Carts');

	    // If an ID is provided, delete the corresponding cart item
	    if ($id) {
	        $cartItem = $cartsTable->find()->where(['Carts.id' => $id])->first();
	        if ($cartItem) {
	            $cartsTable->delete($cartItem);
	        }
	        return $this->redirect(['controller' => 'Products', 'action' => 'cart']);
	    }
	    
	    // Fetch the user's cart items
	    $carts = $cartsTable->find('all')
	        ->where([
	            'Carts.userId' => $this->request->getSession()->read('RitevetUsers.id'),
	            'Carts.orderId IS' => null
	        ])
	        ->contain(['Products'])
	        ->order(['Carts.id' => 'ASC'])
	        ->toArray();
	        
	    // Set variables for the view
	    $this->set(compact('layoutTitle', 'breadcum', 'carts'));
	}
	
	public function addtocart($productId)
    {
        // Set the response type to JSON early
        $this->response = $this->response->withType('application/json');

        // Check if the user is logged in
        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Load tables using fetchTable()
        $productsTable = $this->fetchTable('Products');
        $cartsTable = $this->fetchTable('Carts');

        // Find the product by ID
        $product = $productsTable->find()
            ->where(['Products.id' => $productId])
            ->first();

        // Check if the product exists
        if (!$product) {
            throw new NotFoundException('Product not found');
        }

        // Check if the product is already in the cart
        $cart = $cartsTable->find()
            ->where([
                'Carts.productId' => $productId,
                'Carts.userId' => $this->request->getSession()->read('RitevetUsers.id'),
                'Carts.orderId IS' => null
            ])
            ->first();

        // Determine the price
        $price = $product->specialPrice != 0 ? $product->specialPrice : $product->unitPrice;

        // Prepare data for the cart
        $cartData = [
            'productOwner' => $product->userId,
            'categoryId' => $product->categoryId,
            'subCategoryId' => $product->subCategory,
            'productId' => $product->id,
            'quantity' => 1,
            'unitPrice' => $price,
            'TotalPrice' => 1 * $price,
            'added_from' => 'WEB',
            'userId' => $this->request->getSession()->read('RitevetUsers.id'),
            'status' => 1,
        ];

        if (!$cart) {
            // Create a new cart entry
            $cart = $cartsTable->newEmptyEntity();
            $cart = $cartsTable->patchEntity($cart, $cartData, ['validate' => false]);
        } else {
            // Update the existing cart entry
            $cart = $cartsTable->patchEntity($cart, $cartData, ['validate' => false]);
            $cart->modified = date('Y-m-d H:i:s');
        }

        // Save the cart entry
        if ($cartsTable->save($cart)) {
            // Count the number of items in the cart
            $cartCount = $cartsTable->find()
                ->where([
                    'Carts.userId' => $this->request->getSession()->read('RitevetUsers.id'),
                    'Carts.orderId IS' => null
                ])
                ->count();

            $response = [
                'status' => 'Success',
                'quantity' => $product->quantity,
                'cartCount' => $cartCount
            ];
        } else {
            $response = [
                'status' => 'Fail',
                'msg' => 'Can\'t add product'
            ];
        }

        // Return the response as JSON
        return $this->response->withStringBody(json_encode($response));
    }
	
	public function addtocart2($productId, $quantity)
    {
        // Set the response type to JSON early
        $this->response = $this->response->withType('application/json');

        // Check if the user is logged in
        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Load tables using fetchTable()
        $productsTable = $this->fetchTable('Products');
        $cartsTable = $this->fetchTable('Carts');

        // Find the product by ID
        $product = $productsTable->find()
            ->where(['Products.id' => $productId])
            ->first();

        // Check if the product exists
        if (!$product) {
            throw new NotFoundException('Product not found');
        }

        // Check if the product is already in the cart
        $cart = $cartsTable->find()
            ->where([
                'Carts.productId' => $productId,
                'Carts.userId' => $this->request->getSession()->read('RitevetUsers.id'),
                'Carts.orderId IS' => null
            ])
            ->first();

        // Adjust quantity if it exceeds available stock
        if ($quantity > $product->quantity) {
            $quantity = $product->quantity;
        }

        // Determine the price
        $price = $product->specialPrice != 0 ? $product->specialPrice : $product->unitPrice;

        // Prepare cart data
        $cartData = [
            'productOwner' => $product->userId,
            'categoryId' => $product->categoryId,
            'subCategoryId' => $product->subCategory,
            'productId' => $product->id,
            'quantity' => $quantity,
            'unitPrice' => $price,
            'TotalPrice' => $quantity * $price,
        ];

        if (!$cart) {
            // Create a new cart entry
            $cart = $cartsTable->newEmptyEntity();
            $cart = $cartsTable->patchEntity($cart, $cartData, ['validate' => false]);
            $cart->userId = $this->request->getSession()->read('RitevetUsers.id');
            $cart->status = 1;
            $cart->added_from = 'WEB';
            $cart->created = date('Y-m-d H:i:s');
            $cart->modified = date('Y-m-d H:i:s');
        } else {
            // Update the existing cart entry
            $cart = $cartsTable->patchEntity($cart, $cartData, ['validate' => false]);
            $cart->modified = date('Y-m-d H:i:s');
        }

        // Save the cart item
        if (!$cartsTable->save($cart)) {
            $response = [
                'status' => 'Fail',
                'msg' => 'Unable to add or update cart item'
            ];
            return $this->response->withStringBody(json_encode($response));
        }

        // Calculate totals
        $cartItems = $cartsTable->find()
            ->where([
                'Carts.userId' => $this->request->getSession()->read('RitevetUsers.id'),
                'Carts.orderId IS' => null
            ])
            ->all()
            ->toArray();

        $mainTotal = 0;
        $finalTotal = 0;
        foreach ($cartItems as $item) {
            if ($productId == $item->productId) {
                $mainTotal += ($item->unitPrice * $item->quantity);
            }
            $finalTotal += ($item->unitPrice * $item->quantity);
        }

        // Calculate the cart count
        $cartCount = $cartsTable->find()
            ->where([
                'Carts.userId' => $this->request->getSession()->read('RitevetUsers.id'),
                'Carts.orderId IS' => null
            ])
            ->count();

        // Prepare the response
        $response = [
            'status' => 'Success',
            'quantity' => $quantity,
            'mainTotal' => $mainTotal,
            'finalTotal' => $finalTotal,
            'cartCount' => $cartCount
        ];

        // Return the response as JSON
        return $this->response->withStringBody(json_encode($response));
    }
	
	public function subcategory($id)
    {
        // Validate the ID parameter
        if (!is_numeric($id) || $id <= 0) {
            $response = [
                'first' => '<option value="">Select Sub Category</option>',
                'second' => ''
            ];
            return $this->response->withType('application/json')
                                 ->withStringBody(json_encode($response));
        }
    
        // Use Table Locator to get the Categories model
        $categoriesTable = $this->fetchTable('Categories');
    
        // Fetch subcategories using named arguments for find('list')
        $subcategories = $categoriesTable->find('list', 
            keyField: 'id',
            valueField: 'name'
        )
        ->where(['Categories.parentId' => $id])
        ->order(['Categories.name' => 'ASC'])
        ->toArray();
    
        // Prepare the response
        $response = [
            'first' => '<option value="">Select Sub Category</option>',
            'second' => ''
        ];
    
        // Build option tags for subcategories
        foreach ($subcategories as $key => $value) {
            $response['first'] .= '<option value="' . h($key) . '">' . h($value) . '</option>';
            $response['second'] .= '<option value="' . h($key) . '">' . h($value) . '</option>';
        }
    
        // Return JSON response
        return $this->response->withType('application/json')
                              ->withStringBody(json_encode($response));
    }
	
	public function payment()
	{
	    $this->viewBuilder()->setLayout('pages');
	    $layoutTitle = 'Ritevet - Payment';
	    $url = $this->request->getAttribute('webroot');
	    $breadcum = [
	        'Title' => 'Payment',
	        'URL' => [
	            'Home' => $url,
	            'Payment' => $url . 'products/payment/',
	        ]
	    ];

	    $userId = $this->request->getSession()->read('RitevetUsers.id');
	    if (empty($userId)) {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }

	    $productsTable = $this->fetchTable('Products');
	    $ordersTable = $this->fetchTable('Orderes');
	    $cartsTable = $this->fetchTable('Carts');

	    $carts = $cartsTable->find()
	        ->where(['Carts.userId' => $userId, 'Carts.orderId IS' => null])
	        ->contain(['Products' => ['Users']])
	        ->orderBy(['Carts.id' => 'ASC'])
	        ->all()
	        ->toArray();

	    if (empty($carts)) {
	        $this->Flash->error(__('Cart is empty or items have already been processed.'));
	        return $this->redirect(['controller' => 'Products', 'action' => 'cart']);
	    }

	    $productsByOwner = [];
	    $totalData = [
	        'QUANTITY' => 0,
	        'PRICE' => 0,
	        'TOTALAMOUNT' => 0,
	        'ADMINFEES' => 0,
	        'SALETAX' => 0,
	        'SHIPPING' => 0,
	    ];

	    foreach ($carts as $cart) {
	        $owner = $cart['productOwner'];
	        if (!isset($productsByOwner[$owner])) {
	            $productsByOwner[$owner] = [];
	        }
	        $productsByOwner[$owner][] = $cart;

	        $productPrice = $cart->unitPrice;
	        $totalData['QUANTITY'] += $cart->quantity;
	        $totalData['PRICE'] += $productPrice;
	        $totalData['TOTALAMOUNT'] += $cart->TotalPrice;
	    }
	    
	    $totalData['ADMINFEES'] = $totalData['TOTALAMOUNT'] * 0.16;

	    if ($this->request->is('post')) {
	        $session = $this->request->getSession();
	        $lockKey = "payment_processing_{$userId}";
	        $lockTimestamp = $session->read($lockKey);
	        
	        if ($lockTimestamp && (time() - $lockTimestamp) < 30) {
	            $this->Flash->error(__('Payment is already being processed. Please wait a moment and try again.'));
	            return $this->redirect(['controller' => 'Products', 'action' => 'payment']);
	        }
	        
	        $session->write($lockKey, time());

	        try {
	            $totalData['SALETAX'] = $this->request->getData('saleTax');
	            $totalData['SHIPPING'] = $this->request->getData('shipping');
	            $total = $this->request->getData('total');
	            $paidAmount = ceil($totalData['TOTALAMOUNT'] + $totalData['ADMINFEES']) + $totalData['SALETAX'] + $totalData['SHIPPING'];
	           
	            if (trim($paidAmount) != trim($total)) {
	                $session->delete($lockKey);
	                $this->Flash->error(__('An error occurred: check total price.'));
	                return $this->redirect(['controller' => 'Products', 'action' => 'payment']);
	            }

	            $cartCheck = $cartsTable->find()
	                ->where(['Carts.userId' => $userId, 'Carts.orderId IS' => null])
	                ->count();
	            if ($cartCheck === 0) {
	                $session->delete($lockKey);
	                $this->Flash->error(__('Items have already been processed.'));
	                return $this->redirect(['controller' => 'Products', 'action' => 'orders']);
	            }

	            $fields = [
	                'action' => 'charge',
	                'name' => $this->request->getData('name'),
	                'stripeToken' => $this->request->getData('stripeToken'),
	                'amount' => $paidAmount * 100,
	                'userId' => $userId,
	                'description' => 'This payment for buy product by userId:' . $userId
	            ];

	            $connection = \Cake\Datasource\ConnectionManager::get('default');
	            $connection->begin();

	            $Furl = $url . "strip_master/strip_master/StripeServices.php";
	            $ch = curl_init();
	            curl_setopt($ch, CURLOPT_URL, $Furl);
	            curl_setopt($ch, CURLOPT_POST, true);
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	            curl_setopt($ch, CURLOPT_HTTPHEADER, [
	                'Content-Type: application/x-www-form-urlencoded',
	            ]);
	            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
	            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	            
	            $result = curl_exec($ch);
	            if ($result === false) {
	                $error = curl_error($ch);
	                curl_close($ch);
	                $connection->rollback();
	                $session->delete($lockKey);
	                $this->Flash->error(__('Payment processing failed: ') . $error);
	                return $this->redirect(['controller' => 'Products', 'action' => 'payment']);
	            }
	            curl_close($ch);

	            $DecondeResult = json_decode($result);
	            if (!isset($DecondeResult->status) || $DecondeResult->status !== "success") {
	                $connection->rollback();
	                $session->delete($lockKey);
	                $this->Flash->error(__('Card information is wrong or payment failed.'));
	                return $this->redirect(['controller' => 'Products', 'action' => 'payment']);
	            }

	            $ORDERID = [];
	            $hasSameOwner = count($productsByOwner) === 1;

	            foreach ($productsByOwner as $owner => $products) {
	                $ownerData = [
	                    'order_number' => 'ORD-' . strtoupper(substr(hash('md5', uniqid(mt_rand(), true)), 0, 10)),
	                    'userId' => $userId,
	                    'quantity' => 0,
	                    'price' => 0,
	                    'TotalAmount' => 0,
	                    'adminFees' => $totalData['ADMINFEES'],
	                    'sale_tax' => $totalData['SALETAX'],
	                    'shipping' => $totalData['SHIPPING'],
	                    'paidAmount' => $DecondeResult->amount / 100 ?? 0,
	                    'currency' => strtoupper($DecondeResult->currency ?? 'USD'),
	                    'transactionID' => $DecondeResult->tokenId ?? '',
	                    'paymentStatus' => 'paid',
	                    'paymentTrouugh' => 'WEB',
	                    'shippingName' => $this->request->getData('shippingName'),
	                    'ShippingMobile' => $this->request->getData('ShippingMobile'),
	                    'shippingAddress' => $this->request->getData('shippingAddress'),
	                    'shippingZipcode' => $this->request->getData('shippingZipcode'),
	                    'orderStatus' => 1,
	                    'created' => new \Cake\I18n\DateTime(),
	                    'modified' => new \Cake\I18n\DateTime(),
	                ];

	                foreach ($products as $product) {
	                    $price = $product->unitPrice;
	                    $ownerData['quantity'] += $product->quantity;
	                    $ownerData['price'] += $price;
	                    $ownerData['TotalAmount'] += $product->TotalPrice;
	                }

	                $ownerOrder = $ordersTable->newEmptyEntity();
	                $ownerOrder = $ordersTable->patchEntity($ownerOrder, $ownerData, ['validate' => false]);
	                
	                if (!$ordersTable->save($ownerOrder)) {
	                    $connection->rollback();
	                    $session->delete($lockKey);
	                    $this->Flash->error(__('Failed to save order.'));
	                    return $this->redirect(['controller' => 'Products', 'action' => 'payment']);
	                }

	                foreach ($products as $product) {
	                    $prod = $productsTable->get($product->productId);
	                    $productsTable->updateAll(
	                        ['quantity' => $prod->quantity - $product->quantity],
	                        ['id' => $product->productId]
	                    );
	                }

	                $cartsTable->updateAll(
	                    ['orderId' => $ownerOrder->id, 'modified' => new \Cake\I18n\DateTime()],
	                    ['userId' => $userId, 'productOwner' => $owner, 'orderId IS' => null]
	                );

	                $ORDERID[] = $ownerOrder->id;
	            }

	            $connection->commit();
	            $this->getTaxRate($this->request->getData('shippingZipcode'), null, $ORDERID);
	            $this->Flash->success(__('Order is processed successfully.'));
	            $session->delete($lockKey);
	            return $this->redirect(['controller' => 'Products', 'action' => 'orders']);
	            
	        } catch (\Exception $e) {
	            $connection->rollback();
	            $session->delete($lockKey);
	            $this->Flash->error(__('An error occurred during payment processing: ') . $e->getMessage());
	            return $this->redirect(['controller' => 'Products', 'action' => 'payment']);
	        }
	    }

	    $this->set(compact('layoutTitle', 'breadcum', 'carts'));
	}
	
	public function orders()
	{
		$this->viewBuilder()->setLayout('login');

		$layoutTitle = 'Ritevet - My Orders';
	    $url = Router::url('/', true);
	    $breadcum = [
	        'Title' => 'Manage My Orders',
	        'URL' => [
	            'Home' => $url,
	            'Dashboard' => $url . 'users/dashboard',
	            'Receive Orders' => $url . 'products/receiveorders/'
	        ]
	    ];

	    // Check if the user is logged in
	    if ($this->request->getSession()->read('RitevetUsers.id') === null) {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }	    

	    // Fetch the Orders table
	    $ordersTable = $this->fetchTable('Orderes');

	    // Set pagination settings
	    $this->paginate = [
	        'limit' => 10,
	        'order' => [
	            'Orderes.id' => 'DESC'
	        ],
	    ];

	    // Build the query conditions
	    $conditions = [
	        'Orderes.userId' => $this->request->getSession()->read('RitevetUsers.id')
	    ];

	    // Handle keyword search
	    $keyword = $this->request->getQuery('keyword');
	    if (!empty($keyword)) {
	        $conditions[] = [
	            'OR' => [
	                'Orderes.order_number LIKE' => '%' . $keyword . '%',
	                // Uncomment and modify the following lines if you want to search in related Products
	                // 'Products.productName LIKE' => '%' . $keyword . '%',
	                // 'Products.price LIKE' => '%' . $keyword . '%',
	                // 'Products.description LIKE' => '%' . $keyword . '%',
	            ]
	        ];
	    }

	    // Fetch the orders based on conditions
	    $query = $ordersTable->find('all')->where($conditions);
	    $orders = $this->paginate($query);

	    // Set the orders to the view
	    $this->set(compact('layoutTitle', 'breadcum', 'orders'));
	}
	
	public function productsOrders()
	{
		$this->viewBuilder()->setLayout('login');

		$layoutTitle = 'Ritevet - MyProducts Orders';
	    $url = Router::url('/', true);
	    $breadcum = [
	        'Title' => 'Manage My Products Orders',
	        'URL' => [
	            'Home' => $url,
	            'Dashboard' => $url.'users/dashboard',
	            'Orders Place' => $url . 'products/orders/'
	        ]
	    ];

	    // Check if the user is logged in
	    if ($this->request->getSession()->read('RitevetUsers.id') === null) {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }

	    // Fetch the Orders table
	    $ordersTable = $this->fetchTable('Orderes');

	    // Set pagination settings
	    $this->paginate = [
	        'limit' => 10,
	        'order' => ['Orderes.id' => 'DESC'],
	    ];

	    // Build the query conditions
	    $conditions = [
	        'Carts.productOwner' => $this->request->getSession()->read('RitevetUsers.id')
	    ];

	    // Handle keyword search
	    $keyword = $this->request->getQuery('keyword');
	    if (!empty($keyword)) {
	        $conditions[] = [
	            'OR' => [
	                'Orderes.order_number LIKE' => '%' . $keyword . '%',
	            ]
	        ];
	    }

	    // Fetch the orders based on conditions
	    $query = $ordersTable->find('all')
	        ->contain(['Carts'])
	        ->distinct(['Orderes.id'])
	        ->where($conditions);

	    $orders = $this->paginate($query);

	    // Set the orders to the view
	    $this->set(compact('layoutTitle', 'breadcum', 'orders'));
	}
    
    public function trackOrder()
	{
		$this->viewBuilder()->setLayout('login');

		$layoutTitle = 'Ritevet - Track Order';
	    $url = Router::url('/', true);
	    $breadcum = [
	        'Title' => 'Track Order',
	        'URL' => [
	            'Home' => $url,
	            'Dashboard' => $url.'users/dashboard',
	            'Track Order' => $url . 'products/orders/'
	        ]
	    ];

	    // Check if the user is logged in
	    if ($this->request->getSession()->read('RitevetUsers.id') === null) {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }

	    // Handle form submission
	    if ($this->request->is('post')) {
	        $orderNumber = trim($this->request->getData('orderNo'));

	        if (empty($orderNumber)) {
	            $this->Flash->error(__('You should enter an order number.'));
	            return $this->redirect(['controller' => 'Products', 'action' => 'trackOrder']);
	        }

	        // Fetch the Orders table
	        $ordersTable = $this->fetchTable('Orderes');
	        $status = $ordersTable->find('list', [
	            'conditions' => ['order_number' => $orderNumber],
	            'keyField' => 'id',
	            'valueField' => 'orderStatus'
	        ])->first();

	        if ($status) {
	            switch ($status) {
	                case 1:
	                    $this->Flash->success(__('Order status is In Review.'));
	                    break;
	                case 2:
	                    $this->Flash->success(__('Order status is Shipped.'));
	                    break;
	                case 3:
	                    $this->Flash->success(__('Order status is Delivered.'));
	                    break;
	                default:
	                    $this->Flash->error(__('Order status is unknown.'));
	            }
	        } else {
	            $this->Flash->error(__('Order not found.'));
	        }

	        return $this->redirect(['controller' => 'Products', 'action' => 'trackOrder']);
	    }

	    $this->set(compact('layoutTitle', 'breadcum'));
	}
	
	public function orderdetails($id)
	{
	    $this->viewBuilder()->setLayout('login');

	    $layoutTitle = 'Ritevet - Orders Details';
	    $url = Router::url('/', true);
	    $breadcum = [
	        'Title' => 'Orders Details',
	        'URL' => [
	            'Home' => $url,
	            'Manage Orders' => $url . 'products/orders/',
	            'Order Details' => ''
	        ]
	    ];

	    // Check if the user is logged in
	    if ($this->request->getSession()->read('RitevetUsers.id') === null) {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }

	    // Fetch the Orders table
	    $ordersTable = $this->fetchTable('Orderes');

	    // Fetch order details
	    $orderList = $ordersTable->find()
	        ->where([
	            'Orderes.id' => $id,
	            'OR' => [
	                ['Orderes.userId' => $this->request->getSession()->read('RitevetUsers.id')],
	                ['Carts.productOwner' => $this->request->getSession()->read('RitevetUsers.id')]
	            ]
	        ])
	        ->contain(['Carts' => ['Products' => ['Subcategories', 'Categories']]])
	        ->toArray();

	    if (!empty($orderList)) {
	        $this->set(compact('layoutTitle', 'breadcum', 'orderList'));
	    } else {
	        $this->Flash->error(__('Order no longer available.'));
	        return $this->redirect(['controller' => 'Products', 'action' => 'orders']);
	    }
	}
	
	public function updateOrderStatus()
	{
		// Restrict to POST requests
        $this->request->allowMethod(['post']);

	    // Check if the user is logged in
        if ($this->request->getSession()->read('RitevetUsers.id') === null) {
            return $this->response->withType('application/json')
                ->withStatus(401)
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'User not logged in. Please log in and try again.',
                ]));
        }

	    // Retrieve data from the request
	    $orderId = $this->request->getData('orderId');
	    $orderStatus = $this->request->getData('statusId');
	    $shipCode = $this->request->getData('shippingCode') ?: null; // Use null if empty
	    $shippingDate = '';

	    // Fetch the Orders table
	    $ordersTable = $this->fetchTable('Orderes');
	    $order = $ordersTable->find()->where(['Orderes.id' => $orderId])->first();

	    // Set shipping date based on order status
	    if ($orderStatus == 2) {
	        $shippingDate = date('Y-m-d H:i:s');
	    } else {
	        $shippingDate = $order ? $order->shippingDate : null;
	    }

	    // Check if the order exists
	    if ($order) {
	        // Update the order status
	        $order->orderStatus = $orderStatus;
	        $order->shippingCode = $shipCode;
	        $order->shippingDate = $shippingDate;
	        $order->modified = date('Y-m-d H:i:s');

	        if ($ordersTable->save($order)) {
	            $response = [
	                'status' => 'Success',
	                'msg' => __('Order status changed successfully.')
	            ];
	            $this->Flash->success(__('Order status changed successfully.'));
	        } else {
	            $response = [
	                'status' => 'Fail',
	                'msg' => __('Unable to update order status. Please try again.')
	            ];
	            $this->Flash->error(__('Unable to update order status. Please try again.'));
	        }
	    } else {
	        $response = [
	            'status' => 'Fail',
	            'msg' => __('Order no longer available.')
	        ];
	        $this->Flash->error(__('Order no longer available.'));
	    }

	    // Return JSON response
	    return $this->response->withType('application/json')
	                           ->withStringBody(json_encode($response));
	}
	
	public function confirmDelivery()
	{
	    // Check if the request is a POST request
	    if ($this->request->is('post')) {
	        $delivered = $this->request->getData('delivered');
	        $orderId = $this->request->getData('orderId');

	        // Fetch the Orders table
	        $ordersTable = $this->fetchTable('Orderes');
	        $order = $ordersTable->find()->where(['id' => $orderId])->first();

	        if ($order) {
	            $msg = $delivered == '1' ? 'Order marked as delivered successfully.' : 'Order marked as not delivered successfully.';
	            $order->deliveryConfirm = $delivered;

	            if ($ordersTable->save($order)) {
	                $this->delivered($order->id); // Assuming this method exists and handles post-delivery actions

	                $this->Flash->success(__($msg));
	                $response = [
	                    'status' => 'Success',
	                    'msg' => $msg
	                ];
	            } else {
	                $this->Flash->error(__('Error updating order status'));
	                $response = [
	                    'status' => 'Fail',
	                    'msg' => 'Error updating order status.'
	                ];
	            }
	        } else {
	            $this->Flash->error(__('Order not found'));
	            $response = [
	                'status' => 'Fail',
	                'msg' => 'Order not found.'
	            ];
	        }

	        // Return JSON response
	        return $this->response->withType('application/json')
	                               ->withStringBody(json_encode($response));
	    }
	}
	
	public function delivered($orderId)
	{
	    // Check if the user is logged in
	    if ($this->request->getSession()->read('RitevetUsers.id') === null) {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }

	    // Fetch the Orders table
	    $ordersTable = $this->fetchTable('Orderes');
	    $order = $ordersTable->find()
	        ->where(['Orderes.id' => $orderId])
	        ->contain(['Carts' => function ($q) {
	            return $q->contain(['ProductOwner' => function ($q) {
	                return $q->select(['id', 'wallet']);
	            }]);
	        }])
	        ->first(); // Use first() to get a single order

	    $response = []; // Initialize response array

	    if ($order) {
	        if ($order->orderStatus == 3 && $order->deliveryConfirm == 1) {
	            $productOwner = $order->cart->product_owner;

	            // Fetch the Users table
	            $usersTable = $this->fetchTable('Users');
	            $productOwnerUser  = $usersTable->get($productOwner->id); // Get the user entity

	            // Update the user's wallet
	            $productOwnerUser ->wallet += $order->cart->TotalPrice;
	            $productOwnerUser ->modified = date('Y-m-d H:i:s');

	            if ($usersTable->save($productOwnerUser )) {
	                $response['status'] = 'Success';
	                $response['msg'] = 'User  wallet updated successfully';
	            } else {
	                $response['status'] = 'Fail';
	                $response['msg'] = 'Error updating user wallet.';
	            }
	        } else {
	            $response['status'] = 'Fail';
	            $response['msg'] = 'Check order status and delivery confirmation.';
	        }
	    } else {
	        $response['status'] = 'Fail';
	        $response['msg'] = 'Order not found.';
	    }

	    // Return JSON response
	    return $this->response->withType('application/json')
	                           ->withStringBody(json_encode($response));
	}
    
    public function getTaxRate($zip, $requestId = null, array $orderId = []) 
	{
	    // API LIVE URL
	   // $url = "https://api.taxjar.com/v2/rates/" . $zip;
	    
	    // API SANDBOX URL
	    $url = "https://api.sandbox.taxjar.com/v2/rates/" . $zip;
	    
	    $ch = curl_init($url);

	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, [
	        'Authorization: Bearer fae6e131110ae6c428c5f90af563c396', // sandbox
	       // 'Authorization: Bearer 9327adc807c37cc4806b0ae284d751db', // live
	        'Content-Type: application/json'
	    ]);

	    $response = curl_exec($ch);

	    // Handle cURL errors
	    if (curl_errno($ch)) {
	        $error_msg = curl_error($ch);
	        curl_close($ch);
	        $result = ['status' => 'error', 'message' => $error_msg];
	        echo json_encode($result);
	        exit;
	    }

	    curl_close($ch);

	    $result = json_decode($response, true);

	    // Check for API errors
	    if (isset($result['status']) && $result['status'] != 200) {
	        $result = ['status' => 'error', 'message' => $result['error']];
	        echo json_encode($result);
	        exit;
	    }
	    
	    // Fetch the TaxRates table
	    $taxRatesTable = $this->fetchTable('TaxRates');

	    // Prepare data for saving
	    if (count($orderId) > 0) 
	    {
	        $taxRateEntity = $taxRatesTable->newEntity([
	            'order_id' => implode(',', $orderId),
	            'zip' => $zip,
	            'country' => $result['rate']['country'],
	            'country_rate' => $result['rate']['country_rate'],
	            'state' => $result['rate']['state'],
	            'state_rate' => $result['rate']['state_rate'],
	            'county' => $result['rate']['county'],
	            'county_rate' => $result['rate']['county_rate'],
	            'city' => $result['rate']['city'],
	            'city_rate' => $result['rate']['city_rate'],
	            'combined_district_rate' => $result['rate']['combined_district_rate'],
	            'combined_rate' => $result['rate']['combined_rate'],
	            'freight_taxable' => $result['rate']['freight_taxable'],
	            'added_from' => 'WEB'
	        ]);

	        if ($taxRatesTable->save($taxRateEntity)) {
	            // Return the result
	        } else {
	            // Handle error (optional)
	            echo json_encode(['status' => 'error', 'message' => json_encode($taxRateEntity->getErrors())]);
	        }
	        
	    } 
	    elseif (!empty($requestId)) 
	    {
	        $taxRateEntity = $taxRatesTable->newEntity([
	            'request_id' => $requestId,
	            'zip' => $zip,
	            'country' => $result['rate']['country'],
	            'country_rate' => $result['rate']['country_rate'],
	            'state' => $result['rate']['state'],
	            'state_rate' => $result['rate']['state_rate'],
	            'county' => $result['rate']['county'],
	            'county_rate' => $result['rate']['county_rate'],
	            'city' => $result['rate']['city'],
	            'city_rate' => $result['rate']['city_rate'],
	            'combined_district_rate' => $result['rate']['combined_district_rate'],
	            'combined_rate' => $result['rate']['combined_rate'],
	            'freight_taxable' => $result['rate']['freight_taxable'],
	            'added_from' => 'WEB'
	        ]);

	        if ($taxRatesTable->save($taxRateEntity)) {
	            // Return the result
	        } else {
	            // Handle error (optional)
	            echo json_encode(['status' => 'error', 'message' => json_encode($taxRateEntity->getErrors())]);
	        }
	    }
	    
	    echo json_encode(['status' => 'success', 'rate' => $result['rate']]);
	    if ($this->request->is('ajax')) {
	        exit;
	    }
	}
    
    public function getTotalShippingCost($destinationZIPCode) 
	{
	    $userId = $this->request->getSession()->read('RitevetUsers.id');
	    if (empty($userId)) {
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }

	    // Fetch the Products and Carts tables
	    $cartsTable = $this->fetchTable('Carts');
	    $productsTable = $this->fetchTable('Products');

	    $carts = $cartsTable->find('all')
	        ->where(['Carts.userId' => $userId, 'Carts.orderId IS' => NULL])
	        ->contain(['Products' => ['Users']])
	        ->order(['Carts.id' => 'ASC'])
	        ->toArray();

	    if (empty($carts)) {
	        $this->Flash->error(__('Cart is empty.'));
	        return $this->redirect(['controller' => 'Products', 'action' => 'cart']);
	    }
	    
	    $packages = $this->calculatePackageDimensions($carts);
	    
	    // Fetch USPS data
	    $uspsTable = $this->fetchTable('Usps');
	    $uspsData = $uspsTable->find()->first();
	    
	    $apiKey = $uspsData->access_token ?? null; // Use null coalescing operator

	    $url = 'https://apis-tem.usps.com/prices/v3/base-rates/search'; // live => https://apis.usps.com/prices/v3/base-rates/search
	    
	    $totalCost = 0; // Initialize total cost

	    foreach ($packages as $package) {
	        $data = [
	            "originZIPCode" => $package['ownerZipcode'],
	            "destinationZIPCode" => $destinationZIPCode,
	            "weight" => (int) $package['weight'],
	            "length" => (int) $package['length'],
	            "width" => (int) $package['width'],
	            "height" => (int) $package['height'],
	            "mailClass" => "USPS_GROUND_ADVANTAGE",
	            "processingCategory" => "NONSTANDARD",
	            "rateIndicator" => "SP",
	            "destinationEntryFacilityType" => "NONE",
	            "priceType" => "COMMERCIAL",
	            "mailingDate" => date('Y-m-d'),
	        ];
	        
	        // Initialize cURL
	        $ch = curl_init($url);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, [
	            'Content-Type: application/json',
	            'Authorization: Bearer ' . $apiKey,
	        ]);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

	        $response = curl_exec($ch);
	        if ($response === false) {
	            // Handle error
	            return $this->response->withType('application/json')
	                                   ->withStringBody(json_encode(['error' => curl_error($ch)]));
	        } else {
	            $shippingResponse = json_decode($response, true);
	            if (isset($shippingResponse['totalBasePrice'])) {
	                $totalCost += $shippingResponse['totalBasePrice']; // Accumulate total cost
	            } else {
	                return $this->response->withType('application/json')
	                                       ->withStringBody(json_encode(['message' => $shippingResponse['error']['message']]));
	            }
	        }

	        curl_close($ch);
	    }

	    return $this->response->withType('application/json')
	                           ->withStringBody(json_encode(['totalCost' => $totalCost]));
	}

    function calculatePackageDimensions($cartItems) 
    {
        $sellerDimensions = [];
        
        foreach ($cartItems as $item) {
            $sellerId      = $item['productOwner'];
            $sellerZipcode = $item['product']['user']['zipCode'];
            
            // Initialize seller dimensions if not already set
            if (!isset($sellerDimensions[$sellerId])) {
                $sellerDimensions[$sellerId] = [
                    'totalWeight' => 0,
                    'maxLength' => 0,
                    'maxWidth' => 0,
                    'maxHeight' => 0,
                    'ownerZipcode' => $sellerZipcode,
                ];
            }
    
            // Update total weight
            $sellerDimensions[$sellerId]['totalWeight'] += $item['product']['weight'];
    
            // Update maximum dimensions
            $sellerDimensions[$sellerId]['maxLength'] = max($sellerDimensions[$sellerId]['maxLength'], $item['product']['length']);
            $sellerDimensions[$sellerId]['maxWidth']  = max($sellerDimensions[$sellerId]['maxWidth'], $item['product']['width']);
            $sellerDimensions[$sellerId]['maxHeight'] = max($sellerDimensions[$sellerId]['maxHeight'], $item['product']['height']);
        }
    
        // Prepare the final result array
        $result = [];
        foreach ($sellerDimensions as $sellerId => $dimensions) {
            $result[] = [
                'productOwner' => $sellerId,
                'ownerZipcode' => $dimensions['ownerZipcode'],
                'weight' => $dimensions['totalWeight'],
                'length' => $dimensions['maxLength'],
                'width' => $dimensions['maxWidth'],  
                'height' => $dimensions['maxHeight'],
            ];
        }
    
        return $result;
    }
    
}