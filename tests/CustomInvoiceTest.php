<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\InvoiceTemplate;

class CustomInvoiceTest extends TestCase
{
    use DatabaseTransactions;

    private $userAdmin;
    private $settings;
    private $template;

    public function setUp()
    {
        parent::setUp();

        $this->userAdmin = factory(App\User::class)->create();
        $this->userAdmin->roles()->attach(2);
        $this->userAdmin = \Mockery::mock($this->userAdmin)->makePartial();
        $this->userAdmin->shouldReceive('isPremium')
            ->andReturn(true);

        $this->settings = \App\Factories\SettingsFactory::create($this->userAdmin->company_id);
        $this->settings->set('taxable', false);
        App\Services\RestoreDefaultTemplates::restoreDefaults($this->userAdmin->company_id);
        $this->template = InvoiceTemplate::first();
    }

    public function testTemplatesPageLayout()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice_template')
            ->see('Standard Receipt')
            ->see('Standard Quote')
            ->see('Standard Invoice')
            ->see('btnCreate')
            ->see('btnDefaults');
    }

    public function testEditTemplate()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice_template/'.$this->template->id.'/edit')
            ->see('name="title"')
            ->see('name="type"')
            ->see('name="template"')
            ->see('btnUpdate')
            ->see('btnDelete');
    }

    public function testBlankTitle()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice_template/'.$this->template->id.'/edit')
            ->type('', 'title')
            ->press('btnUpdate')
            ->see(trans('validation.required', ['attribute' => 'title']));
    }

    public function testBlankType()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice_template/'.$this->template->id.'/edit')
            ->select('', 'type')
            ->press('btnUpdate')
            ->see(trans('validation.required', ['attribute' => 'type']));
    }

    public function testBlankContent()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice_template/'.$this->template->id.'/edit')
            ->type('', 'template')
            ->press('btnUpdate')
            ->see(trans('validation.required', ['attribute' => 'template']));
    }

    public function testDuplicateTitleNotAllowed()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice_template/create')
            ->type('Standard Invoice', 'title')
            ->select('invoice', 'type')
            ->type('something cool', 'template')
            ->press('btnSubmit')
            ->see(trans('validation.unique', ['attribute' => 'title']));
    }

    public function testCompanySeparation()
    {
        $company2 = factory(App\Company::class)->create();
        $user2 = factory(App\User::class)->create();
        $user2->roles()->attach(2);
        $user2->company()->associate($company2);
        $user2->save();
        $user2 = \Mockery::mock($user2)->makePartial();
        $user2->shouldReceive('isPremium')
            ->andReturn(true);

        $this->be($user2);
        $this->actingAs($user2)
            ->visit('/invoice_template/create')
            ->type('User2 Invoice', 'title')
            ->select('invoice', 'type')
            ->type('something cool', 'template')
            ->press('btnSubmit');

        $this->be($this->userAdmin);
        $this->actingAs($this->userAdmin)
            ->visit('/invoice_template')
            ->dontSee('User2 Invoice');
    }
}
