<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Query\SelectQuery;

class ArticlesController extends AppController
{
    public function index(?int $categoryId = null): void
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - E-Learning Articles';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'E-Learning Articles',
            'URL' => [
                'Home' => $url,
                'E-Learning Articles' => $url . 'pages/elearn',
                'Articles' => null
            ]
        ];
    
        // Load model using fetchTable
        $elearningArticles = $this->fetchTable('ElearningArticles');
    
        // Define base query
        $query = $elearningArticles->find()
            ->contain(['ElearningCategories'])
            ->where(['ElearningArticles.status' => 1])
            ->order(['ElearningArticles.created' => 'desc']);
    
        // Apply category filter if provided
        if ($categoryId !== null) {
            $query->where(['ElearningArticles.category_id' => $categoryId]);
        }
    
        // Handle search query
        $searchQuery = $this->getRequest()->getQuery('q');
        if (!empty($searchQuery)) {
            $query->where([
                'OR' => [
                    'ElearningArticles.title LIKE' => '%' . $searchQuery . '%',
                    'ElearningArticles.content LIKE' => '%' . $searchQuery . '%'
                ]
            ]);
        }
    
        // Check total articles count
        $totalArticles = $query->count();
    
        // Set pagination options
        $this->paginate = [
            'limit' => 20,
        ];
    
        // Paginate the query
        $articles = $this->paginate($query);
    
        $category = null;
        if ($categoryId !== null) {
            // Use named arguments for get() in category retrieval
            $category = $elearningArticles->ElearningCategories->get($categoryId);
        }
    
        // Pass variables to the view
        $this->set(compact('layoutTitle', 'breadcum', 'articles', 'categoryId', 'category', 'totalArticles', 'searchQuery'));
    }

    public function view(?int $id = null): void
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Article Details';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Article Details',
            'URL' => [
                'Home' => $url,
                'Article Details' => $url . 'pages/elearn'
            ]
        ];
    
        // Load model using fetchTable
        $elearningArticles = $this->fetchTable('ElearningArticles');
        $article = $elearningArticles->get($id, contain: ['ElearningCategories']);
    
        if (!$article) {
            throw new NotFoundException(__('Article not found'));
        }
    
        $this->set(compact('layoutTitle', 'breadcum', 'article'));
    }
}