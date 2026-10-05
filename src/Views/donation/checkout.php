<main class="content">

<?php
$errors = $errors ?? [];
$old = $old ?? [];
$amount = $amount ?? 0;
$success = $success ?? null;

$sections = [
    'checkout_form_page',
];

foreach ($sections as $section) {
    $this->renderSection(__DIR__, $section, get_defined_vars());
}

?>

</main>