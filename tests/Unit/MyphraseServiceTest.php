<?php

namespace Tests\Unit;

use App\Models\Word;
use App\Repositories\LanguageRepository;
use App\Repositories\MyphraseRepository;
use App\Services\MyphraseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class MyphraseServiceTest extends TestCase
{
    use RefreshDatabase;
    protected $myphraseService;
    protected $myphraseRepositoryMock;
    protected $languageRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->myphraseRepositoryMock = Mockery::mock(MyphraseRepository::class);
        $this->languageRepositoryMock = Mockery::mock(LanguageRepository::class);
        $this->myphraseService = new MyphraseService($this->myphraseRepositoryMock, $this->languageRepositoryMock);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * test that process request data correctly
     *@test
     *@return void
     */
    public function test_processRequestData_process_data_correctrly(): void
    {
        $this->languageRepositoryMock->shouldReceive('getLangCodeByName')
            ->with('English (US)')
            ->once()
            ->andReturn('en-US');

        $this->myphraseRepositoryMock->shouldReceive('insertMyPhrase')->with([
            "user_id" => 2,
            "language_code" => "en-US",
            "words" => ["obsolate", "study"],
            "phrase" => "The obsolate study"
        ])->once()->andReturn(["wordIds" => [1, 2], "phraseId" => 1]);

        $newPhraseData = [
            "user_id" => 2,
            "language" => "English (US)",
            "words" => ["obsolate", "study"],
            "phrase" => "The obsolate study"
        ];

        $expectedObject = ["wordIds" => [1, 2], "phraseId" => 1];

        $result = $this->myphraseService->createMyphrase($newPhraseData);

        assertEquals($expectedObject, $result);
    }

    /**
     * test that delete word correctly
     *@test
     *@return void
     */
    public function test_delete_word_success(): void
    {
        $word = Word::factory()->create();

        $this->myphraseRepositoryMock
            ->shouldReceive('deleteWord')
            ->with($word->id)
            ->once()
            ->andReturn($word);

        $result = $this->myphraseRepositoryMock->deleteWord($word->id);

        $this->assertInstanceOf(Word::class, $result);
        $this->assertEquals($word->id, $result->id);
    }
}
