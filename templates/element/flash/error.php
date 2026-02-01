<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = ($message);
}
?>

<!--If you want to allow HTML (like links), remove the h() function:-->
<div class="alert alert-danger" onclick="this.classList.add('hidden');"><?= ($message) ?></div>

