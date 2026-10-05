<main class="content">

<?php
$sections = [
    'hero',
    'donation_reason',
    'donation_form',
    'founder_word',
];

foreach ($sections as $section) {
    $this->renderSection(__DIR__, $section, get_defined_vars());
}

?>

</main>