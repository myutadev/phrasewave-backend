<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Repositories\LanguageRepository;
use Database\Seeders\LanguageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class LanguageRepositoryTest extends TestCase
{



    use RefreshDatabase;

    protected $languageRepository;
    protected $language;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(LanguageSeeder::class);
        $this->language = new Language();
        $this->languageRepository = new LanguageRepository($this->language);
    }

    public function tearDown(): void
    {
        $this->artisan('migrate:refresh');
        parent::tearDown();
    }
    /**
     * test that get all language data collection
     *@test 
     * @covers \App\Repositories\LanguageRepository:: getAll()
     * $return void
     */
    public function test_getall_languages_correctly(): void
    {
        $expectedData = $this->language->all();

        $data = $this->languageRepository->getAll($this->language);

        assertEquals($expectedData, $data);
    }
    /**
     * test that retrieve correct langauge_code by languge name
     *@test 
     * @covers \App\Repositories\LanguageRepository:: getLangCodeByName()
     * $return void
     */
    public function test_getLangCodeByName_correctly(): void
    {
        $expectedData = "en-US";

        $data = $this->languageRepository->getLangCodeByName("English (US)");

        assertEquals($expectedData, $data);
    }
}
