<section class="admin-donation-show">

    <h3 class="admin-h3">
        Don n° <?= htmlspecialchars($donation['donation_number']) ?>
    </h3>

    <table class="admin-donation-details">
        <tbody>
            <tr>
                <th scope="row">Date du don</th>
                <td>
                    <?= htmlspecialchars($donation['created_at']) ?>
                </td>
            </tr>

            <tr>
                <th scope="row">Statut</th>
                <td>
                    <?= $donation['status'] === 'processed'
                        ? 'Traité'
                        : 'À traiter' ?>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Nom</th>
                <td>
                    <?= htmlspecialchars($donation['last_name']) ?>
                </td>
            </tr>

            <tr>
                <th scope="row">Prénom</th>
                <td>
                    <?= htmlspecialchars($donation['first_name']) ?>
                </td>
            </tr>

            <tr>
                <th scope="row">Email</th>
                <td>
                    <?= htmlspecialchars($donation['email']) ?>
                </td>
            </tr>

            <?php if (!empty($donation['phone'])): ?>
                <tr>
                    <th scope="row">Téléphone</th>
                    <td>
                        <?= htmlspecialchars($donation['phone']) ?>
                    </td>
                </tr>
            <?php endif; ?>

            <tr>
                <th scope="row">Montant</th>
                <td>
                    <?= number_format(
                        (float) $donation['amount'],
                        2,
                        ',',
                        ' '
                    ) ?> €
                </td>
            </tr>

            <tr>
                <th scope="row">Message</th>
                <td class="admin-donation-message">
                    <?php if (!empty($donation['comment'])): ?>

                        <?= nl2br(htmlspecialchars($donation['comment'])) ?>

                    <?php else: ?>

                        Aucun message

                    <?php endif; ?>
                </td>
            </tr>

        </tbody>
    </table>

</section>