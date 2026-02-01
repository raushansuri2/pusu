<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;

class VideosController extends AppController
{
    // Define class properties for models
    private $ElearningVideos;
    private $ElearningCategories;

    public function initialize(): void
    {
        parent::initialize();
        // Load models using TableLocator
        $this->ElearningVideos = $this->getTableLocator()->get('ElearningVideos');
        $this->ElearningCategories = $this->getTableLocator()->get('ElearningCategories');
    }

    public function index($categoryId = null)
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - E-Learning Videos';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'E-Learning Videos',
            'URL' => [
                'Home' => $url,
                'E-Learning Videos' => $url . 'pages/elearn',
                'Videos' => null
            ]
        ];

        // Build query with status filter
        $query = $this->ElearningVideos->find()
            ->where(['ElearningVideos.status' => 1]);

        // Apply category filter if provided
        if ($categoryId) {
            $query->where(['ElearningVideos.category_id' => $categoryId]);
            $category = $this->ElearningCategories->get($categoryId);
        }

        // Search filter
        if ($this->getRequest()->is('get') && !empty($this->getRequest()->getQuery('search'))) {
            $search = $this->getRequest()->getQuery('search');
            $query->where(['ElearningVideos.title LIKE' => "%$search%"]);
        }

        // Set up pagination
        $this->paginate = [
            'limit' => 20,
            'order' => ['ElearningVideos.created' => 'desc'],
        ];

        // Paginate results using the query
        $videos = $this->paginate($query->contain(['ElearningCategories']));

        // Set variables for template
        $this->set(compact('layoutTitle', 'breadcum', 'videos', 'categoryId', 'category'));
    }
}