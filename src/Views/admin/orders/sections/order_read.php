<?php if (empty($orders)): ?>

    <p>Aucune commande enregistrée.</p>

<?php else: ?>

    <table class="admin-orders-list-table">
        <thead>
            <tr>
                <th>Statut</th>
                <th>Date</th>
                <th>Client</th>
                <th>Email</th>
                <th>N° commande</th>
                <th>Total</th>
                <th>Détail</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($orders as $order): ?>

                <tr>
                    <td>
                        <form
                            method="post"
                            action="?url=admin/commandes/update-status"
                            class="order-status-form"
                        >
                            <input
                                type="hidden"
                                name="id"
                                value="<?= (int) $order['id'] ?>"
                            >

                            <select
                                name="status"
                                class="order-status-select"
                            >
                                <option
                                    value="pending"
                                    <?= $order['status'] === 'pending' ? 'selected' : '' ?>
                                >
                                    En attente
                                </option>

                                <option
                                    value="shipped"
                                    <?= $order['status'] === 'shipped' ? 'selected' : '' ?>
                                >
                                    Expédié
                                </option>

                                <option
                                    value="cancelled"
                                    <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>
                                >
                                    Annulé
                                </option>
                            </select>
                        </form>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            date('d/m/Y H:i', strtotime($order['created_at']))
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $order['last_name'] . ' ' . $order['first_name']
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($order['email']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($order['order_number']) ?>
                    </td>
                    
                    <td>
                        <?= htmlspecialchars(
                            number_format(
                                (float) $order['total'],
                                2,
                                ',',
                                ' '
                            )
                        ) ?> €
                    </td>

                    <td>
                        <a
                            href="<?= BASE_URL ?>/?url=admin/commandes/show&id=<?= (int) $order['id'] ?>"
                        >
                            Voir
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>

<?php endif; ?>