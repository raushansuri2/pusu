<?php
$EIMGS = $this->Url->build('/') . 'img/uploads/elearning_categories/';
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
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    .category-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
        margin: 30px 0;
    }
    .category-btn {
        background-color: #17a2b8;
        color: white;
        border: none;
        border-radius: 30px;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .category-btn:hover {
        background-color: #138496;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }
    .category-btn.active {
        background-color: #0c6c7a;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    .card-container {
        display: flex;
        flex-wrap: nowrap; /* Keep in one row */
        justify-content: space-between; /* Distribute space evenly */
        gap: 25px;
        margin: 40px 0;
        padding: 0 15px;
        width: 100%;
    }
    .custom-card {
        flex: 1; /* Allow cards to grow/shrink equally */
        max-width: calc((1200px - 50px) / 3); /* Max width: (container - gaps) / 3 */
        min-width: 300px; /* Minimum width to maintain readability */
        background-color: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        padding: 0;
    }
    .custom-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }
    .card-img-top {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        margin: 20px auto 0;
        display: block;
        border: 4px solid #e67e22;
        transition: transform 0.3s ease;
    }
    .custom-card:hover .card-img-top {
        transform: scale(1.05);
    }
    .card-content {
        padding: 25px;
        text-align: center;
    }
    .card-title {
        font-size: 22px;
        font-weight: 600;
        color: #2c3e50;
        margin: 15px 0;
    }
    .card-text {
        font-size: 15px;
        color: #7f8c8d;
        line-height: 1.7;
        margin-bottom: 20px;
        word-wrap: break-word;
    }
    .card-link {
        display: inline-block;
        padding: 10px 20px;
        background-color: #e67e22;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }
    .card-link:hover {
        background-color: #d35400;
    }
    @media (max-width: 992px) {
        .custom-card {
            max-width: calc((992px - 50px) / 3); /* Adjust for smaller screens */
        }
    }
    @media (max-width: 768px) {
        .card-container {
            flex-wrap: wrap; /* Allow wrapping on very small screens */
            justify-content: center;
        }
        .custom-card {
            flex: 0 0 90%; /* Full width on mobile */
            max-width: 90%;
            margin: 15px auto;
        }
        .category-btn {
            padding: 10px 20px;
        }
        .card-img-top {
            width: 160px;
            height: 160px;
        }
    }
</style>

<div class="container">
    <div class="col-md-12 col-sm-12">
        <div class="detail-wrapper text-center">
            <div class="category-list" id="category-list">
                <?php foreach ($categories as $category): ?>
                    <button class="category-btn" data-id="<?= $category->id ?>" onclick="showImages(<?= $category->id ?>, this)">
                        <?= h($category->name) ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <div class="card-container" id="card">
                <!-- Cards will be dynamically inserted here -->
            </div>
        </div>
    </div>
</div>

<script>
    function showImages(categoryId, button) {
        const cardContainer = document.getElementById('card');
        cardContainer.innerHTML = ''; // Clear previous cards

        const categories = <?= json_encode($categories) ?>;
        const baseUrl = '<?= $EIMGS ?>';
        const articlesUrl = '<?= $this->Url->build(['controller' => 'Articles', 'action' => 'index']) ?>';
        const questionsUrl = '<?= $this->Url->build(['controller' => 'Questions', 'action' => 'index']) ?>';
        const videosUrl = '<?= $this->Url->build(['controller' => 'Videos', 'action' => 'index']) ?>';
        const defaultImage = '<?= $this->Url->build('/img/uploads/elearning_categories/default_image.jpg') ?>';

        const category = categories.find(cat => cat.id === categoryId);
        if (category) {
            // Course (Questions) Card
            const courseImage = category.questions_image ? `${baseUrl}${category.questions_image}` : defaultImage;
            const courseCard = document.createElement('div');
            courseCard.className = 'custom-card';
            courseCard.innerHTML = `
                <a href="${questionsUrl}/${category.id}">
                    <img src="${courseImage}" class="card-img-top" alt="${category.name} Questions" onerror="this.src='${defaultImage}'" />
                </a>
                <div class="card-content">
                    <h5 class="card-title">${category.title} Questions</h5>
                    <p class="card-text">${category.description}</p>
                    <a href="${questionsUrl}/${category.id}" class="card-link">Start Learning</a>
                </div>
            `;
            cardContainer.appendChild(courseCard);

            // Video Card
            const videoImage = category.videos_image ? `${baseUrl}${category.videos_image}` : defaultImage;
            const videoCard = document.createElement('div');
            videoCard.className = 'custom-card';
            videoCard.innerHTML = `
                <a href="${videosUrl}/${category.id}">
                    <img src="${videoImage}" class="card-img-top" alt="${category.name} Videos" onerror="this.src='${defaultImage}'" />
                </a>
                <div class="card-content">
                    <h5 class="card-title">${category.title} Videos</h5>
                    <p class="card-text">${category.description}</p>
                    <a href="${videosUrl}/${category.id}" class="card-link">Watch Now</a>
                </div>
            `;
            cardContainer.appendChild(videoCard);

            // Articles Card
            const articlesImage = category.articles_image ? `${baseUrl}${category.articles_image}` : defaultImage;
            const articlesCard = document.createElement('div');
            articlesCard.className = 'custom-card';
            articlesCard.innerHTML = `
                <a href="${articlesUrl}/${category.id}">
                    <img src="${articlesImage}" class="card-img-top" alt="${category.name} Articles" onerror="this.src='${defaultImage}'" />
                </a>
                <div class="card-content">
                    <h5 class="card-title">${category.title} Articles</h5>
                    <p class="card-text">${category.description}</p>
                    <a href="${articlesUrl}/${category.id}" class="card-link">Read Articles</a>
                </div>
            `;
            cardContainer.appendChild(articlesCard);

            cardContainer.style.display = 'flex';

            document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        } else {
            console.error('No content found for category ID: ' + categoryId);
        }
    }
</script>