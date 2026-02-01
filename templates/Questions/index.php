<?php
// Load Bootstrap 5 using CakePHP 3.1 HtmlHelper
$this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', ['block' => true]);
$this->Html->script('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', ['block' => true]);
?>

<style>
    /* Header Styling */
    .quiz-header {
        margin-bottom: 35px;
        padding: 20px;
        background: linear-gradient(to right, #f8f9fa, #e9ecef);
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .quiz-header h1.page-header {
        font-size: 36px;
        font-weight: 700;
        color: #222;
        margin: 0 0 10px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
    }
    .quiz-meta {
        font-size: 15px;
        color: #555;
    }
    .quiz-meta .category {
        color: #1e90ff;
        font-weight: 600;
    }
    .quiz-meta .byline a {
        color: #ff4500;
        text-decoration: none;
        transition: color 0.3s;
    }
    .quiz-meta .byline a:hover {
        color: #1e90ff;
        text-decoration: underline;
    }

    /* Question Container Styling */
    .question-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        padding: 20px;
    }

    /* Question Card Styling - Centered */
    .question-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        padding: 30px;
        width: 100%;
        max-width: 800px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin: auto; /* Added for additional centering */
    }
    .question-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }
    .question-title {
        font-size: 20px;
        color: #333;
        font-weight: 600;
        margin-bottom: 15px;
        background: linear-gradient(to right, #f8f9fa, #fff);
        padding: 10px;
        border-radius: 5px;
        border-bottom: 1px solid #eee;
    }
    .answer-option {
        display: block;
        margin: 10px 0;
        padding: 10px;
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .answer-option:hover {
        background-color: #ff4500;
        color: #fff;
    }
    .answer-option input {
        margin-right: 10px;
    }
    .question-item {
        margin-bottom: 30px;
    }

    /* Submit Button Styling */
    .submit-btn {
        display: block;
        width: 200px;
        margin: 30px auto 0;
        padding: 12px;
        background: #1e90ff;
        color: #fff;
        border: none;
        border-radius: 30px;
        font-size: 16px;
        font-weight: 600;
        text-align: center;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: background 0.3s ease, transform 0.3s ease;
    }
    .submit-btn:hover {
        background: #ff4500;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(255, 69, 0, 0.4);
    }

    /* No Questions Message */
    .no-questions {
        font-size: 18px;
        color: #666;
        text-align: center;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        max-width: 800px;
        width: 100%;
        margin: auto; /* Added for centering */
    }

    /* Flash Message */
    .flash-message {
        text-align: center;
        margin-bottom: 25px;
    }
</style>

<!-- Header -->
<div class="quiz-header">
    <h1 class="page-header"><?= 'E-Learning Questions' ?></h1>
    <div class="quiz-meta">
        <span class="category">
            <?= isset($category->name) ? h($category->name) : 'Uncategorized' ?>
        </span>
        | <span class="byline">Quiz by: <a href="#">Admin</a></span>
    </div>
</div>

<!-- Flash Messages -->
<div class="flash-message">
    <?= $this->Flash->render() ?>
</div>

<!-- Question Container -->
<div class="question-container">
    <?php if (empty($questions)): ?>
        <p class="no-questions">No active questions available for this category.</p>
    <?php else: ?>
        <?= $this->Form->create(null, ['url' => ['action' => 'submit']]) ?>
        <?= $this->Form->hidden('category_id', ['value' => $category->id]) ?>

        <div class="question-card">
            <?php foreach ($questions as $index => $question): ?>
                <div class="question-item">
                    <h3 class="question-title"><?= ($index + 1) . '. ' . h($question->question_text) ?></h3>
                    <label class="answer-option">
                        <input type="radio" name="answers[<?= $question->id ?>]" value="option_a" required="required">
                        A. <?= h($question->option_a) ?>
                    </label>
                    <label class="answer-option">
                        <input type="radio" name="answers[<?= $question->id ?>]" value="option_b">
                        B. <?= h($question->option_b) ?>
                    </label>
                    <label class="answer-option">
                        <input type="radio" name="answers[<?= $question->id ?>]" value="option_c">
                        C. <?= h($question->option_c) ?>
                    </label>
                    <label class="answer-option">
                        <input type="radio" name="answers[<?= $question->id ?>]" value="option_d">
                        D. <?= h($question->option_d) ?>
                    </label>
                </div>
            <?php endforeach; ?>

            <?= $this->Form->button('Submit Quiz', ['class' => 'submit-btn']) ?>
        </div>

        <?= $this->Form->end() ?>
    <?php endif; ?>
</div>