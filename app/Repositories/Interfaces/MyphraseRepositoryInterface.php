<?php

namespace App\Repositories\interfaces;

interface MyphraseRepositoryInterface
{
    public function insertMyphrase(array $newPhraseData): array;
    public function deleteMyphrase(array $newPhraseData): void;
}
