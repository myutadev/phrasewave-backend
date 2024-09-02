<?php

namespace Tests\Unit;

use App\Services\GeneratePhraseService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;

class GeneratePhraseServiceTest extends TestCase
{

    protected $generatePhraseService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generatePhraseService = new GeneratePhraseService();
    }

    /**
     * Test that getWordPhraseObj correctly processes form data and returns the expected object.
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
    /**
     *Test that generateStudyPhrases return Collection.
     * 
     *@test
     *@covers \App\Service\GeneratePhraseService::generateStudyPhrases
     * @return void
     */
    public function test_generateStudyPhrases_return_Collection(): void
    {

        OpenAI::fake([
            CreateResponse::fake([
                'choices' => [
                    [
                        'message' => [
                            'content' => '[{"usedWords":["overload", "obsolete"], "generatedPhrase":"When technology becomes obsolete, it can lead to an overload of unnecessary information."}, {"usedWords":["overload", "obsolete"], "generatedPhrase":"The company had to upgrade their systems to avoid the overload caused by using obsolete equipment."}, {"usedWords":["overload", "obsolete"], "generatedPhrase":"As devices become obsolete, they risk being overloaded with tasks they werent designed to handle."}]'
                        ]
                    ]
                ]
            ])
        ]);


        $studyLang = "English(US)";
        $studyWords = [
            [
                'word' => 'overload',
                'context' => 'The power shut off after all the circuits were overloaded'
            ],
            [
                'word' => 'obsolete',
                'context' => ''
            ]
        ];


        $this->assertInstanceOf(Collection::class, $this->generatePhraseService->generateStudyPhrases($studyLang, $studyWords));
    }

    /**
     *Test that generatePhrase method return Collection 
     *@test
     *@param string $request html request from frontend
     *@covers \App\Service\GeneratePhraseService::generateStudyPhrases
     * @return void
     */

    public function test_generatePhrase_return_collection(): void
    {
        OpenAI::fake([
            CreateResponse::fake([
                'choices' => [
                    [
                        'message' => [
                            'content' => '[{"usedWords":["overload", "obsolete"], "generatedPhrase":"When technology becomes obsolete, it can lead to an overload of unnecessary information."}, {"usedWords":["overload", "obsolete"], "generatedPhrase":"The company had to upgrade their systems to avoid the overload caused by using obsolete equipment."}, {"usedWords":["overload", "obsolete"], "generatedPhrase":"As devices become obsolete, they risk being overloaded with tasks they werent designed to handle."}]'
                        ]
                    ]
                ]
            ])
        ]);

        $data = [
            "language" => "English(US)",
            "word1" => "overload",
            "word2" => "obsolete",
            "word3" => "",
            "word4" => "",
            "word5" => "",
            "context1" => "The power shut off after all the circuits were overloaded.",
            "context2" => "",
            "context3" => "",
            "context4" => "",
            "context5" => ""
        ];

        $request = new Request([], [], [], [], [], [], json_encode($data));
        $request->headers->set('Content-Type', 'application/json');

        $this->assertInstanceOf(Collection::class, $this->generatePhraseService->generatePhrase($request));
    }
}
