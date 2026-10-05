<main class="content">

<?php
$sections = [
    'hero',
    'our_mascot',
    'our_deans',
    'our_newborns',
];

foreach ($sections as $section) {
    $this->renderSection(__DIR__, $section, get_defined_vars());
}

?>

</main>