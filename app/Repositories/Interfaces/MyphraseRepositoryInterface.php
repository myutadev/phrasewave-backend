<?php

namespace App\Repositories\Interfaces;

interface MyphraseRepositoryInterface
{
    public function insertMyphrase(array $newPhraseData): array;
    public function deleteMyphrase(array $newPhraseData): void;
}
