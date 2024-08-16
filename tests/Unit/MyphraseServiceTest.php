<?php

namespace Tests\Unit;

use App\Repositories\LanguageRepository;
use App\Repositories\MyphraseRepository;
use App\Services\MyphraseService;
use Mockery;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class MyphraseServiceTest extends TestCase
{
    protected $myphraseService;
    protected $myphraseRepositoryMock;
    protected $languageRepositoryMock;

    public function setUp(): void
    {
        $this->myphraseRepositoryMock = Mockery::mock(MyphraseRepository::class);
        $this->languageRepositoryMock = Mockery::mock(LanguageRepository::class);
        $this->myphraseService = new MyphraseService($this->myphraseRepositoryMock, $this->languageRepositoryMock);
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
}
