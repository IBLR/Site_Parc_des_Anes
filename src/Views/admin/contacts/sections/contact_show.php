<section class="admin-contact-show">

    <h3 class="admin-h3">
        Message reçu
    </h3>

    <table class="admin-contact-details">
        <tbody>
            <tr>
                <th scope="row">Date de réception</th>
                <td><?= htmlspecialchars($message['created_at']) ?></td>
            </tr>

            <tr>
                <th scope="row">Statut</th>
                <td>
                    <?= $message['status'] === 'processed'
                        ? 'Traité'
                        : 'À traiter' ?>
                </td>
            </tr>

            <tr>
                <th scope="row">Nom</th>
                <td><?= htmlspecialchars($message['last_name']) ?></td>
            </tr>

            <tr>
                <th scope="row">Prénom</th>
                <td><?= htmlspecialchars($message['first_name']) ?></td>
            </tr>

            <tr>
                <th scope="row">Email</th>
                <td><?= htmlspecialchars($message['email']) ?></td>
            </tr>

            <?php if (!empty($message['phone'])): ?>
                <tr>
                    <th scope="row">Téléphone</th>
                    <td><?= htmlspecialchars($message['phone']) ?></td>
                </tr>
            <?php endif; ?>

            <tr>
                <th scope="row">Message</th>
                <td class="admin-contact-message">
                    <?= nl2br(htmlspecialchars($message['message'])) ?>
                </td>
            </tr>
        </tbody>
    </table>

</section>