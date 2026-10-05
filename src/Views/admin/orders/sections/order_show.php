<section class="admin-order-show">

    <h3 class="admin-h3">
        Commande n° <?= htmlspecialchars($order['order_number']) ?>
    </h3>

    <table class="admin-order-details">
        <tbody>
            <tr>
                <th scope="row">Date de commande</th>
                <td>
                    <?= htmlspecialchars($order['created_at']) ?>
                </td>
            </tr>

            <tr>
                <th scope="row">Statut</th>
                <td>
                    <?= match ($order['status']) {
                        'shipped' => 'Expédié',
                        'cancelled' => 'Annulé',
                        default => 'En attente'
                    } ?>
                </td>
            </tr>

            <tr>
                <th scope="row">Nom</th>
                <td>
                    <?= htmlspecialchars($order['last_name']) ?>
                </td>
            </tr>

            <tr>
                <th scope="row">Prénom</th>
                <td>
                    <?= htmlspecialchars($order['first_name']) ?>
                </td>
            </tr>

            <tr>
                <th scope="row">Email</th>
                <td>
                    <?= htmlspecialchars($order['email']) ?>
                </td>
            </tr>

            <?php if (!empty($order['phone'])): ?>
                <tr>
                    <th scope="row">Téléphone</th>
                    <td>
                        <?= htmlspecialchars($order['phone']) ?>
                    </td>
                </tr>
            <?php endif; ?>

            <tr>
                <th scope="row">Adresse</th>
                <td>
                    <?= htmlspecialchars($order['address']) ?><br>
                    <?= htmlspecialchars($order['postcode']) ?>
                    <?= htmlspecialchars($order['city']) ?>
                </td>
            </tr>

        </tbody>
    </table>


    <h3 class="admin-h3">Articles commandés</h3>

    <?php if (empty($items)): ?>

        <p>Aucun article associé à cette commande.</p>

    <?php else: ?>

        <table class="admin-order-items">
            <thead>
                <tr>
                    <th>Réf. produit</th>
                    <th>Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                </tr>
            </thead>

            <tbody>

                <?php
                $total = 0;
                ?>

                <?php foreach ($items as $item): ?>

                    <?php
                    $total += (float) $item['subtotal'];
                    ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars($item['product_reference']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($item['product_name']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                number_format(
                                    (float) $item['unit_price'],
                                    2,
                                    ',',
                                    ' '
                                )
                            ) ?> €
                        </td>

                        <td>
                            <?= (int) $item['quantity'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                number_format(
                                    (float) $item['subtotal'],
                                    2,
                                    ',',
                                    ' '
                                )
                            ) ?> €
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

            <tfoot>
                <tr>
                    <th colspan="4">Total</th>
                    <th>
                        <?= htmlspecialchars(
                            number_format(
                                $total,
                                2,
                                ',',
                                ' '
                            )
                        ) ?> €
                    </th>
                </tr>
            </tfoot>
        </table>

    <?php endif; ?>

</section>