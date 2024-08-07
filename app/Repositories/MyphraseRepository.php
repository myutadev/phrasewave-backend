<?php

namespace App\Repositories;

use App\Models\Phrase;
use App\Models\Word;
use App\Repositories\interfaces\MyphraseRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MyphraseRepository implements MyphraseRepositoryInterface
{
    /**
     *insert Word,phrase data to DB
     *@param array $newPhraseData  ex. [ "words"=>["obsolate","study"],"language_code"=>en-US","user_id"=>1,"phraes"=>"The  system  may experience.."]
     *@return array $saveIds ex ["wordIds"=>[1,2], "phraseId"=>1]
     */

    public function insertMyPhrase(array $newPhraseData): array
    {
        DB::beginTransaction();

        try {
            $wordIds = $this->insertWords($newPhraseData['words'], $newPhraseData['user_id'], $newPhraseData['language_code']);
            $phraseId = $this->insertPhrase($newPhraseData['phrase']);

            $this->insertPhraseWordRelations($phraseId, $wordIds);

            $returnObj = [
                'wordIds' => $wordIds,
                'phraseId' => $phraseId,
            ];

            DB::commit();
            return $returnObj;
        } catch (\Exception $e) {
            Log::alert('Myphrase保存エラーが発生しました', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * insert new Phrase to DB
     * @param $phrase string data from request 
     * @return int if of inserted phrase Instace
     */
    private function insertPhrase(String $phrase): int
    {
        $newPhrase =  $newPhrase = Phrase::create([
            'phrase' => $phrase,
        ]);
        $newPhraseId = $newPhrase->id;
        return $newPhraseId;
    }
    /**
     * insert words from request
     * @param array ex. str[], ["obsolate","study"], int $userId,
     * @return array ex int[], [1,2]
     */
    private function insertWords(array $words, int $user_id, string $language_code): array
    {
        $wordsIds = [];
        foreach ($words as $word) {
            //ここにコンディションが必要。もし、ユーザーID,language_code,wordが同じものがすでにDB上にあればCreateしない
            $existingWord = Word::where('user_id', $user_id)->where('language_code', $language_code)->where('word', $word)->first();
            if ($existingWord) {
                $wordsIds[] = $existingWord->id;
                continue;
            }

            $newWord = Word::create(
                [
                    'user_id' => $user_id,
                    'language_code' => $language_code,
                    'word' => $word,
                ]
            );
            $wordsIds[] = $newWord->id;
        }

        return $wordsIds;
    }
    /**
     * insert relation between phrase and words
     * @param  int $praseId The ID of the phrase
     * @param array $wordsIds An array of word IDs  
     * @return void
     */
    private function insertPhraseWordRelations(int $phraseId, array $wordIds): void
    {
        $phrase = Phrase::find($phraseId);
        $phrase->words()->attach($wordIds);
    }

    /**
     * Delete word,phrases,phrase_phrase 
     * @param array $newPhraseData object with wordIds and phraseId. ex ['wordIds' => [3,4], 'phraseId'=>3] 
     * @return void
     */

    public function deleteMyphrase(array $newPhraseData): void
    {
        $wordIds = $newPhraseData['wordIds'];
        $phraseId = $newPhraseData['phraseId'];
        DB::beginTransaction();
        try {
            $phrase = Phrase::find($phraseId);
            $phrase->words()->detach();
            $phrase->delete();
            // 他のフレーズに紐づいているかチェック
            foreach ($wordIds as $wordId) {
                $isExists =  DB::table('phrase_word')->where('word_id', $wordId)->exists();
                if ($isExists) continue;
                Word::find($wordId)->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            Log::alert('Myphrase削除エラーが発生しました', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            DB::rollBack();
            throw $e;
        }
    }
}
