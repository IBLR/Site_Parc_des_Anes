<section class="layout-section account-section">

    <h2>Mes dons</h2>

    <?php if (empty($donations)) : ?>

        <p>Vous n'avez pas encore effectué de don.</p>

    <?php else : ?>

        <div class="account-table-wrapper">

            <table class="account-table account-donations">

                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Montant</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($donations as $donation) : ?>

                        <tr>

                            <td
                                data-label="Date"
                            >
                                <?= htmlspecialchars(
                                    date(
                                        'd/m/Y',
                                        strtotime($donation['created_at'])
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td
                                data-label="Montant"
                            >
                                <?= number_format(
                                    (float) $donation['amount'],
                                    2,
                                    ',',
                                    ' '
                                ) ?> €
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</section>