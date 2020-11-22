<?php

use App\Models\Checklist;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ChecklistTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateChecklist()
    {

        $this->json('POST','/checklists',['data'=>['attributes'=>[
            'object_domain'=>'contact',
            'object_id'=>'1',
            'task_id'=>'123',
            'description'=>'Checklist Example',
            'due'=> '2019-01-25T07:50:14+00:00',
            'urgency'=> 1,
        ]]])->seeJsonContains([
            'task_id'=>'123',
            'is_completed'=>false
        ]);
    }

    public function testGetAllChecklist()
    {
        $checklist = Checklist::factory()->create();
        $this->get('/checklists')->seeJsonContains([
            'task_id'=>$checklist->task_id,
            'is_completed'=>false
        ]);
    }

    public function testGetSingleChecklist()
    {
        $checklist = Checklist::factory()->create();
        $this->get('/checklists/'.$checklist->id)->seeJsonContains([
            'task_id'=>$checklist->task_id,
            'is_completed'=>false
        ]);
    }

    public function testUpdateChecklist()
    {
        $checklist = Checklist::factory()->create();
        $urgency = $checklist->urgency;
        $this->json('PATCH','/checklists/'.$checklist->id,[
            'data'=>['attributes'=>['urgency'=>$urgency+1]]
            ])->seeJsonContains(['urgency'=>$urgency+1]);
    }

    public function testDeleteChecklist()
    {
        $checklist = Checklist::factory()->create();
        $this->delete('/checklists/'.$checklist->id)->seeStatusCode(204);
    }
}
