<?php

class Quete
{
    public string $nom;
    public bool $terminee;

    public function __construct(string $nom)
    {
        $this->nom = $nom;
        $this->terminee = false;
    }

    public function terminer(): void
    {
        $this->terminee = true;
    }

    public function reprendre(): void
    {
        $this->terminee = false;
    }
}
