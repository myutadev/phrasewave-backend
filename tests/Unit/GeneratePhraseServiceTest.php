<?php

namespace Tests\Unit;

use App\Services\GeneratePhraseService;
use PHPUnit\Framework\TestCase;

class GeneratePhraseServiceTest extends TestCase
{

    protected $generatePhraseService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generatePhraseService = new GeneratePhraseService();
    }

    /**
     * Test that getWordPhraseObj correctly processes form data and returns expected object.
     *
     * @test
     * @covers \App\Services\GeneratePhraseService::getWordPhraseObj
     * @return void
     */
    public function test_getWordPhraseObj_return_expected_object(): void
    {
        $formData  = [
            "language" => "English (British)",  "word1" => "overload", "word2" => "obsolate", "word3" => null, "word4" => null, "word5" => null,
            "context1" => "The power shut off after all the circuits were overloaded", "context2" => null, "context3" => null, "context4" => null, "context5" => null
        ];

        $expectedData = [
            [
                'word' => 'overload',
                'context' => 'The power shut off after all the circuits were overloaded'

            ], [
                'word' => 'obsolate',
                'context' => ''
            ]

        ];

        $result = $this->generatePhraseService->getWordPhraseObj($formData);

        $this->assertEquals($expectedData, $result);
    }
}
