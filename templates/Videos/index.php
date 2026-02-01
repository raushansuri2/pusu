<?php
// Load Bootstrap 5 using CakePHP 3.1 HtmlHelper
$this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', ['block' => true]);
$this->Html->script('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', ['block' => true]);
?>

<style>
    /* Breadcrumbs Styling */
    .breadcrumb {
        margin-bottom: 25px;
        background: none;
        padding: 10px 0;
        font-size: 16px;
    }
    .breadcrumb li {
        display: inline;
    }
    .breadcrumb li + li:before {
        content: "›";
        padding: 0 10px;
        color: #bbb;
        font-weight: bold;
    }
    .breadcrumb a {
        color: #1e90ff;
        text-decoration: none;
        transition: color 0.3s;
    }
    .breadcrumb a:hover {
        color: #ff4500;
        text-decoration: underline;
    }
    .breadcrumb .active {
        color: #444;
        font-weight: 600;
    }

    /* Header Styling */
    .article-header {
        margin-bottom: 35px;
        padding: 20px;
        background: linear-gradient(to right, #f8f9fa, #e9ecef);
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .article-header h1.page-header {
        font-size: 36px;
        font-weight: 700;
        color: #222;
        margin: 0 0 10px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
    }
    .article-meta {
        font-size: 15px;
        color: #555;
    }
    .article-meta .category {
        color: #1e90ff;
        font-weight: 600;
    }
    .article-meta .byline a {
        color: #ff4500;
        text-decoration: none;
        transition: color 0.3s;
    }
    .article-meta .byline a:hover {
        color: #1e90ff;
        text-decoration: underline;
    }

    /* Search Field Styling */
    .search-container {
        margin-bottom: 40px;
        max-width: 600px;
        position: relative;
    }
    .search-input {
        width: 100%;
        padding: 14px 20px;
        font-size: 16px;
        border: 2px solid #ddd;
        border-radius: 30px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        outline: none;
        transition: all 0.3s ease;
        background: #fff;
    }
    .search-input:focus {
        border-color: #1e90ff;
        box-shadow: 0 5px 20px rgba(30, 144, 255, 0.3);
    }
    .search-input::placeholder {
        color: #999;
        font-style: italic;
    }

    /* Video Grid Styling */
    .video-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }
    .video-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        cursor: pointer;
    }
    .video-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }
    .video-player {
        width: 100%;
        height: 260px;
        object-fit: cover;
        background: #000;
        display: block;
    }
    .video-title {
        padding: 15px 20px;
        margin: 0;
        font-size: 20px;
        color: #333;
        font-weight: 600;
        background: linear-gradient(to right, #f8f9fa, #fff);
        transition: color 0.3s ease;
        border-top: 1px solid #eee;
    }
    .video-card:hover .video-title {
        color: #ff4500;
    }

    /* Pagination Styling */
    .pagination {
        justify-content: center;
        margin-top: 40px;
    }
    .pagination .page-item .page-link {
        border: none;
        color: #1e90ff;
        background: #fff;
        border-radius: 50%;
        margin: 0 8px;
        padding: 12px 18px;
        font-size: 16px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .pagination .page-item.active .page-link {
        background: #1e90ff;
        color: #fff;
        box-shadow: 0 5px 15px rgba(30, 144, 255, 0.4);
    }
    .pagination .page-item .page-link:hover {
        background: #ff4500;
        color: #fff;
        box-shadow: 0 5px 15px rgba(255, 69, 0, 0.4);
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        border: none;
    }
    .modal-header {
        background: linear-gradient(45deg, #1e90ff, #ff4500);
        color: #fff;
        border-bottom: none;
        padding: 15px 20px;
    }
    .modal-title {
        font-size: 24px;
        font-weight: 600;
    }
    .modal-body {
        padding: 0;
        background: #000;
    }
    .modal-body iframe,
    .modal-body video {
        width: 100%;
        height: 600px;
        display: block;
    }
    .btn-close {
        filter: invert(1) brightness(2);
        opacity: 0.8;
        transition: opacity 0.3s;
    }
    .btn-close:hover {
        opacity: 1;
    }
</style>

<!-- Header -->
<div class="article-header">
    <h1 class="page-header"><?= 'E-Learning Videos' ?></h1>
    <div class="article-meta">
        <span class="category">
            <?= isset($category->name) ? h($category->name) : 'Uncategorized' ?>
        </span>
        | <span class="byline">Posts by: <a href="#">Admin</a></span>
    </div>
</div>

<!-- Search Field -->
<div class="search-container">
    <?= $this->Form->create(null, ['url' => ['controller' => 'Videos', 'action' => 'index'], 'type' => 'get']) ?>
    <?= $this->Form->control('search', [
        'label' => false,
        'placeholder' => 'Search videos...',
        'class' => 'search-input',
        'value' => $this->request->getQuery('search'),
        'oninput' => 'this.form.submit()'
    ]) ?>
    <?= $this->Form->end() ?>
</div>

<div class="video-grid">
    <?php if (empty($videos)): ?>
        <p style="font-size: 18px; color: #666; text-align: center; padding: 20px; background: #f8f9fa; border-radius: 10px;">No videos available.</p>
    <?php else: ?>
        <?php foreach ($videos as $video): ?>
            <div class="video-card" 
                 data-bs-toggle="modal" 
                 data-bs-target="#videoModal_<?= $video->id ?>">
                <?php if ($video->url && (strpos($video->url, 'youtube.com') === false && strpos($video->url, 'youtu.be') === false)): ?>
                    <!-- Local MP4 or similar video -->
                    <video class="video-player" controls preload="metadata">
                        <source src="<?= h($video->url) ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php else: ?>
                    <!-- YouTube video with iframe -->
                    <?php
                        $videoIdMatch = preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video->url, $matches);
                        $youtubeId = $videoIdMatch ? $matches[1] : null;
                    ?>
                    <?php if ($youtubeId): ?>
                        <iframe class="video-player" 
                                src="https://www.youtube.com/embed/<?= $youtubeId ?>?controls=1&rel=0" 
                                frameborder="0" 
                                allowfullscreen></iframe>
                    <?php else: ?>
                        <video class="video-player" controls>
                            <source src="" type="video/mp4">
                            Invalid video URL.
                        </video>
                    <?php endif; ?>
                <?php endif; ?>
                <h3 class="video-title"><?= h($video->title) ?></h3>
            </div>

            <!-- Individual Modal for Each Video -->
            <div class="modal fade" id="videoModal_<?= $video->id ?>" tabindex="-1" aria-labelledby="videoModalLabel_<?= $video->id ?>" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="videoModalLabel_<?= $video->id ?>"><?= h($video->title) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php if ($video->url && (strpos($video->url, 'youtube.com') === false && strpos($video->url, 'youtu.be') === false)): ?>
                                <video width="100%" height="600" controls autoplay>
                                    <source src="<?= h($video->url) ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            <?php else: ?>
                                <?php if ($youtubeId): ?>
                                    <iframe width="100%" height="600" 
                                            src="https://www.youtube.com/embed/<?= $youtubeId ?>?autoplay=1&controls=1&rel=0" 
                                            frameborder="0" 
                                            allowfullscreen></iframe>
                                <?php else: ?>
                                    <p style="color: #fff; text-align: center; padding: 20px;">Invalid YouTube URL</p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination">
        <?= $this->Paginator->prev('« Previous', ['class' => 'page-item']) ?>
        <?= $this->Paginator->numbers(['class' => 'page-item']) ?>
        <?= $this->Paginator->next('Next »', ['class' => 'page-item']) ?>
    </ul>
</nav>