<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Repositories\MyphraseRepository;
use Database\Seeders\LanguageSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

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
    public function test_delete_words_phrase_phrase_words_Relation_correctly(): void
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

        // testとexampleが含まれている例文を削除
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

    /**
     * Test that get All words by user correctly. 
     * @test
     * @covers \App\Repositories\MyphraseRepository::getUserWordsWithPhrases
     * $return void
     */
    public function test_get_user_words_with_phrases_and_language_correctly(): void
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

        $resultData = $this->myphraseRepository->getUserWordsWithPhrases(1);
        // test, example,obsolateで長さ3の配列ができる。
        assertEquals(3, count($resultData));

        $expectedResult = [
            [
                'test' => [
                    'phrases' => [
                        "This is a test example"
                    ],
                    'language' => 'English (US)'
                ]
            ],
            [
                'example' => [
                    'phrases' => [
                        "This is a test example",
                        "Obsolate example"
                    ],
                    'language' => 'English (US)'

                ]
            ],
            [
                'obsolate' => [
                    'phrases' => [
                        "Obsolate example"
                    ],
                    'language' => 'English (US)'
                ]

            ]

        ];

        assertEquals($expectedResult, $resultData);
    }


    /**
     * Test that softDelete function for word. 
     * @test
     * @covers \App\Repositories\MyphraseRepository::deleteWord and restoreWord
     * $return void
     */
    public function test_softDelete_word_if_deleted_at_has_timestamp_after_the_process_and_restore_correctly(): void
    {
        $insertData1 = [
            'words' => ['test', 'example'],
            'user_id' => 1,
            'language_code' => 'en-US',
            'phrase' => 'This is a test example',
        ];

        $this->myphraseRepository->insertMyPhrase($insertData1);

        $result = $this->myphraseRepository->deleteWord(1);
        // word=testは消えている、word=exampleは消えていないはず
        $this->assertDatabaseHas('words', [
            'id' => 1, // ソフトデリートされたワードのID
            'deleted_at' => now(), // もしくはアサート用のタイムスタンプを使う
        ]);

        // 4. 別のワードがまだ存在することを確認
        $this->assertDatabaseHas('words', [
            'word' => 'example',
            'user_id' => 1,
            'language_code' => 'en-US',
        ]);

        // 5. 復元処理を実行
        $restoredWord = $this->myphraseRepository->restoreWord(1);

        // 6. 復元後、deleted_atがNULLになっていることを確認
        $this->assertDatabaseHas('words', [
            'id' => 1,
            'deleted_at' => null,
        ]);
    }
}
