<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

class GeneratePhraseService
{

    /**
     * request openAI api to generate phrases
     *
     * @param mixed $request request data from Form  
     * @return json 
     */
    public function generatePhrase($request)
    {
        $formData = $request->json()->all();

        $studyLang = $formData["language"];
        $studyWords = $this->getWordPhraseObj($formData);

        
    }

    /**
     * process request data to studyWords Object
     *
     * @param object $formData  request data converted to json 
     * @return object return array has object ex.[{word:overload,context:the system was overloaded.}]
     */
    public function getWordPhraseObj($formData)
    {
        $wordKeys = ['word1', 'word2', 'word3', 'word4', 'word5'];
        $contextKeys = [
            'context1',
            'context2',
            'context3',
            'context4',
            'context5',
        ];

        $studyWords = array_map(function ($item, $index) use ($formData, $contextKeys) {
            return [
                'word' => $formData[$item] ?? "",
                'context' => $formData[$contextKeys[$index]] ?? ""
            ];
        }, $wordKeys, array_keys($wordKeys));

        $filteredStudyWords = array_filter($studyWords, function ($item) {
            return $item['word'] !== "";
        });

        return $filteredStudyWords;
    }

    // formData is {"language":"English (British)",
    // "word1":"overload","word2":"obsolate","word3":null,"word4":null,"word5":null,"context1":"The power shut off after all the circuits were overloaded","context2":null,"context3":null,"context4":null,"context5":null} 
}
