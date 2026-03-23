<?php

    function checkLogin($user, $password)
    {
        // Geldige gebruikers (username => password)
        $gebruikers = [
            "jeroen" => "123456",
            "remco" => "654321",
            "angela" => "112233"
        ];

        // Check login
        if (isset($gebruikers[$user]) && $gebruikers[$user] === $password)
        {
            $foutmelding = "Aanmelden gelukt! Welkom $user";
        }
        else
        {
            $foutmelding = "Onjuiste gebruikersnaam of wachtwoord";
        }

        return $foutmelding;
    }

?>
