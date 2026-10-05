<section class="layout-section account-section">

    <h2>Mes commandes</h2>

    <?php if (empty($orders)) : ?>

        <p>Vous n'avez pas encore passé de commande.</p>

    <?php else : ?>

        <div class="account-table-wrapper">

            <table class="account-table account-orders">

                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">N° de commande</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($orders as $order) : ?>

                        <tr>

                            <td
                                data-label="Date"
                            >
                                <?= htmlspecialchars(
                                    date(
                                        'd/m/Y',
                                        strtotime($order['created_at'])
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td
                                data-label="N° de commande"
                            >
                                <?= htmlspecialchars(
                                    $order['order_number'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td
                                data-label="Total"
                            >
                                <?= number_format(
                                    (float) $order['total'],
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