<?php if (empty($messages)): ?>

    <p>Aucun message reçu.</p>

<?php else: ?>

    <table class="admin-contacts-list-table">
        <thead>
            <tr>
                <th>Statut</th>
                <th>Date</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Message</th>
                <th>Détail</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($messages as $message): ?>

                <tr>
                    <td>
                        <form
                            method="post"
                            action="?url=admin/contacts/update-status"
                            class="contact-status-form"
                        >
                            <input
                                type="hidden"
                                name="id"
                                value="<?= (int) $message['id'] ?>"
                            >

                            <select
                                name="status"
                                class="contact-status-select"
                            >
                                <option
                                    value="pending"
                                    <?= $message['status'] === 'pending' ? 'selected' : '' ?>
                                >
                                    En attente
                                </option>

                                <option
                                    value="processed"
                                    <?= $message['status'] === 'processed' ? 'selected' : '' ?>
                                >
                                    Traité
                                </option>
                            </select>
                        </form>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            date('d/m/Y H:i', strtotime($message['created_at']))
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $message['last_name'] . ' ' . $message['first_name']
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($message['email']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            mb_strimwidth($message['message'], 0, 30, '…')
                        ) ?>
                    </td>

                    <td>
                        <a href="<?= BASE_URL ?>/?url=admin/contacts/show&id=<?= (int) $message['id'] ?>">
                            Voir
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>

<?php endif; ?>