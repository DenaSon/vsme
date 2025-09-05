<?php

namespace Database\Seeders;

use App\Models\Disclosure;
use App\Models\Module;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;

class VsmeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {



        // 1) Questionnaire
        $q = Questionnaire::query()->updateOrCreate(
            ['code' => 'vsme', 'version' => 'v1'],
            [
                'locale_default' => 'en',
                'is_active'      => true,
                'published_at'   => now(),
            ]
        );

        // 2) Modules
        $basic = Module::query()->updateOrCreate(
            ['questionnaire_id' => $q->id, 'code' => 'basic'],
            ['order' => 1, 'is_active' => true]
        );

        $comp = Module::query()->updateOrCreate(
            ['questionnaire_id' => $q->id, 'code' => 'comprehensive'],
            ['order' => 2, 'is_active' => true]
        );

        // 3) Disclosures
        $basicCodes = ['b1','b2','b3','b4','b5','b6','b7','b8','b9','b10','b11'];
        $compCodes  = ['c1','c2','c3','c4','c5','c6','c7','c8','c9'];

        foreach ($basicCodes as $i => $code) {
            Disclosure::query()->updateOrCreate(
                ['questionnaire_id'=>$q->id,'module_id'=>$basic->id,'code'=>$code],
                ['order'=>$i+1,'is_active'=>true,'is_applicable_by_default'=>true]
            );
        }

        foreach ($compCodes as $i => $code) {
            Disclosure::query()->updateOrCreate(
                ['questionnaire_id'=>$q->id,'module_id'=>$comp->id,'code'=>$code],
                ['order'=>$i+1,'is_active'=>true,'is_applicable_by_default'=>true]
            );
        }

        // 4) Questions for B1
        $b1 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id'        => $basic->id,
            'code'             => 'b1'
        ])->first();

        $this->seedB1Q1($b1);
        $this->seedB1Q2($b1);
        $this->seedB1Q3($b1);

        $this->seedB1Q4($b1);
    }

    /**
     * B1.Q1
     */
    protected function seedB1Q1(Disclosure $b1): void
    {
        $q1 = Question::updateOrCreate(
            ['disclosure_id'=>$b1->id,'key'=>'b1.q1'],
            [
                'number'=>1,
                'type'=>'radio-cards',
                'title' => [
                    'en' => 'Which option has the undertaking selected?',
                    'fi' => 'Minkä vaihtoehdon yritys on valinnut?'
                ],

                'rules'=>['required'=>true],
                'order'=>1,
                'is_active'=>true,
                'help_official' => [
                    'en' => '1-Our ESG framework ensures that our operations adhere to the highest standards of environmental responsibility, social fairness, and corporate governance. We are committed to transparent reporting and continuous improvement across all ESG dimensions.',
                    'fi' => '1-ESG-kehyksemme varmistaa, että toimintamme noudattaa korkeimpia ympäristövastuun, sosiaalisen oikeudenmukaisuuden ja hyvän hallintotavan standardeja. Olemme sitoutuneet läpinäkyvään raportointiin ja jatkuvaan parantamiseen kaikilla ESG-alueilla.'
                ],
                'help_friendly' => [
                    'en' => 'We take ESG seriously! From reducing our environmental footprint to supporting our communities and maintaining strong governance, we’re constantly improving and sharing our progress openly.',
                    'fi' => 'Suhtaudumme ESG:hen tosissamme! Pienennämme ympäristöjalanjälkeämme, tuemme yhteisöjämme ja ylläpidämme vahvaa hallintoa – parannamme jatkuvasti ja kerromme edistymisestämme avoimesti.'
                ],
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id'=>$q1->id,'kind'=>'option','key'=>'option_a'],
            ['value'=>'A','label'=>['en'=>'Basic Module'],'sort'=>1,'is_active'=>true]
        );

        QuestionOption::updateOrCreate(
            ['question_id'=>$q1->id,'kind'=>'option','key'=>'option_b'],
            ['value'=>'B','label'=>['en'=>'Basic + Comprehensive'],'sort'=>2,'is_active'=>true]
        );
    }

    /**
     * B1.Q2
     */
    protected function seedB1Q2(Disclosure $b1): void
    {
        $q2 = Question::updateOrCreate(
            ['disclosure_id'=>$b1->id,'key'=>'b1.q2'],
            [
                'number'=>2,
                'type'=>'radio-cards',
                'title' => [
                    'en' => 'Will your company be reporting sustainability data?',
                    'fi' => 'Raportoiko yrityksesi kestävyystietoja?'
                ],
                'rules'=>['required'=>true,'in'=>['individual','consolidated']],
                'order'=>2,
                'is_active'=>true,
                'help_official' => [
                    'en' => '2-A basic report provides an overview of key findings in a concise format, while a comprehensive report includes deeper analysis, detailed data, and broader context. Together, they support informed decision-making at different levels.',
                    'fi' => '2-Perusraportti tarjoaa yleiskuvan keskeisistä havainnoista tiiviissä muodossa, kun taas kattava raportti sisältää syvällisemmän analyysin, yksityiskohtaiset tiedot ja laajemman kontekstin. Yhdessä ne tukevat päätöksentekoa eri tasoilla.'
                ],
                'help_friendly' => [
                    'en' => 'Think of the basic report as a snapshot – short and clear. The comprehensive report is like the full story with all the details, charts, and background. Both are useful depending on how much depth you need.',
                    'fi' => 'Ajattele perusraporttia pikakuvana – lyhyt ja selkeä. Kattava raportti taas on kuin koko tarina kaikkine yksityiskohtineen, kaavioineen ja taustatietoineen. Molemmista on hyötyä riippuen siitä, kuinka paljon syvyyttä tarvitset.'
                ],

            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id'=>$q2->id,'kind'=>'option','key'=>'report_individual'],
            ['value'=>'individual','label'=>['en'=>'Individually'],'sort'=>1,'is_active'=>true]
        );

        QuestionOption::updateOrCreate(
            ['question_id'=>$q2->id,'kind'=>'option','key'=>'report_consolidated'],
            ['value'=>'consolidated','label'=>['en'=>'In a consolidated fashion'],'sort'=>2,'is_active'=>true]
        );
    }


    /**
     * B1.Q3
     */
    protected function seedB1Q3(Disclosure $b1): void
    {
        $q3 = Question::updateOrCreate(
            ['disclosure_id' => $b1->id, 'key' => 'b1.q3'],
            [
                'number' => 3,
                'type'   => 'repeatable-group',
                'title' => [
                    'en' => 'Reporting company/companies',
                    'fi' => 'Raportoiva yritys/yritykset'
                ],
                'help_official' => [
                    'en' => '3-Our ESG framework ensures that our operations adhere to the highest standards of environmental responsibility, social fairness, and corporate governance. We are committed to transparent reporting and continuous improvement across all ESG dimensions.',
                    'fi' => '3-ESG-kehyksemme varmistaa, että toimintamme noudattaa korkeimpia ympäristövastuun, sosiaalisen oikeudenmukaisuuden ja hyvän hallintotavan standardeja. Olemme sitoutuneet läpinäkyvään raportointiin ja jatkuvaan parantamiseen kaikilla ESG-alueilla.'
                ],
                'help_friendly' => [
                    'en' => '3-We take ESG seriously! From reducing our environmental footprint to supporting our communities and maintaining strong governance, we’re constantly improving and sharing our progress openly.',
                    'fi' => '3-Suhtaudumme ESG:hen tosissamme! Pienennämme ympäristöjalanjälkeämme, tuemme yhteisöjämme ja ylläpidämme vahvaa hallintoa – parannamme jatkuvasti ja kerromme edistymisestämme avoimesti.'
                ],

                'rules'  => [
                    'required'   => true,
                    'array'      => true,
                    'min'        => 1,
                    // قوانین هر ردیف
                    'item_rules' => [
                        'name'            => ['required','string','max:200'],
                        'street_address'  => ['required','string','max:300'],
                        'city'            => ['required','string','max:120'],
                        'country'         => ['required'],   // نمونه
                        'geolocation'     => ['required','string','max:120'],   // lat/lon یا plus code
                        'nace'            => ['required'],
                    ],
                ],
                'order'     => 3,
                'is_active' => true,
                'meta'      => [

                    'max_rows_if' => [
                        ['when' => ['key' => 'b1.q2', 'eq' => 'individual'], 'max' => 1],
                    ],
                ],
            ]
        );


        QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'name'],
            [
                'label'     => ['en' => 'Company name'],
                'extra'     => ['type' => 'text', 'placeholder' => 'ProVision'],
                'sort'      => 1,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'street_address'],
            [
                'label'     => ['en' => 'Street Address'],
                'extra'     => ['type' => 'text', 'placeholder' => 'Mäkelänkatu 25 B 13'],
                'sort'      => 2,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'city'],
            [
                'label'     => ['en' => 'City / Town'],
                'extra'     => ['type' => 'text', 'placeholder' => 'Helsinki'],
                'sort'      => 3,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'country'],
            [
                'label'     => ['en' => 'Country'],
                'extra'     => [
                    'type'    => 'select',
                    'choices' => [
                        ['value'=>'FI','label'=>'Finland'],
                        ['value'=>'SE','label'=>'Sweden'],
                        ['value'=>'DE','label'=>'Germany'],
                        ['value'=>'FR','label'=>'France'],
                        ['value'=>'IR','label'=>'Iran'],
                    ],
                ],
                'sort'      => 4,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'geolocation'],
            [
                'label'     => ['en' => 'Geolocation'],
                'extra'     => ['type' => 'text', 'placeholder' => 'lat 60.17 | lon 24.94'],
                'sort'      => 5,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'nace'],
            [
                'label'     => ['en' => 'NACE code'],
                'extra'     => [
                    'type'      => 'select',
                    'searchable'=> true,
                    'choices'   => [
                        ['value'=>'A.1.1.4',  'label'=>'A.1.1.4 - Growing of sugar cane'],
                        ['value'=>'C.10.1.1', 'label'=>'C.10.1.1 - Processing and preserving of meat'],
                        ['value'=>'G.47.1.1', 'label'=>'G.47.1.1 - Retail sale in non-specialised stores'],
                    ],
                ],
                'sort'      => 6,
                'is_active' => true,
            ]
        );
    }


    /**
     * B1.Q4
     */


    protected function seedB1Q4(Disclosure $b1): void
    {
        $q4 = Question::updateOrCreate(
            ['disclosure_id' => $b1->id, 'key' => 'b1.q4'],
            [
                'number'   => 4,
                'type'     => 'radio-with-other',     // 👈 نوع اختصاصی
                'title' => [
                    'en' => "What is your company's legal form?",
                    'fi' => "Mikä on yrityksesi oikeudellinen muoto?",
                ],
                'rules'    => [

                    'type'   => 'radio-with-other',
                    'choice' => [ 'required' => true, 'in' => ['pll','sole','partnership','cooperative','other'] ],
                   // 'other'  => [ 'required_if:choice,other', 'min' => 3, 'max' => 200 ],
                ],
                'order'    => 4,
                'is_active'=> true,
                'help_official' => [
                    'en' => '4-Our ESG framework ensures that our operations adhere to the highest standards of environmental responsibility, social fairness, and corporate governance. We are committed to transparent reporting and continuous improvement across all ESG dimensions.' . rand(0,1000),
                    'fi' => '4-ESG-kehyksemme varmistaa, että toimintamme noudattaa korkeimpia ympäristövastuun, sosiaalisen oikeudenmukaisuuden ja yrityshallinnon standardeja. Olemme sitoutuneet avoimeen raportointiin ja jatkuvaan parantamiseen kaikilla ESG-osa-alueilla.' . rand(0,1000),
                ],
                'help_friendly' => [
                    'en' => '4-We take ESG seriously! From reducing our environmental footprint to supporting our communities and maintaining strong governance, we’re constantly improving and sharing our progress openly.' . rand(0,1000),
                    'fi' => '4-Otamme ESG:n vakavasti! Ympäristöjalanjälkemme pienentämisestä yhteisöjemme tukemiseen ja vahvan hallinnon ylläpitämiseen – parannamme jatkuvasti ja jaamme edistymisemme avoimesti.' . rand(0,1000),
                ]
              ]

        );

        $opts = [
            ['key'=>'pll',          'value'=>'pll',          'label'=>['en'=>'Private limited liability undertaking'], 'sort'=>1],
            ['key'=>'sole',         'value'=>'sole',         'label'=>['en'=>'Sole proprietorship'],                   'sort'=>2],
            ['key'=>'partnership',  'value'=>'partnership',  'label'=>['en'=>'Partnership'],                           'sort'=>3],
            ['key'=>'cooperative',  'value'=>'cooperative',  'label'=>['en'=>'Cooperative'],                           'sort'=>4],
            ['key'=>'other',        'value'=>'other',        'label'=>['en'=>'Other'],                                 'sort'=>5, 'extra'=>['shows_text'=>true,'placeholder'=>'Type your legal form...']],
        ];

        foreach ($opts as $o) {
            QuestionOption::updateOrCreate(
                ['question_id'=>$q4->id,'kind'=>'option','key'=>$o['key']],
                [
                    'value'     => $o['value'],
                    'label'     => $o['label'],
                    'sort'      => $o['sort'],
                    'is_active' => true,
                    'extra'     => $o['extra'] ?? null,
                ]
            );
        }
    }


}
