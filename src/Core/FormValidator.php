<?php

namespace App\Core;

/**
 * Validates form fields according to the data provided by each form.
 */
class FormValidator
{
    /**
     * Validates the fields provided by a form.
     * Each form only submits the fields it contains.
     */
    public function validate(array $data): array
    {
        $errors = [];

        // Validate last name.
        if (array_key_exists('last_name', $data)) {
            $lastName = $data['last_name'];

            if ($lastName === '') {
                $errors['last_name'] = 'Le nom est requis';
            } elseif (mb_strlen($lastName) < 2) {
                $errors['last_name'] = 'Le nom est trop court';
            } elseif (!preg_match("/^[\p{L}\s'-]+$/u", $lastName)) {
                $errors['last_name'] = 'Le nom contient des caractères invalides';
            }
        }

        // Validate first name.
        if (array_key_exists('first_name', $data)) {
            $firstName = $data['first_name'];

            if ($firstName === '') {
                $errors['first_name'] = 'Le prénom est requis';
            } elseif (mb_strlen($firstName) < 2) {
                $errors['first_name'] = 'Le prénom est trop court';
            } elseif (!preg_match("/^[\p{L}\s'-]+$/u", $firstName)) {
                $errors['first_name'] = 'Le prénom contient des caractères invalides';
            }
        }

        // Validate email address.
        if (array_key_exists('email', $data)) {
            $email = $data['email'];

            if ($email === '') {
                $errors['email'] = 'L\'email est requis';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'L\'email est invalide';
            }
        }

        // Validate the optional phone number.
        if (array_key_exists('phone', $data)) {
            $phone = $data['phone'];

            if ($phone !== '') {
                // Normalize the number before validation.
                $normalizedPhone = $this->normalizeFrenchPhone($phone);

                if (!preg_match('/^0[1-9]\d{8}$/', $normalizedPhone)) {
                    $errors['phone'] = 'Le numéro de téléphone est invalide (FR)';
                }
            }
        }

        // Validate message.
        if (array_key_exists('message', $data)) {
            $message = $data['message'];

            if ($message === '') {
                $errors['message'] = 'Le message est requis';
            } elseif (mb_strlen($message) < 10) {
                $errors['message'] = 'Le message doit contenir au moins 10 caractères';
            }
        }

        // Validate address.
        if (array_key_exists('address', $data)) {
            $address = $data['address'];

            if ($address === '') {
                $errors['address'] = "L'adresse est requise";
            }
        }

        // Validate postal code.
        if (array_key_exists('postcode', $data)) {
            $postcode = $data['postcode'];

            if ($postcode === '') {
                $errors['postcode'] = 'Le code postal est requis';
            } elseif (!preg_match('/^(?!00000$)\d{5}$/', $postcode)) {
                $errors['postcode'] = 'Le code postal doit contenir 5 chiffres valides';
            }
        }

        // Validate city.
        if (array_key_exists('city', $data)) {
            $city = $data['city'];

            if ($city === '') {
                $errors['city'] = 'La ville est requise';
            } elseif (!preg_match("/^[\p{L}\s'-]+$/u", $city)) {
                $errors['city'] = 'La ville contient des caractères invalides';
            }
        }

        return $errors;
    }

    /**
     * Normalizes a French phone number.
     *
     * Accepted formats include spaces, dots, dashes, slashes,
     * and the +33 international prefix.
     */
    public function normalizeFrenchPhone(string $phone): string
    {
        $phone = trim($phone);

        // Remove spaces, dots, dashes, and slashes.
        $phone = preg_replace('/[\s.\/-]+/', '', $phone);

        // Convert the +33 international prefix to the French 0 prefix.
        if (str_starts_with($phone, '+33')) {
            $phone = '0' . substr($phone, 3);
        }

        return $phone;
    }
}