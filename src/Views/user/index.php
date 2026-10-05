<main class="content account-page">

    <?php
    $sections = [
        'account_info',
    ];

    if (empty($_SESSION['user']['isAdmin'])) {
        $sections[] = 'orders';
        $sections[] = 'donations';
    }

    $sections[] = 'password';

    foreach ($sections as $section) {
        $this->renderSection(__DIR__, $section, get_defined_vars());
    }
    ?>

</main>