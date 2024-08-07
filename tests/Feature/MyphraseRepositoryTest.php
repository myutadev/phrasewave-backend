<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Repositories\MyphraseRepository;
use Database\Seeders\LanguageSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use function PHPUnit\Framework\assertCount;

class MyphraseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $myphraseRepository;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->myphraseRepository = new MyphraseRepository();
        $this->user = User::factory()->create([
            'name' => 'test',
            'email' => 'testdbuser@test.co.jp',
            'password' => Hash::make('1111'), // パスワードをハッシュ化
            'email_verified_at' => now(), // メール確認日時
            'remember_token' => Str::random(10), // remember_tokenを生成
        ]);
        $this->seed(LanguageSeeder::class);
    }

    protected function tearDown(): void
    {
        $this->artisan('migrate:refresh');

        parent::tearDown();
    }




    /**
     * Test that incert words, languages, word_language correctly
     * @test
     * @covers \App\Repositories\MyphraseRepository::insertMyphrase
     * $return void
     */
    public function test_insert_words_phrase_phrasewordsRelation_correctly(): void
    {
        $newPhraseData = [
            'words' => ['test', 'example'],
            'user_id' => 1,
            'language_code' => 'en-US',
            'phrase' => 'This is a test example',
        ];


        $result = $this->myphraseRepository->insertMyPhrase($newPhraseData);

        //返り値の構造を確認
        $this->assertArrayHasKey('wordIds', $result);
        $this->assertArrayHasKey('phraseId', $result);

        //DBに正しくデータが入っているか?
        $this->assertDatabaseHas('words', [
            'word' => 'test',
            'user_id' => 1,
            'language_code' => 'en-US',
        ]);
        $this->assertDatabaseHas('words', [
            'word' => 'example',
            'user_id' => 1,
            'language_code' => 'en-US',
        ]);
        $this->assertDatabaseHas('phrases', [
            'phrase' => 'This is a test example',
        ]);

        //中間テーブルにちゃんと紐づけられているか?
        foreach ($result['wordIds'] as $wordId) {
            $this->assertDatabaseHas('phrase_word', [
                'phrase_id' => $result['phraseId'],
                'word_id' =>  $wordId
            ]);
        }

        $newPhraseData2 = [
            'words' => ['obsolate', 'example'],
            'user_id' => 1,
            'language_code' => 'en-US',
            'phrase' => 'Obsolate example',
        ];

        $this->myphraseRepository->insertMyPhrase($newPhraseData2);

        //wordsの中に'obsolate'が入ってるか?
        $this->assertDatabaseHas('words', [
            'word' => 'obsolate',
            'user_id' => 1,
            'language_code' => 'en-US',
        ]);

        //wordsの中にexampleが重複していないか?
        $savedTargetWord = DB::table('words')->where('user_id', 1)->where('language_code', 'en-US')->where('word', 'example')->get();
        assertCount(1, $savedTargetWord, "The word 'example' should appear only once for the given user and language");
    }

    /**
     * Test that delete words, languages, word_language correctly. 
     * @test
     * @covers \App\Repositories\MyphraseRepository::deleteMyphrase
     * $return void
     */
    public function test_delete_words_phrase_phrase_ordsRelation_correctly(): void
    {
        $insertData1 = [
            'words' => ['test', 'example'],
            'user_id' => 1,
            'language_code' => 'en-US',
            'phrase' => 'This is a test example',
        ];

        $insertData2 = [
            'words' => ['obsolate', 'example'],
            'user_id' => 1,
            'language_code' => 'en-US',
            'phrase' => 'Obsolate example',
        ];
        $result1 = $this->myphraseRepository->insertMyPhrase($insertData1);
        $result2 = $this->myphraseRepository->insertMyPhrase($insertData2);

        $this->myphraseRepository->deleteMyphrase($result1);
        // word=testは消えている、word=exampleは消えていないはず
        $this->assertDatabaseHas('words', [
            'word' => 'example',
            'user_id' => 1,
            'language_code' => 'en-US',

        ]);

        $this->assertDatabaseMissing('words', [
            'word' => 'test',
            'user_id' => 1,
            'language_code' => 'en-US',
        ]);


        $this->myphraseRepository->deleteMyphrase($result2);


        $this->assertDatabaseMissing('words', [
            'word' => 'obsolate',
            'user_id' => 1,
            'language_code' => 'en-US',
        ]);

        $this->assertDatabaseMissing('words', [
            'word' => 'example',
            'user_id' => 1,
            'language_code' => 'en-US',
        ]);
    }
}
