<?php

class Form
{
    /**
     * Permet de sécuriser les données en appliquant les fonctions trim, stripslashes et htmlspecialchars
     * @param string Données à sécuriser et à rendre safe
     * @return string Donnée safe
     */
    public static function safeData($value): string
    {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        return $value;
    }


    /**
     * Permet de transformer une date au format US en date au format FR
     * @param string Date US
     * @return string Date FR
     */
    public static function formatDateUsToFr($dateUS): string
    {
        return implode('/', array_reverse(explode('-', $dateUS)));
    }
}
