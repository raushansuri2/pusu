<?php
// Assuming $article is passed from the controller with fields: title, image, content, category_id, created, etc.
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
    body {
        background-color: #f5f7fa;
        font-family: 'Roboto', sans-serif;
        color: #333;
        margin: 0;
        padding: 0;
    }
    .breadcrumb {
        background-color: transparent;
        padding: 15px 0;
        margin-bottom: 20px;
        font-size: 14px;
    }
    .breadcrumb li {
        display: inline;
        color: #7f8c8d;
    }
    .breadcrumb li a {
        color: #17a2b8;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .breadcrumb li a:hover {
        color: #e67e22;
    }
    .breadcrumb li.active {
        color: #2c3e50;
        font-weight: 500;
    }
    .breadcrumb li + li:before {
        content: " / ";
        color: #7f8c8d;
        padding: 0 5px;
    }
    .article-header {
        margin-bottom: 30px;
    }
    .article-header h1 {
        font-size: 36px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 15px 0;
    }
    .article-meta {
        font-size: 14px;
        color: #7f8c8d;
        margin-bottom: 10px;
    }
    .article-meta .category {
        color: #17a2b8;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        background-color: rgba(23, 162, 184, 0.1);
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
    }
    .article-meta .byline a {
        color: #e67e22;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .article-meta .byline a:hover {
        color: #d35400;
    }
    .article-image {
        margin-bottom: 30px;
        float: right;
        padding: 15px;
        max-width: 50%;
    }
    .article-image img {
        width: 100%;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        transition: opacity 0.3s ease;
    }
    .article-image:hover img {
        opacity: 0.9;
    }
    .article-content {
        max-width: 800px;
        margin: 0;
    }
    .article-content p {
        font-size: 16px;
        line-height: 1.8;
        color: #7f8c8d;
        margin-bottom: 20px;
    }
    .article-content .content-subtitle {
        font-size: 20px;
        font-weight: 600;
        color: #2c3e50;
        margin: 25px 0 15px 0;
        display: block;
    }
    .article-content a {
        color: #17a2b8;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .article-content a:hover {
        color: #e67e22;
    }
    .article-footer {
        margin-top: 40px;
        font-size: 14px;
        color: #7f8c8d;
    }
    .article-footer .reviewed {
        margin-bottom: 10px;
    }
    .back-link {
        display: inline-block;
        margin-top: 30px;
        padding: 10px 20px;
        background-color: #e67e22;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }
    .back-link:hover {
        background-color: #d35400;
        color: #fff;
    }
    @media (max-width: 768px) {
        .article-header h1 {
            font-size: 28px;
        }
        .article-image {
            float: none;
            max-width: 100%;
            padding: 0;
            margin-bottom: 20px;
        }
        .breadcrumb {
            font-size: 12px;
        }
    }
</style>

<section class="col-sm-9">
    <ol class="breadcrumb">
        <li><a href="<?= $this->Url->build('/') ?>">Home</a></li>
        <li><a href="<?= $this->Url->build('/pages/elearn') ?>">E-Learning</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Articles', 'action' => 'index', $article->category_id]) ?>">
            <?= isset($article->elearning_category->name) ? h($article->elearning_category->name) : 'Uncategorized' ?></a></li>
        <li class="active"><?= h($article->title) ?></li>
    </ol>
    <div class="article-header">
        <h1 class="page-header"><?= h($article->title) ?></h1>
        <div class="article-meta">
            <span class="category"><?= isset($article->elearning_category->name) ? h($article->elearning_category->name) : 'Uncategorized' ?></span>
            | <span class="byline">Posts by: <a href="#">Admin</a></span>
        </div>
    </div>
    <div class="article-image">
        <img src="<?= $this->Url->build('/img/uploads/elearning_articles/' . $article->image) ?>" alt="<?= h($article->title) ?>">
    </div>
    <div class="article-content">
        <?php if (!empty($article->content)): ?>
            <?= $this->Text->autoParagraph(h($article->content)) ?>
        <?php else: ?>
            <p>No content available for this article.</p>
        <?php endif; ?>
    </div>
    <div class="article-footer">
        <?php if ($article->created): ?>
            <div class="reviewed">Reviewed on: <span><?= $article->created->format('l, F j, Y') ?></span></div>
        <?php endif; ?>
        <a href="<?= $this->Url->build(['controller' => 'Articles', 'action' => 'index', $article->category_id]) ?>" class="back-link">Back to Articles</a>
    </div>
</section>