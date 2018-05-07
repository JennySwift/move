<?php

use App\Models\Entry;
use App\Models\Exercise;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class ExerciseEntriesTest
 */
class ExerciseEntriesTest extends TestCase {

    use DatabaseTransactions;

    private $url = '/api/entries/';

    /**
     * @test
     * @return void
     */
    public function it_gets_entries_for_a_specific_exercise_and_date_and_unit()
    {
        $this->logInUser();
        $this->markTestIncomplete();

        $date = Carbon::today()->format('Y-m-d');
        $response = $this->call('GET', $this->url . 'specificExerciseAndDateAndUnit?exercise_id=1&unit_id=1&date=' . $date);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseEntryKeysExist($content[0]);
        
        foreach ($content as $entry) {
            $this->assertEquals($date, $entry['date']);
            $this->assertEquals(1, $entry['unit']['data']['id']);
            $this->assertEquals(1, $entry['exercise']['data']['id']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_add_a_new_exercise_entry()
    {
        $this->logInUser();
        $this->markTestIncomplete();

        $date = Carbon::today()->format('Y-m-d');

        $entry = [
            'date' => $date,
            'exercise_id' => 1,
            'quantity' => 5,
            'unit_id' => 1,
            'level' => 1
        ];

        $response = $this->call('POST', $this->url, $entry);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseEntryKeysExist($content);

        $this->assertEquals($date, $content['date']);
        $this->assertEquals(1, $content['exercise']['data']['id']);
//        $this->assertEquals(20, $content['daysAgo']);
        $this->assertEquals(1, $content['unit']['data']['id']);
//        $this->assertEquals(20, $content['sets']);
//        $this->assertEquals(20, $content['total']);
        $this->assertEquals(5, $content['quantity']);
        $this->assertEquals(0, $content['exercise']['data']['lastDone']);
        $this->assertEquals(7, $content['exercise']['data']['dueIn']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @todo: This was failing after upgrade. Not sure if it already was beforehand.
     * @test
     * @return void
     */
    public function it_can_add_an_exercise_set()
    {
        $this->markTestIncomplete();
        $this->logInUser();

        $date = Carbon::today()->format('Y-m-d');

        $this->assertCount(19, $this->user->exerciseEntries);
        $this->assertCount(2, $this->getEntriesForTheDay($date));

        $entry = [
            'date' => $date,
            'exercise_id' => 1,
            'useExerciseDefaults' => true
        ];

        $response = $this->call('POST', $this->url, $entry);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseEntryKeysExist($content);

        //Todo: check values are according to default exercise set values
        $this->assertEquals($date, $content['date']);
        $this->assertEquals(1, $content['exercise']['data']['id']);
//        $this->assertEquals(20, $content['daysAgo']);
        $this->assertEquals(1, $content['unit']['data']['id']);
        $this->assertEquals(3, $content['sets']);
//        $this->assertEquals(20, $content['total']);
        $this->assertEquals(20, $content['quantity']);
        $this->assertEquals(0, $content['exercise']['data']['lastDone']);
        $this->assertEquals(7, $content['exercise']['data']['dueIn']);

        $entriesForTheDay = $this->getEntriesForTheDay($date);

        $this->assertCount(20, $this->user->exerciseEntries()->get());
        //The number shouldn't increase because it was an exercise/unit combination already done that day
        $this->assertCount(2, $entriesForTheDay);
        $this->assertEquals(3, $entriesForTheDay[0]['sets']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_update_an_exercise_entry()
    {
        DB::beginTransaction();
        $this->logInUser();

        $entry = Entry::forCurrentUser()->first();
        $this->assertEquals(50, $entry->quantity);

        $response = $this->call('PUT', $this->url . $entry->id, [
            'quantity' => 20
        ]);
        
        $content = $this->getContent($response);
//        dd($content);

        $this->checkExerciseEntryKeysExist($content);

        $this->assertEquals(20, $content['quantity']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     */
    public function it_can_delete_an_exercise_entry()
    {
        DB::beginTransaction();
        $this->logInUser();

        $entry = Entry::forCurrentUser()->first();

        $response = $this->call('DELETE', $this->url . $entry->id);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', $this->url . $entry->id);
        $this->assertEquals(404, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * The show route for entries doesn't exist yet
     * @test
     */
//    public function it_cannot_show_an_entry_that_belongs_to_another_user()
//    {
//        $this->logInUser();
//
//        $entry = Entry::where('user_id', 2)->first();
//
//        $response = $this->call('GET', '/api/exerciseEntries/' . $entry->id);
//        $content = json_decode($response->getContent(), true);
//            dd($content);
//
//        $this->assertArrayHasKey('error', $content);
//        $this->assertContains('Unauthorised', $content['error']);
//
//        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
//    }

    /**
     *
     * @param $date
     * @return mixed
     */
    private function getEntriesForTheDay($date)
    {
        $response = $this->apiCall('GET', $this->url . $date);

        $this->assertEquals(200, $response->getStatusCode());

        return $this->getContent($response);
    }
}
