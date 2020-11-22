<?php

use App\Models\Checklist;
use App\Models\Item;
use Laravel\Lumen\Testing\DatabaseMigrations;

class ItemTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateChecklistItem()
    {
        $api_key = $this->generate_api();
        $checklist = Checklist::factory()->create();
        $this->json('POST','/checklists/'.$checklist->id.'/items',[
            'data'=>['attributes'=>[
                'description'=>'Item Example',
                'due'=> '2019-01-25T07:50:14+00:00',
                'urgency'=> 1,
                'assignee_id'=>10,
            ]]],['Authorization'=>$api_key])->seeJsonContains([
            'description'=>'Item Example',
            'is_completed'=>false,
            'task_id'=>$checklist->task_id
        ]);
    }

    public function testGetChecklistItem()
    {
        $api_key = $this->generate_api();
        $item = Item::factory()->create();
        $this->get('/checklists/'.$item->checklist_id.'/items',['Authorization'=>$api_key])
        ->seeJsonContains([
            'description'=>$item->description,
            'task_id'=>$item->checklist->task_id
        ]);
    }

    public function testGetSingleChecklistItem()
    {
        $api_key = $this->generate_api();
        $item = Item::factory()->create();
        $this->get('/checklists/'.$item->checklist_id.'/items/'.$item->id,['Authorization'=>$api_key])
        ->seeJsonContains([
            'description'=>$item->description,
            'task_id'=>$item->checklist->task_id
        ]);;
    }

    public function testEditChecklistItem()
    {
        $api_key = $this->generate_api();
        $item = Item::factory()->create();
        $assignee = $item->assignee_id;
        $this->json('PATCH','/checklists/'.$item->checklist_id.'/items/'.$item->id,[
            'data'=>['attributes'=>['assignee_id'=>$assignee+1]]
        ],['Authorization'=>$api_key])->seeJsonContains(['assignee_id'=>$assignee+1]);
    }

    public function testDeleteChecklistItem()
    {
        $api_key = $this->generate_api();
        $item = Item::factory()->create();
        $this->delete('/checklists/'.$item->checklist_id.'/items/'.$item->id,[],['Authorization'=>$api_key])->seeStatusCode(204);
    }
}