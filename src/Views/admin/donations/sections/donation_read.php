<?php if (empty($donations)): ?>

    <p>Aucun don reçu.</p>

<?php else: ?>

    <table class="admin-donations-list-table">
        <thead>
            <tr>
                <th>Statut</th>
                <th>Date</th>
                <th>Donateur</th>
                <th>Email</th>
                <th>Montant</th>
                <th>Message</th>
                <th>Détail</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($donations as $donation): ?>

                <tr>
                    <td>
                        <form
                            method="post"
                            action="?url=admin/dons/update-status"
                            class="donation-status-form"
                        >
                            <input
                                type="hidden"
                                name="id"
                                value="<?= (int) $donation['id'] ?>"
                            >

                            <select
                                name="status"
                                class="donation-status-select"
                            >
                                <option
                                    value="pending"
                                    <?= $donation['status'] === 'pending' ? 'selected' : '' ?>
                                >
                                    En attente
                                </option>

                                <option
                                    value="processed"
                                    <?= $donation['status'] === 'processed' ? 'selected' : '' ?>
                                >
                                    Traité
                                </option>
                            </select>
                        </form>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            date('d/m/Y H:i', strtotime($donation['created_at']))
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $donation['last_name'] . ' ' . $donation['first_name']
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($donation['email']) ?>
                    </td>

                    <td>
                        <?= number_format(
                            (float) $donation['amount'],
                            2,
                            ',',
                            ' '
                        ) ?> €
                    </td>

                    <td>
                        <?php $comment = $donation['comment'] ?? ''; ?>

                        <?= $comment !== ''
                            ? htmlspecialchars(
                                mb_strimwidth($comment, 0, 30, '…')
                            )
                            : '-'
                        ?>
                    </td>

                    <td>
                        <a href="<?= BASE_URL ?>/?url=admin/dons/show&id=<?= (int) $donation['id'] ?>">
                            Voir
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>

<?php endif; ?>