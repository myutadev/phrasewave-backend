<?php

namespace App\Services;

use App\Repositories\LanguageRepository;
use App\Repositories\MyphraseRepository;

class MyphraseService
{

    protected $myphraseRepository;
    protected $languageRepository;

    public function __construct(MyphraseRepository $myphraseRepository, LanguageRepository $languageRepository)
    {
        $this->myphraseRepository = $myphraseRepository;
        $this->languageRepository = $languageRepository;
    }

    /**
     * process request data: language to language_code save myPhrase data
     * @param array $newPhraseData ex.[ "words"=>["obsolate","study"],"language"=>Engllish(US)","user_id"=>1,"phraes"=>"The  system  may experience.."]
     * @return array $saveIds ex ["wordIds"=>[1,2], "phraseId"=>1]
     */
    public function createMyphrase(array $newPhraseData): array
    {

        return $this->myphraseRepository->insertMyPhrase($this->processRequestData($newPhraseData));
    }

    /**
     * delete myPhrase data 
     * @param array $newPhraseData object with wordIds and phraseId. ex ['wordIds' => [3,4], 'phraseId'=>3] 
     * @return void
     */
    public function deleteMyphrase(array $deleteData): void
    {
        $this->myphraseRepository->deleteMyphrase($deleteData);
    }

    private function processRequestData(array $newPhraseData): array
    {
        $selectedLanguageCode = $this->languageRepository->getLangCodeByName($newPhraseData['language']);
        return  [
            "words" => $newPhraseData["words"],
            "language_code" => $selectedLanguageCode,
            "user_id" => $newPhraseData["user_id"],
            "phrase" => $newPhraseData["phrase"]
        ];
    }

    /**
     * get wordsWith Phrases data for Myphrases page
     * @param int $userId userid from Auth
     * @return array ex ["study": ['phrases'=>["study is ...", "the obsolate study.."], 'language' => 'English (US)'], "obsolate":['phrases'=>["the obsolate study..."],'languages'=>'English (US)']] 
     */
    public function getAllSavedPhrasesByUser(int $userId): array
    {
        return  $this->myphraseRepository->getUserWordsWithPhrases($userId);
    }
}
