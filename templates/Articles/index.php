<?php
// Assuming $articles, $categoryId, $category, and $searchQuery are passed from the controller
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Arial', sans-serif;
    }
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    .breadcrumb {
        background-color: transparent;
        padding: 15px 0;
        margin-bottom: 20px;
        font-size: 14px;
        border-bottom: 1px solid #eee;
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
    .articles-header {
        text-align: left;
        margin-bottom: 40px;
        padding: 30px 0;
        background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
        border-radius: 10px;
    }
    .articles-header h2 {
        font-size: 36px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
    }
    .articles-header p {
        font-size: 16px;
        color: #7f8c8d;
        margin-top: 10px;
        font-style: italic;
    }
    .search-container {
        margin-bottom: 30px;
        max-width: 400px;
        position: relative;
    }
    .search-container input[type="text"] {
        width: 100%;
        padding: 14px 20px;
        border: 1px solid #ddd;
        border-radius: 25px;
        font-size: 16px;
        outline: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        background-color: #fff;
    }
    .search-container input[type="text"]:focus {
        border-color: #17a2b8;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.2);
    }
    .search-container input[type="text"]::placeholder {
        color: #bdc3c7;
        font-style: italic;
    }
    .article-row {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        border-left: 4px solid #17a2b8;
        position: relative;
        overflow: hidden;
    }
    .article-row:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }
    .article-row::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(120deg, rgba(23, 162, 184, 0.03), rgba(230, 126, 34, 0.03));
        z-index: -1;
    }
    .article-image {
        flex: 0 0 auto;
        margin-right: 25px;
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        width: 280px;
        height: 180px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .article-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease, opacity 0.3s ease;
        display: block;
    }
    .article-image:hover img {
        transform: scale(1.05);
        opacity: 0.9;
    }
    .article-content {
        flex: 1;
    }
    .article-category {
        font-size: 14px;
        font-weight: 500;
        color: #17a2b8;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        background-color: rgba(23, 162, 184, 0.1);
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
        transition: background-color 0.3s ease;
    }
    .article-category:hover {
        background-color: rgba(23, 162, 184, 0.2);
    }
    .article-title {
        font-size: 24px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }
    .article-title a {
        color: #2c3e50;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .article-title a:hover {
        color: #e67e22;
        text-decoration: underline;
    }
    .pagination-wrapper {
        text-align: left;
        margin-top: 40px;
        padding: 15px 0;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .pagination {
        display: inline-flex;
        list-style: none;
        padding: 0;
        gap: 10px;
    }
    .pagination li {
        display: inline-block;
    }
    .pagination li a, .pagination li span {
        display: block;
        padding: 10px 15px;
        background-color: #fff;
        color: #2c3e50;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .pagination li.active span {
        background-color: #e67e22;
        color: #fff;
        box-shadow: 0 4px 10px rgba(230, 126, 34, 0.3);
    }
    .pagination li a:hover {
        background-color: #17a2b8;
        color: #fff;
        box-shadow: 0 4px 10px rgba(23, 162, 184, 0.3);
    }
    .pagination li.disabled span {
        background-color: #ecf0f1;
        color: #bdc3c7;
        cursor: not-allowed;
    }
    @media (max-width: 768px) {
        .article-row {
            flex-direction: column;
            text-align: center;
        }
        .article-image {
            margin-right: 0;
            margin-bottom: 20px;
            width: 100%;
            height: 200px;
        }
        .articles-header, .pagination-wrapper {
            text-align: center;
        }
        .breadcrumb {
            font-size: 12px;
            text-align: center;
        }
        .search-container {
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>

<div class="container">
    <div class="articles-header">
        <h2>Articles<?php if (isset($category->name)): ?> for <?= h($category->name) ?><?php endif; ?></h2>
        <p>Explore our collection of insightful articles.</p>
    </div>

    <div class="search-container">
        <form method="get" action="<?= $this->Url->build(['controller' => 'Articles', 'action' => 'index', $categoryId]) ?>">
            <input type="text" name="q" placeholder="Search articles..." value="<?= isset($searchQuery) ? h($searchQuery) : '' ?>" oninput="this.form.submit()">
        </form>
    </div>

    <?php if (empty($totalArticles)): ?>
        <p>No articles found<?php echo isset($searchQuery) ? ' for "' . h($searchQuery) . '"' : (isset($category->name) ? ' for this category' : ''); ?>.</p>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <div class="article-row">
                <div class="article-image">
                    <a href="<?= $this->Url->build(['controller' => 'Articles', 'action' => 'view', $article->id]) ?>">
                        <img src="<?php echo $this->Url->build('/img/uploads/elearning_articles/' . $article->image); ?>" alt="<?php echo h($article->title); ?>" class="img-responsive">
                    </a>
                </div>
                <div class="article-content">
                    <div class="article-category"><?= h($article->elearning_category->name ? $article->elearning_category->name : 'Uncategorized') ?></div>
                    <div class="article-title">
                        <a href="<?= $this->Url->build(['controller' => 'Articles', 'action' => 'view', $article->id]) ?>">
                            <?= h($article->title) ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="pagination-wrapper">
        <ul class="pagination">
            <?= $this->Paginator->prev('« Previous') ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('Next »') ?>
        </ul>
    </div>
</div>