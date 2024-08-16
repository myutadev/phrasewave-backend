<?php

namespace App\Services;

use App\Repositories\LanguageRepository;
use Illuminate\Database\Eloquent\Collection;
use OpenAI\Laravel\Facades\OpenAI;

class GeneratePhraseService
{
    protected $languageRepository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * request openAI api to generate phrases
     *
     * @param mixed $request request data from Form  
     * @return Collection
     */
    public function generatePhrase($request): Collection
    {
        $formData = $request->json()->all();

        $studyLang = $formData["language"];
        $studyWords = $this->getWordPhraseObj($formData);

        return $this->generateStudyPhrases($studyLang, $studyWords);
    }

    /**
     * process request data to studyWords Object
     *
     * @param array $formData  request data converted to json 
     * @return array return array has object ex.[{word:overload,context:the system was overloaded.}]
     */
    public function getWordPhraseObj(array $formData): array
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

    /**
     * process request data to studyWords Object
     *
     * @param string $studyLang Language to study
     * @param array $studyWords word and context pair array 
     * @return Collection return array has object ex.[{word:overload,context:the system was overloaded.}]
     */
    public function generateStudyPhrases(string $studyLang, array $studyWords): Collection
    {

        try {
            $result = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "As an $studyLang teacher, create 3 example sentences using only the words from the input JSON. 
                        Ensure each word is used at least once, and try to include 3 or more words in each sentence if possible. If fewer than 3 words, 
                        reuse them to make 3 sentences. RETURN ONLY JSON: [{'usedWords': ['word1', 'word2', ...], 'generatedPhrase': 'Sentence using the words.'}]."
                    ],
                    [
                        'role' => 'user',
                        'content' => json_encode($studyWords)
                    ],
                ],
            ]);
            $jsonResult = $result['choices'][0]['message']['content'];
            return Collection::make(json_decode($jsonResult));
        } catch (\Exception $e) {
            throw new \Exception("Failed to generate study sentences: " . $e->getMessage());
        }
    }
}
