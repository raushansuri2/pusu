<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Http\Exception\NotFoundException;

class QuestionsController extends AppController
{
    // Define class properties for models
    private $ElearningQuestions;
    private $ElearningCategories;

    public function initialize(): void
    {
        parent::initialize();
        // Load models using TableLocator
        $this->ElearningQuestions = $this->getTableLocator()->get('ElearningQuestions');
        $this->ElearningCategories = $this->getTableLocator()->get('ElearningCategories');
    }

    public function index($categoryId = null)
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - E-Learning Questions';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'E-Learning Questions',
            'URL' => [
                'Home' => $url,
                'E-Learning' => $url . 'pages/elearn',
                'Questions' => null
            ]
        ];

        if (!$categoryId) {
            $this->Flash->error(__('No category selected.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'elearn']);
        }

        $category = $this->ElearningCategories->get($categoryId);

        $questions = $this->ElearningQuestions->find()
            ->where([
                'category_id' => $categoryId,
                'status' => 1
            ])
            ->toArray();

        $this->set(compact('layoutTitle', 'breadcum', 'category', 'questions'));
    }

    public function submit()
    {
        if ($this->getRequest()->is('post')) {
            $userAnswers = $this->getRequest()->getData('answers'); // Use getData() for CakePHP 5
            $categoryId = $this->getRequest()->getData('category_id');

            $questions = $this->ElearningQuestions->find()
                ->where([
                    'category_id' => $categoryId,
                    'status' => 1
                ])
                ->all();

            $score = 0;
            $total = $questions->count();

            // Map submitted options to A, B, C, D
            $optionMap = [
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D'
            ];

            foreach ($questions as $question) {
                $userAnswer = !empty($userAnswers[$question->id]) ? $userAnswers[$question->id] : null;
                $userAnswerLetter = $userAnswer && isset($optionMap[$userAnswer]) ? $optionMap[$userAnswer] : null;
                if ($userAnswerLetter === $question->correct_answer) {
                    $score++;
                }
            }

            $this->Flash->success(__("You scored {0} out of {1}!", $score, $total));
            return $this->redirect(['action' => 'index', $categoryId]);
        }
    }
}