<main class="content">

<?php
$sections = [
    'hero',
    'map',
    'hours_and_pricing',
    'contact_details',
    'contact_form',
    'social_networks',
];

foreach ($sections as $section) {
    $this->renderSection(__DIR__, $section, get_defined_vars());
}

?>

</main>