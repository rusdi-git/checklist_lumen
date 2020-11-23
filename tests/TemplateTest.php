<?php

use App\Models\Template;
use App\Models\TemplateItem;
use Laravel\Lumen\Testing\DatabaseMigrations;

class TemplateTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateTemplate()
    {
        $api_key = $this->generate_api();
        $this->json('POST','/checklists/templates',['data'=>['attributes'=>[
            'name'=>'mytemplate',
            'checklist'=>[
                'description'=>'mydescription',
                'due_interval'=>1,
                'due_unit'=>'day'
            ],
            'items'=>[[
                'description'=>'myitem',
                'due_interval'=>1,
                'due_unit'=>'day',
                'urgency'=>1
            ]],
        ]]],['Authorization'=>$api_key])->seeJsonContains([
            'name'=>'mytemplate',
            'description'=>'mydescription',
            'description'=>'myitem'
        ]);
    }

    public function testGetTemplate()
    {
        $api_key = $this->generate_api();
        $template = Template::factory()->has(TemplateItem::factory(),'items')->create();
        $this->get('/checklists/templates',['Authorization'=>$api_key])->seeJsonContains([
            'name'=>$template->name,
            'description'=>$template->description
        ]);
    }
}