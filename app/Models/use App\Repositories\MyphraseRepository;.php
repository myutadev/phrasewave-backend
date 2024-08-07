use App\Repositories\MyphraseRepository;

$repository = new MyphraseRepository();
$phrase = "The overload of information often makes older methods of learning seem obsolete.";
$newPhraseId = $repository->insertPhrase($phrase);

echo $newPhraseId;


use App\Repositories\MyphraseRepository;

$repository = new MyphraseRepository();
$words = ["obsolate","study"];
$user_id = 1;
$language_code = "en-US"
$newWords = $repository->insertWords($words,$user_id,$language_code);

echo $newPhraseId;

use App\Repositories\MyphraseRepository;

$repository = new MyphraseRepository();
$newPhraseData = [ "words"=>["anonymouse","nail"],"language_code"=>"en-US","user_id"=>1,"phrase"=>"The annonimouse nail.."]


$returnObj = $repository->insertMyPhrase($newPhraseData);

print_r($returnObj);

//

use App\Repositories\MyphraseRepository;

$repository = new MyphraseRepository();
$phraseId = 1;
$wordIds = [3,4];


$repository->insertPhraseWordRelations($phraseId,$wordIds);

-----
delete word
use App\Repositories\MyphraseRepository;

$repository = new MyphraseRepository();

$phraseId = 3;
$wordIds = [3,4];
$newPhraseData = [
'wordIds'=>$wordIds,
'phraseId'=>$phraseId
]


$repository->deleteMyphrase($newPhraseData);
