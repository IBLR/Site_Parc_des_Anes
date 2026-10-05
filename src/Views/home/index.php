<main class="content">

<?php
$sections = [
    'hero',
    'story',
    'missions',
    'founder_word',
    'explore_cta',
    'visitor_reviews',
];

foreach ($sections as $section) {
    $this->renderSection(__DIR__, $section, get_defined_vars());
}

?>

</main>