<?php

use App\Models\Entry;
use App\Models\Exercise;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class ExerciseEntriesTest
 */
class ExerciseEntriesTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_lists_the_exercise_entries_for_one_day()
    {
        $this->logInUser();

        $date = Carbon::today()->format('Y-m-d');

        $content = $this->getEntriesForTheDay($date);

        $this->checkExerciseEntryKeysExist($content[0]);

        $exercise = $content[0]['exercise']['data'];

        $this->assertEquals(1, $exercise['id']);
        $this->assertEquals('kneeling pushups', $exercise['name']);
        $this->assertEquals('1.00', $exercise['stepNumber']);
        $this->assertEquals(1, $exercise['defaultUnit']['data']['id']);

        /**
         * @VP:
         * Can I exclude the description (a faker word) here so it passes?
         * So I can use this code instead of the above code.
         */
//        $this->assertEquals([
//            'id' => 1,
//            'name' => 'kneeling pushups',
//            'step_number' => '1.00'
//        ], $content[0]['exercise']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'reps'
        ], $content[0]['unit']);

        $this->assertEquals(2, $content[0]['sets']);
        $this->assertEquals(10, $content[0]['total']);
        $this->assertEquals(5, $content[0]['quantity']);
        $this->assertCount(2, $content);
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_entries_for_a_specific_exercise_and_date_and_unit()
    {
        $this->logInUser();

        $date = Carbon::today()->format('Y-m-d');
        $response = $this->call('GET', '/api/exerciseEntries/specificExerciseAndDateAndUnit?exercise_id=1&exercise_unit_id=1&date=' . $date);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseEntryKeysExist($content[0]);
        
        foreach ($content as $entry) {
            $this->assertEquals($date, $entry['date']);
            $this->assertEquals(1, $entry['unit']['id']);
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

        $date = Carbon::today()->format('Y-m-d');

        $entry = [
            'date' => $date,
            'exercise_id' => 1,
            'quantity' => 5,
            'unit_id' => 1
        ];

        $response = $this->call('POST', '/api/exerciseEntries', $entry);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseEntryKeysExist($content);

        $this->assertEquals($date, $content['date']);
        $this->assertEquals(1, $content['exercise']['data']['id']);
//        $this->assertEquals(20, $content['daysAgo']);
        $this->assertEquals(1, $content['unit']['id']);
//        $this->assertEquals(20, $content['sets']);
//        $this->assertEquals(20, $content['total']);
        $this->assertEquals(5, $content['quantity']);
        $this->assertEquals(0, $content['exercise']['data']['lastDone']);
        $this->assertEquals(7, $content['exercise']['data']['dueIn']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_add_an_exercise_set()
    {
        $this->logInUser();

        $date = Carbon::today()->format('Y-m-d');

//        $this->assertCount(19, $this->user->exerciseEntries);
//        $this->assertCount(2, $this->getEntriesForTheDay($date));

        $entry = [
            'date' => $date,
            'exercise_id' => 1,
            'exerciseSet' => true
        ];

        $response = $this->call('POST', '/api/exerciseEntries', $entry);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseEntryKeysExist($content);

        //Todo: check values are according to default exercise set values
        $this->assertEquals($date, $content['date']);
        $this->assertEquals(1, $content['exercise']['data']['id']);
//        $this->assertEquals(20, $content['daysAgo']);
        $this->assertEquals(1, $content['unit']['id']);
        $this->assertEquals(3, $content['sets']);
//        $this->assertEquals(20, $content['total']);
        $this->assertEquals(20, $content['quantity']);
        $this->assertEquals(0, $content['exercise']['data']['lastDone']);
        $this->assertEquals(7, $content['exercise']['data']['dueIn']);

        $entriesForTheDay = $this->getEntriesForTheDay($date);

        $this->assertCount(20, $this->user->exerciseEntries()->get());
        //The number shouldn't increase because it was an exericise/unit combination already done that day
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
        $this->assertEquals(5, $entry->quantity);

        $response = $this->call('PUT', '/api/exerciseEntries/'.$entry->id, [
            'quantity' => 20
        ]);
        
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseEntryKeysExist($content);

        $this->assertEquals(20, $content['quantity']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_delete_an_exercise_entry()
    {
        $this->logInUser();

        $date = Carbon::today()->format('Y-m-d');

        $entry = new Entry([
            'date' => $date,
            'quantity' => 501,
        ]);

        $entry->user()->associate($this->user);
        $entry->exercise()->associate(Exercise::find(1));
        $entry->unit()->associate(Unit::find(1));
        $entry->save();

        $this->seeInDatabase('exercise_entries', [
            'date' => $date,
            'exercise_id' => 1,
            'quantity' => 501,
            'exercise_unit_id' => 1
        ]);

        $response = $this->call('DELETE', '/api/exerciseEntries/'.$entry->id);
//        dd($response);
        $this->assertEquals(204, $response->getStatusCode());
        $this->missingFromDatabase('exercise_entries', [
            'date' => $date,
            'exercise_id' => 1,
            'quantity' => 501,
            'exercise_unit_id' => 1
        ]);

        $response = $this->call('DELETE', '/api/exerciseEntries/' . $entry->id);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     *
     * @param $date
     * @return mixed
     */
    private function getEntriesForTheDay($date)
    {
        $response = $this->apiCall('GET', '/api/exerciseEntries/' . $date);

        $this->assertEquals(200, $response->getStatusCode());

        return json_decode($response->getContent(), true);
    }
}
