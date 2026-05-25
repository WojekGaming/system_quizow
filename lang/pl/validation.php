<?php
 
return [
    'accepted'             => 'Pole :attribute musi zostać zaakceptowane.',
    'email'                => 'Pole :attribute musi być prawidłowym adresem email.',
    'confirmed'            => 'Potwierdzenie pola :attribute nie zgadza się.',
    'max'                  => [
        'string' => 'Pole :attribute nie może być dłuższe niż :max znaków.',
    ],
    'min'                  => [
        'string' => 'Pole :attribute musi mieć co najmniej :min znaków.',
    ],
    'required'             => 'Pole :attribute jest wymagane.',
    'string'               => 'Pole :attribute musi być ciągiem znaków.',
    'unique'               => 'Taka wartość pola :attribute już istnieje.',
    'password'             => [
        'letters'       => 'Hasło musi zawierać co najmniej jedną literę.',
        'mixed'         => 'Hasło musi zawierać co najmniej jedną wielką i jedną małą literę.',
        'numbers'       => 'Hasło musi zawierać co najmniej jedną cyfrę.',
        'symbols'       => 'Hasło musi zawierać co najmniej jeden znak specjalny.',
        'uncompromised' => 'Podane hasło wyciekło w sieci. Proszę wybrać inne hasło.',
    ],
 
    'attributes' => [
        'name'                  => 'nazwa użytkownika',
        'email'                 => 'adres email',
        'password'              => 'hasło',
        'password_confirmation' => 'potwierdzenie hasła',
        'login'                 => 'login',
    ],
];