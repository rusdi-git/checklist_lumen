<?php

use App\Models\Checklist;
use Laravel\Lumen\Testing\DatabaseMigrations;

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
        $api_key = $this->generate_api();
        $this->json('POST','/checklists',['data'=>['attributes'=>[
            'object_domain'=>'contact',
            'object_id'=>'1',
            'task_id'=>'123',
            'description'=>'Checklist Example',
            'due'=> '2019-01-25T07:50:14+00:00',
            'urgency'=> 1,
        ]]],['Authorization'=>$api_key])->seeJsonContains([
            'task_id'=>'123',
            'is_completed'=>false
        ]);
    }

    public function testGetAllChecklist()
    {
        $api_key = $this->generate_api();
        $checklist = Checklist::factory()->create();
        $this->get('/checklists',['Authorization'=>$api_key])->seeJsonContains([
            'task_id'=>$checklist->task_id,
            'is_completed'=>false
        ]);
    }

    public function testGetSingleChecklist()
    {
        $checklist = Checklist::factory()->create();
        $api_key = $this->generate_api();
        $this->get('/checklists/'.$checklist->id,['Authorization'=>$api_key])->seeJsonContains([
            'task_id'=>$checklist->task_id,
            'is_completed'=>false
        ]);
    }

    public function testUpdateChecklist()
    {
        $api_key = $this->generate_api();
        $checklist = Checklist::factory()->create();
        $urgency = $checklist->urgency;
        $this->json('PATCH','/checklists/'.$checklist->id,[
            'data'=>['attributes'=>['urgency'=>$urgency+1]]
        ],['Authorization'=>$api_key])->seeJsonContains(['urgency'=>$urgency+1]);
    }

    public function testDeleteChecklist()
    {
        $checklist = Checklist::factory()->create();
        $api_key = $this->generate_api();
        $this->delete('/checklists/'.$checklist->id,[],['Authorization'=>$api_key])->seeStatusCode(204);
    }
}
