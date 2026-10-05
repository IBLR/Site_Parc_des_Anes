<main class="content">

<?php
$sections = [
    'hero',
    'products',

];

foreach ($sections as $section) {
    $this->renderSection(__DIR__, $section, get_defined_vars());
}

?>

</main>
