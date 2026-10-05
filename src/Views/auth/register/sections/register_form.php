<form
    method="POST"
    class="auth-form"
    novalidate
>

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars(
            $csrfToken,
            ENT_QUOTES,
            'UTF-8'
        ) ?>"
    >

    <div class="form-field-block input-style">

        <label
            for="register-email"
            class="high-text"
        >
            Email
            <span
                class="required-field"
                aria-hidden="true"
            >*</span>
        </label>

        <input
            type="email"
            id="register-email"
            name="email"
            autocomplete="username"
            required
        >

    </div>


    <div class="form-field-block input-style">

        <label
            for="register-password"
            class="high-text"
        >
            Mot de passe
            <span
                class="required-field"
                aria-hidden="true"
            >*</span>
        </label>

        <input
            type="password"
            id="register-password"
            name="password"
            autocomplete="new-password"
            required
        >

    </div>


    <button type="submit" class="btn btn--primary btn--square-lg">
        Créer mon compte
    </button>

</form>