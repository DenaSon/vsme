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

        // 4) Disclosures refs
        $b1 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id'        => $basic->id,
            'code'             => 'b1'
        ])->first();

        $b2 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id'        => $basic->id,
            'code'             => 'b2',
        ])->first();

        $b3 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id'        => $basic->id,
            'code'             => 'b3',
        ])->first();


        $b4 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id'        => $basic->id,
            'code'             => 'b4',
        ])->first();

        $b5 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id'        => $basic->id,
            'code'             => 'b5',

        ])->first();

        $b6 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id'        => $basic->id,
            'code'             => 'b6',
        ])->first();

        $b7 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id'        => $basic->id,
            'code'             => 'b7',
        ])->first();





        // ---------- B1 (8 questions)
        $this->seedB1Q1($b1);
        $this->seedB1Q2($b1);
        $this->seedB1Q3($b1);
        $this->seedB1Q4($b1);
        $this->seedB1Q5($b1);
        $this->seedB1Q6($b1);
        $this->seedB1Q7($b1);
        $this->seedB1Q8($b1);


        // ---------- B2 (4 questions: practices, policies, initiatives, targets)
        $this->seedB2Q1_Practices($b2);
        $this->seedB2Q2_Policies($b2);
        $this->seedB2Q3_FutureInitiatives($b2);
        $this->seedB2Q4_Targets($b2);

        // ---------- B3

        $this->seedB3Q1_Energy($b3);

        $this->seedB3Q2_Boundary($b3);

        $this->seedB3Q3_Scope1($b3);

        $this->seedB3Q4_Scope2Location($b3);


        //B4 - one questions

        $this->seedB4Q1($b4);

        //B5
        $this->seedB5Q1_Gate($b5);
        $this->seedB5Q2($b5);
        $this->seedB5Q3_LandUseTotals($b5);

        //B6 -> 2 Questions
        $this->seedB6Q1($b6);
        $this->seedB6Q2_SignificantWaterUse($b6);

        //B7
        $this->seedB7Q1_CircularPrinciples($b7);
        $this->seedB7Q2_WasteGeneration($b7);




    }

    /* ====================== B1 ====================== */

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
                'rules' => [
                    'choice' => ['required'],
                ],
                'order'=>1,
                'is_active'=>true,
                'help_official' => [
                    'en' => '1-Our ESG framework ensures that our operations adhere to the highest standards of environmental responsibility, social fairness, and corporate governance.',
                    'fi' => '1-ESG-kehyksemme varmistaa, että toimintamme noudattaa korkeimpia standardeja...'
                ],
                'help_friendly' => [
                    'en' => 'We take ESG seriously!...',
                    'fi' => 'Suhtaudumme ESG:hen tosissamme!...'
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
                    'en' => '2-A basic report provides an overview...',
                    'fi' => '2-Perusraportti tarjoaa yleiskuvan...'
                ],
                'help_friendly' => [
                    'en' => 'Think of the basic report as a snapshot…',
                    'fi' => 'Ajattele perusraporttia pikakuvana…'
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
                    'en' => '3-Our ESG framework ensures...',
                    'fi' => '3-ESG-kehyksemme varmistaa...'
                ],
                'help_friendly' => [
                    'en' => '3-We take ESG seriously!...',
                    'fi' => '3-Suhtaudumme ESG:hen tosissamme!...'
                ],
                'rules'  => [
                    'required'   => true,
                    'array'      => true,
                    'min'        => 1,
                    'item_rules' => [
                        'name'            => ['required','string','max:200'],
                        'street_address'  => ['required','string','max:300'],
                        'city'            => ['required','string','max:120'],
                        'country'         => ['required'],
                        'geolocation'     => ['required','string','max:120'],
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
            ['label'=>['en' => 'Company name'], 'extra'=>['type'=>'text','placeholder'=>'ProVision'],'sort'=>1,'is_active'=>true]
        );
        QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'street_address'],
            ['label'=>['en' => 'Street Address'], 'extra'=>['type'=>'text','placeholder'=>'Mäkelänkatu 25 B 13'],'sort'=>2,'is_active'=>true]
        );
        QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'city'],
            ['label'=>['en' => 'City / Town'], 'extra'=>['type'=>'text','placeholder'=>'Helsinki'],'sort'=>3,'is_active'=>true]
        );
        QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'country'],
            [
                'label'=>['en' => 'Country'],
                'extra'=>['type'=>'select','choices'=>[
                    ['value'=>'FI','label'=>'Finland'],
                    ['value'=>'SE','label'=>'Sweden'],
                    ['value'=>'DE','label'=>'Germany'],
                    ['value'=>'FR','label'=>'France'],
                    ['value'=>'IR','label'=>'Iran'],
                ]],
                'sort'=>4,'is_active'=>true
            ]
        );
        QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'geolocation'],
            ['label'=>['en' => 'Geolocation'], 'extra'=>['type'=>'text','placeholder'=>'lat 60.17 | lon 24.94'],'sort'=>5,'is_active'=>true]
        );
        QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'nace'],
            [
                'label'=>['en' => 'NACE code'],
                'extra'=>[
                    'type'=>'select','searchable'=>true,
                    'choices'=>[
                        ['value'=>'A.1.1.4','label'=>'A.1.1.4 - Growing of sugar cane'],
                        ['value'=>'C.10.1.1','label'=>'C.10.1.1 - Processing and preserving of meat'],
                        ['value'=>'G.47.1.1','label'=>'G.47.1.1 - Retail sale in non-specialised stores'],
                    ],
                ],
                'sort'=>6,'is_active'=>true
            ]
        );
    }

    protected function seedB1Q4(Disclosure $b1): void
    {
        $q4 = Question::updateOrCreate(
            ['disclosure_id' => $b1->id, 'key' => 'b1.q4'],
            [
                'number'   => 4,
                'type'     => 'radio-with-other',
                'title' => [
                    'en' => "What is your company's legal form?",
                    'fi' => "Mikä on yrityksesi oikeudellinen muoto?",
                ],
                'rules'    => [
                    'type'   => 'radio-with-other',
                    'choice' => [ 'required' => true, 'in' => ['pll','sole','partnership','cooperative','other'] ],
                ],
                'order'    => 4,
                'is_active'=> true,
                'help_official' => [
                    'en' => '4-Our ESG framework ensures...',
                    'fi' => '4-ESG-kehyksemme varmistaa...'
                ],
                'help_friendly' => [
                    'en' => '4-We take ESG seriously!...',
                    'fi' => '4-Otamme ESG:n vakavasti!...'
                ]
            ]
        );

        $opts = [
            ['key'=>'pll','value'=>'pll','label'=>['en'=>'Private limited liability undertaking'],'sort'=>1],
            ['key'=>'sole','value'=>'sole','label'=>['en'=>'Sole proprietorship'],'sort'=>2],
            ['key'=>'partnership','value'=>'partnership','label'=>['en'=>'Partnership'],'sort'=>3],
            ['key'=>'cooperative','value'=>'cooperative','label'=>['en'=>'Cooperative'],'sort'=>4],
            ['key'=>'other','value'=>'other','label'=>['en'=>'Other'],'sort'=>5,'extra'=>['shows_text'=>true,'placeholder'=>'Type your legal form...']],
        ];

        foreach ($opts as $o) {
            QuestionOption::updateOrCreate(
                ['question_id'=>$q4->id,'kind'=>'option','key'=>$o['key']],
                ['value'=>$o['value'],'label'=>$o['label'],'sort'=>$o['sort'],'is_active'=>true,'extra'=>$o['extra'] ?? null]
            );
        }
    }

    protected function seedB1Q5(Disclosure $b1): void
    {
        $q5 = Question::updateOrCreate(
            ['disclosure_id' => $b1->id, 'key' => 'b1.q5'],
            [
                'number' => 5,
                'type'   => 'radio-cards',
                'title'  => [
                    'en' => 'Does your company have sustainability certificates or labels?',
                    'fi' => 'Onko yritykselläsi kestävyystodistuksia tai -merkintöjä?',
                ],
                'rules'  => [
                    'choice' => ['required', 'in:yes,no'],
                    'desc'   => ['nullable', 'string', 'max:500', 'required_if:choice,yes'],
                ],
                'order'         => 5,
                'is_active'     => true,
                'help_official' => [
                    'en' => 'If yes, which ones? Who issues the certificates or labels?',
                    'fi' => 'Jos kyllä, mitkä? Kuka myöntää sertifikaatit tai merkinnät?',
                ],
                'help_friendly' => [
                    'en' => 'Upload scans or PDFs of the certificates...',
                    'fi' => 'Lataa skannaukset tai PDF-tiedostot...'
                ],
                'meta' => ['ui' => ['show_evidence_badge' => true]],
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q5->id, 'kind' => 'option', 'key' => 'yes'],
            [
                'value'=>'yes','label'=>['en'=>'Yes','fi'=>'Kyllä'],'sort'=>1,'is_active'=>true,
                'extra'=>[
                    'requires_evidence'=>true,
                    'uploader'=>[
                        'enabled'=>true,
                        'label'=>['en'=>'Attach any related files as evidence.','fi'=>'Liitä asiaan liittyvät tiedostot todisteeksi.'],
                        'max_files'=>5,'max_size_mb'=>10,
                        'mimes'=>['pdf','jpg','jpeg','png','doc','docx','xls','xlsx'],
                        'path_pattern'=>'reports/{report_id}/b1.q5',
                    ],
                    'fields'=>[[
                        'key'=>'desc','type'=>'textarea',
                        'label'=>['en'=>'Which certificates/labels? Issuer & validity','fi'=>'Mitkä sertifikaatit/merkinnät?'],
                        'placeholder'=>['en'=>'ISO 14001 (SGS, valid till 2026-05)','fi'=>'esim. ISO 14001 ...'],
                        'max'=>500,
                    ]],
                ],
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q5->id, 'kind' => 'option', 'key' => 'no'],
            ['value'=>'no','label'=>['en'=>'No','fi'=>'Ei'],'sort'=>2,'is_active'=>true,'extra'=>['requires_evidence'=>false,'fields'=>[]]]
        );
    }


    protected function seedB1Q6(Disclosure $b1): void
    {
        $q = Question::updateOrCreate(
            ['disclosure_id' => $b1->id, 'key' => 'b1.q6'],
            [
                'number' => 6,
                'type'   => 'multi-input', // تک‌فیلدی؛ برای suffix واحد پول
                'title'  => [
                    'en' => 'Size of the balance sheet (in Euro)',
                    'fi' => 'Taseen loppusumma (euroissa)',
                ],
                'help_official' => [
                    'en' => 'Enter the balance sheet total (total assets) for the reporting period in EUR.',
                    'fi' => 'Syötä tilikauden taseen loppusumma euroina (EUR).',
                ],
                'help_friendly' => [
                    'en' => 'Numbers only; decimals allowed. No thousand separators.',
                    'fi' => 'Vain numerot; desimaalit sallittu.',
                ],
                'rules' => [
                    'array'      => true,
                    'required'   => true,
                    'item_rules' => [
                        'balance_sheet_eur' => ['required','numeric','min:0'],
                    ],
                ],
                'order'     => 6,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'balance_sheet_eur'],
            [
                'label' => ['en' => 'Balance sheet total', 'fi' => 'Taseen loppusumma'],
                'extra' => [
                    'type'        => 'number',
                    'step'        => '0.01',
                    'min'         => 0,
                    'suffix'      => '€',           // یا اگر ترجیح می‌دهی: 'EUR'
                    'placeholder' => '1000000',
                ],
                'sort'      => 1,
                'is_active' => true,
            ]
        );
    }


    protected function seedB1Q7(Disclosure $b1): void
    {
        $q = Question::updateOrCreate(
            ['disclosure_id' => $b1->id, 'key' => 'b1.q7'],
            [
                'number' => 7,
                'type'   => 'multi-input', // تک‌فیلدی برای نمایش واحد پول
                'title'  => [
                    'en' => 'Turnover (in Euro)',
                    'fi' => 'Liikevaihto (euroissa)',
                ],
                'help_official' => [
                    'en' => 'Enter total turnover for the reporting period in EUR.',
                    'fi' => 'Syötä tilikauden liikevaihto euroina (EUR).',
                ],
                'help_friendly' => [
                    'en' => 'Numbers only; decimals allowed. No thousand separators.',
                    'fi' => 'Vain numerot; desimaalit sallittu.',
                ],
                'rules' => [
                    'array'      => true,
                    'required'   => true,
                    'item_rules' => [
                        'turnover_eur' => ['required','numeric','min:0'],
                    ],
                ],
                'order'     => 7,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'turnover_eur'],
            [
                'label' => ['en' => 'Turnover', 'fi' => 'Liikevaihto'],
                'extra' => [
                    'type'        => 'number',
                    'step'        => '0.01',
                    'min'         => 0,
                    'suffix'      => '€', // یا 'EUR' اگر می‌خواهی نوشتاری باشد
                    'placeholder' => '2500000',
                ],
                'sort'      => 1,
                'is_active' => true,
            ]
        );
    }

    protected function seedB1Q8(Disclosure $b1): void
    {
        $q = Question::updateOrCreate(
            ['disclosure_id' => $b1->id, 'key' => 'b1.q8'],
            [
                'number' => 8,
                'type'   => 'multi-input', // تک‌فیلدی
                'title'  => [
                    'en' => 'Number of employees in headcount or full-time equivalents',
                    'fi' => 'Henkilöstömäärä (henkilömäärä tai HTV/FTE)',
                ],
                'help_official' => [
                    'en' => 'Enter either headcount (integer) or FTE (decimals allowed).',
                    'fi' => 'Syötä joko henkilömäärä (kokonaisluku) tai HTV/FTE (desimaalit sallittu).',
                ],
                'help_friendly' => [
                    'en' => 'Example: 42 (headcount) or 38.5 (FTE).',
                    'fi' => 'Esim. 42 (henkilömäärä) tai 38.5 (HTV).',
                ],
                'rules' => [
                    'array'      => true,
                    'required'   => true,
                    'item_rules' => [
                        'employees' => ['required','numeric','min:0'],
                    ],
                ],
                'order'     => 8,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'employees'],
            [
                'label' => ['en' => 'Employees (headcount/FTE)', 'fi' => 'Henkilöstö (hlö/HTV)'],
                'extra' => [
                    'type'        => 'number',
                    'step'        => '0.1',        // برای FTE
                    'min'         => 0,
                    'placeholder' => '42 or 38.5',
                ],
                'sort'      => 1,
                'is_active' => true,
            ]
        );
    }



    /* ====================== B2 ====================== */

    // (a) Practices
    protected function seedB2Q1_Practices(Disclosure $b2): void
    {
        $q = Question::updateOrCreate(
            ['disclosure_id' => $b2->id, 'key' => 'b2.q1'],
            [
                'number' => 1,
                'type'   => 'radio-cards',
                'title'  => [
                    'en' => 'Does your company have any practices for transitioning towards a more sustainable economy?',
                    'fi' => 'Onko yrityksellänne käytäntöjä siirtymiseksi kestävämpään talouteen?',
                ],
                'help_official' => [
                    'en' => 'If yes please specify.',
                    'fi' => 'Jos kyllä, täsmennä.',
                ],
                'help_friendly' => [
                    'en' => 'Examples: circular economy initiatives, eco-design, sustainable procurement, etc.',
                    'fi' => 'Esim. kiertotalousaloitteet, ekosuunnittelu, kestävä hankinta jne.',
                ],
                'rules' => [
                    'choice' => ['required', 'in:yes,no'],
                    'desc'   => ['nullable', 'string', 'max:500', 'required_if:choice,yes'],
                ],
                'order'     => 1,
                'is_active' => true,
                'meta'      => ['ui' => ['show_evidence_badge' => true]],
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'yes'],
            [
                'value' => 'yes', 'label' => ['en' => 'Yes', 'fi' => 'Kyllä'], 'sort' => 1, 'is_active' => true,
                'extra' => [
                    'requires_evidence' => true,
                    'uploader' => [
                        'enabled' => true,
                        'label'   => ['en' => 'Attach any related files as evidence.','fi' => 'Liitä asiaan liittyvät tiedostot todisteeksi.'],
                        'max_files' => 5, 'max_size_mb' => 10,
                        'mimes' => ['pdf','jpg','jpeg','png','doc','docx','xls','xlsx'],
                        'path_pattern'=> 'reports/{report_id}/b2.q1',
                    ],
                    'fields' => [[
                        'key'=>'desc','type'=>'textarea',
                        'label'=>['en'=>'Describe your practices','fi'=>'Kuvaile käytäntöjä'],
                        'placeholder'=>['en'=>'circular design program, supplier ESG screening...','fi'=>'esim. kiertotalouden...'],
                        'max'=>500,
                    ]],
                ],
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            ['value'=>'no','label'=>['en'=>'No','fi'=>'Ei'],'sort'=>2,'is_active'=>true,'extra'=>['requires_evidence'=>false,'fields'=>[]]]
        );
    }

    // (b) Policies + publicly available
    protected function seedB2Q2_Policies(Disclosure $b2): void
    {
        $q = Question::updateOrCreate(
            ['disclosure_id' => $b2->id, 'key' => 'b2.q2'],
            [
                'number' => 2,
                'type'   => 'radio-cards',
                'title'  => [
                    'en' => 'Does your company have any policies on sustainability issues?',
                    'fi' => 'Onko yrityksellänne kestävyyspolitiikkoja?',
                ],
                'help_official' => [
                    'en' => 'State whether policies exist and whether they are publicly available.',
                    'fi' => 'Kerro, onko politiikkoja ja ovatko ne julkisesti saatavilla.',
                ],
                'rules' => [
                    'choice'             => ['required','in:yes,no'],
                    'desc'               => ['nullable','string','max:500','required_if:choice,yes'],
                    'publicly_available' => ['nullable','in:yes,no','required_if:choice,yes'],
                ],
                'order'     => 2,
                'is_active' => true,
                'meta'      => ['ui' => ['show_evidence_badge' => true]],
            ]
        );

        // YES
        QuestionOption::updateOrCreate(
            ['question_id'=>$q->id,'kind'=>'option','key'=>'yes'],
            [
                'value'=>'yes','label'=>['en'=>'Yes','fi'=>'Kyllä'],'sort'=>1,'is_active'=>true,
                'extra'=>[
                    'requires_evidence'=>true,
                    'uploader'=>[
                        'enabled'=>true,
                        'label'=>['en'=>'Attach any related files as evidence.','fi'=>'Liitä asiaan liittyvät tiedostot.'],
                        'max_files'=>5,'max_size_mb'=>10,
                        'mimes'=>['pdf','jpg','jpeg','png','doc','docx','xls','xlsx'],
                        'path_pattern'=>'reports/{report_id}/b2.q2',
                    ],
                    'fields'=>[
                        [
                            'key'=>'desc','type'=>'textarea',
                            'label'=>['en'=>'Describe the policies','fi'=>'Kuvaile politiikat'],
                            'placeholder'=>['en'=>'Environmental, social or governance policies…','fi'=>'Ympäristö-, sosiaali- tai hallintopolitiikat…'],
                            'max'=>500,
                        ],
                        [
                            'key'=>'publicly_available','type'=>'radio',
                            'label'=>['en'=>'Are the policies publicly available?','fi'=>'Ovatko politiikat julkisia?'],
                            'choices'=>[
                                ['value'=>'yes','label'=>'Yes'],
                                ['value'=>'no','label'=>'No'],
                            ],
                        ],
                    ],
                ],
            ]
        );

        // NO
        QuestionOption::updateOrCreate(
            ['question_id'=>$q->id,'kind'=>'option','key'=>'no'],
            ['value'=>'no','label'=>['en'=>'No','fi'=>'Ei'],'sort'=>2,'is_active'=>true,'extra'=>['requires_evidence'=>false,'fields'=>[]]]
        );
    }

    // (c) Future initiatives
    protected function seedB2Q3_FutureInitiatives(Disclosure $b2): void
    {
        $q = Question::updateOrCreate(
            ['disclosure_id' => $b2->id, 'key' => 'b2.q3'],
            [
                'number' => 3,
                'type'   => 'radio-cards',
                'title'  => [
                    'en' => 'Does your company have any future initiatives or forward-looking plans on sustainability issues?',
                    'fi' => 'Onko yrityksellänne tulevia aloitteita tai suunnitelmia kestävyyskysymyksissä?',
                ],
                'help_official' => ['en'=>'Please specify.','fi'=>'Täsmennä.'],
                'help_friendly' => [
                    'en'=>'Examples: upcoming targets, roadmaps, pilot projects, transition plans, etc.',
                    'fi'=>'Esim. tulevat tavoitteet, tiekartat, pilottihankkeet, siirtymäsuunnitelmat jne.',
                ],
                'rules' => [
                    'choice' => ['required','in:yes,no'],
                    'desc'   => ['nullable','string','max:500','required_if:choice,yes'],
                ],
                'order' => 3,
                'is_active' => true,
                'meta' => ['ui'=>['show_evidence_badge'=>true]],
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id'=>$q->id,'kind'=>'option','key'=>'yes'],
            [
                'value'=>'yes','label'=>['en'=>'Yes','fi'=>'Kyllä'],'sort'=>1,'is_active'=>true,
                'extra'=>[
                    'requires_evidence'=>true,
                    'uploader'=>[
                        'enabled'=>true,
                        'label'=>['en'=>'Attach any related files as evidence.','fi'=>'Liitä asiaan liittyvät tiedostot todisteeksi.'],
                        'max_files'=>5,'max_size_mb'=>10,
                        'mimes'=>['pdf','jpg','jpeg','png','doc','docx','xls','xlsx'],
                        'path_pattern'=>'reports/{report_id}/b2.q3',
                    ],
                    'fields'=>[[
                        'key'=>'desc','type'=>'textarea',
                        'label'=>['en'=>'Describe the planned initiatives / timelines','fi'=>'Kuvaile suunnitellut aloitteet / aikataulut'],
                        'placeholder'=>['en'=>'net-zero roadmap by 2030, product redesign pilot…','fi'=>'hiilineutraali tiekartta 2030, ...'],
                        'max'=>500,
                    ]],
                ],
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id'=>$q->id,'kind'=>'option','key'=>'no'],
            ['value'=>'no','label'=>['en'=>'No','fi'=>'Ei'],'sort'=>2,'is_active'=>true,'extra'=>['requires_evidence'=>false,'fields'=>[]]]
        );
    }

    // (d) Targets
    protected function seedB2Q4_Targets(Disclosure $b2): void
    {
        $q = Question::updateOrCreate(
            ['disclosure_id' => $b2->id, 'key' => 'b2.q4'],
            [
                'number' => 4,
                'type'   => 'radio-cards',
                'title'  => [
                    'en' => 'Has your company set any targets to monitor the implementation of the policies and the progress achieved?',
                    'fi' => 'Onko yrityksenne asettanut tavoitteita politiikkojen toimeenpanon ja edistymisen seuraamiseksi?',
                ],
                'help_official' => ['en'=>'Please specify.','fi'=>'Täsmennä.'],
                'help_friendly' => [
                    'en'=>'Examples: KPI targets, interim milestones, validation method, review frequency.',
                    'fi'=>'Esim. KPI-tavoitteet, välitavoitteet, validointitapa, tarkistuksen tiheys.',
                ],
                'rules' => [
                    'choice' => ['required','in:yes,no'],
                    'desc'   => ['nullable','string','max:500','required_if:choice,yes'],
                ],
                'order' => 4,
                'is_active' => true,
                'meta' => ['ui'=>['show_evidence_badge'=>true]],
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id'=>$q->id,'kind'=>'option','key'=>'yes'],
            [
                'value'=>'yes','label'=>['en'=>'Yes','fi'=>'Kyllä'],'sort'=>1,'is_active'=>true,
                'extra'=>[
                    'requires_evidence'=>true,
                    'uploader'=>[
                        'enabled'=>true,
                        'label'=>['en'=>'Attach any related files as evidence.','fi'=>'Liitä asiaan liittyvät tiedostot todisteeksi.'],
                        'max_files'=>5,'max_size_mb'=>10,
                        'mimes'=>['pdf','jpg','jpeg','png','doc','docx','xls','xlsx'],
                        'path_pattern'=>'reports/{report_id}/b2.q4',
                    ],
                    'fields'=>[[
                        'key'=>'desc','type'=>'textarea',
                        'label'=>['en'=>'Describe the targets / metrics and review cadence','fi'=>'Kuvaa tavoitteet / mittarit ja tarkistussykli'],
                        'placeholder'=>['en'=>'30% emissions cut by 2027, quarterly KPI review…','fi'=>'30 % päästövähennys 2027, KPI-katsaus neljännesvuosittain…'],
                        'max'=>500,
                    ]],
                ],
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id'=>$q->id,'kind'=>'option','key'=>'no'],
            ['value'=>'no','label'=>['en'=>'No','fi'=>'Ei'],'sort'=>2,'is_active'=>true,'extra'=>['requires_evidence'=>false,'fields'=>[]]]
        );
    }

    /* ====================== B3 ====================== */

    // Numeric energy question moved here
    protected function seedB3Q1_Energy(Disclosure $b3): void
    {
        if (!$b3) return;

        $q = Question::updateOrCreate(
            ['disclosure_id' => $b3->id, 'key' => 'b3.q1'],
            [
                'number' => 1,
                'type'   => 'multi-input',
                'title'  => [
                    'en' => 'Annual energy consumption (electricity & fuels, MWh)',
                    'fi' => 'Vuosittainen energiankulutus (sähkö & polttoaineet, MWh)',
                ],
                'help_official' => [
                    'en' => 'Provide annual consumption values. Use MWh for both electricity and fuels.',
                    'fi' => 'Anna vuosittaiset kulutusarvot. Käytä yksikkönä MWh.',
                ],
                'help_friendly' => [
                    'en' => 'Estimates are okay for now. You can refine later.',
                    'fi' => 'Arviot kelpaavat tässä vaiheessa. Voit tarkentaa myöhemmin.',
                ],
                'rules' => [
                    'array'      => true,
                    'required'   => true,
                    'item_rules' => [
                        'electricity_mwh' => ['required','numeric','min:0'],
                        'fuel_mwh'        => ['required','numeric','min:0'],
                    ],
                ],
                'order'     => 1,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'electricity_mwh'],
            [
                'label' => ['en' => 'Electricity (MWh)', 'fi' => 'Sähkö (MWh)'],
                'extra' => ['type'=>'number','placeholder'=>'100','step'=>'any','min'=>0,'suffix'=>'MWh'],
                'sort'  => 1,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'fuel_mwh'],
            [
                'label' => ['en' => 'Fuels (MWh)', 'fi' => 'Polttoaineet (MWh)'],
                'extra' => ['type'=>'number','placeholder'=>'100','step'=>'any','min'=>0,'suffix'=>'MWh'],
                'sort'  => 2,
                'is_active' => true,
            ]
        );
    }


    // B3.Q2 — Boundary approach (radio-cards)
    protected function seedB3Q2_Boundary(Disclosure $b3): void
    {
        $q = Question::updateOrCreate(
            ['disclosure_id'=>$b3->id,'key'=>'b3.q2'],
            [
                'number'=>2,
                'type'=>'radio-cards',
                'title'=>[
                    'en'=>'Which consolidation/boundary approach do you use for GHG accounting?',
                    'fi'=>'Mitä rajaustapaa käytätte GHG-laskennassa?',
                ],
                'help_official'=>[
                    'en'=>'Choose Equity share or Control approach (Operational/Financial) as defined by the GHG Protocol.',
                    'fi'=>'Valitse Equity share tai Control (Operational/Financial) GHG-protokollan mukaan.',
                ],
                'rules'=>[
                    'choice'=>['required','in:equity_share,control_operational,control_financial'],
                    'desc'  =>['nullable','string','max:500'],
                ],
                'order'=>2,
                'is_active'=>true,
            ]
        );

        $opts = [
            ['key'=>'equity','value'=>'equity_share','label'=>['en'=>'Equity share approach'], 'hint'=>'Account emissions by equity share.'],
            ['key'=>'ctrl_op','value'=>'control_operational','label'=>['en'=>'Control approach — Operational control'], 'hint'=>'Full authority to implement operating policies.'],
            ['key'=>'ctrl_fin','value'=>'control_financial','label'=>['en'=>'Control approach — Financial control'],   'hint'=>'Ability to direct financial & operating policies.'],
        ];

        foreach ($opts as $i => $o) {
            QuestionOption::updateOrCreate(
                ['question_id'=>$q->id,'kind'=>'option','key'=>$o['key']],
                [
                    'value'=>$o['value'],
                    'label'=>$o['label'],
                    'sort'=>$i+1,
                    'is_active'=>true,
                    'extra'=>[
                        'hint'=>$o['hint'],
                        // textarea اختیاری (رادیوکارت شما به طور خودکار اولین textarea را به value.desc بایند می‌کند)
                        'fields'=>[[
                            'key'=>'desc','type'=>'textarea',
                            'label'=>['en'=>'Notes (optional)'],
                            'placeholder'=>['en'=>'Any clarification about boundaries...'],
                            'max'=>500,
                        ]],
                    ],
                ]
            );
        }
    }



    protected function seedB3Q3_Scope1(Disclosure $b3): void
    {
        if (!$b3) return;

        $q = Question::updateOrCreate(
            ['disclosure_id'=>$b3->id,'key'=>'b3.q3'],
            [
                'number'=>3,
                'type'=>'multi-input',
                'title'=>['en'=>'Scope 1 GHG emissions (tCO2e)'],
                'help_official'=>['en'=>'Report annual gross Scope 1 emissions. Unit: tCO2e.'],
                'rules'=>['array'=>true,'required'=>true,'item_rules'=>[
                    'scope1_tco2e'=>['required','numeric','min:0'],
                ]],
                'order'=>3,
                'is_active'=>true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id'=>$q->id,'kind'=>'field','key'=>'scope1_tco2e'],
            [
                'label'=>['en'=>'Scope 1'],
                'extra'=>['type'=>'number','step'=>'any','min'=>0,'suffix'=>'tCO2e','placeholder'=>'150.0'],
                'sort'=>1,'is_active'=>true,
            ]
        );
    }



    // B3.Q4 — Scope 2 (location-based) GHG emissions, tCO2e
    protected function seedB3Q4_Scope2Location(Disclosure $b3): void
    {
        if (!$b3) return;

        $q = Question::updateOrCreate(
            ['disclosure_id' => $b3->id, 'key' => 'b3.q4'],
            [
                'number' => 4,
                'type'   => 'multi-input',
                'title'  => [
                    'en' => 'Scope 2 GHG emissions (location-based, tCO2e)',
                    'fi' => 'Scope 2 -päästöt (location-based, tCO2e)',
                ],
                'help_official' => [
                    'en' => 'Report annual gross location-based Scope 2 emissions (purchased electricity, steam, heating & cooling). Unit: tCO2e.',
                    'fi' => 'Raportoi location-based Scope 2 -päästöt. Yksikkö: tCO2e.',
                ],
                'help_friendly' => [
                    'en' => 'Estimates are fine. You can refine later.',
                    'fi' => 'Arviot käyvät; voit tarkentaa myöhemmin.',
                ],
                'rules' => [
                    'array'      => true,
                    'required'   => true,
                    'item_rules' => [
                        'scope2_loc_tco2e' => ['required','numeric','min:0'],

                    ],
                ],
                'order'     => 4,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'scope2_loc_tco2e'],
            [
                'label' => ['en' => 'Scope 2 (location-based)'],
                'extra' => ['type'=>'number','step'=>'any','min'=>0,'suffix'=>'tCO2e','placeholder'=>'250.0'],
                'sort'  => 1,
                'is_active' => true,
            ]
        );


    }


    protected function seedB4Q1(Disclosure $b4): void
    {
        if (!$b4) return;

        $q = Question::updateOrCreate(
            ['disclosure_id' => $b4->id, 'key' => 'b4.q1'],
            [
                'number' => 1,
                'type'   => 'radio-cards',
                'title'  => [
                    'en' => 'Is your company required to report its emissions or pollutants by law or under an Environmental Management System?',
                    'fi' => 'Onko yrityksenne velvollinen raportoimaan päästöistään lain tai ympäristöjärjestelmän mukaan?',
                ],
                'help_official' => [
                    'en' => 'If yes, disclose the pollutants and amounts or provide a link/document if already publicly available.',
                    'fi' => 'Jos kyllä, ilmoita päästöt ja määrät tai anna linkki/asiakirja, jos tiedot ovat jo julkisia.',
                ],
                'help_friendly' => [
                    'en' => 'If you already submit these to authorities or EMS, you can attach the same file here.',
                    'fi' => 'Jos raportoit nämä viranomaisille tai EMS:lle, voit liittää saman tiedoston tänne.',
                ],
                'rules' => [
                    'choice' => ['required', 'in:yes,no'],
                    'desc'   => ['nullable', 'string', 'max:1000', 'required_if:choice,yes'],
                ],
                'order'     => 1,
                'is_active' => true,
                'meta'      => ['ui' => ['show_evidence_badge' => true]],
            ]
        );


        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'yes'],
            [
                'value'     => 'yes',
                'label'     => ['en' => 'Yes', 'fi' => 'Kyllä'],
                'sort'      => 1,
                'is_active' => true,
                'extra'     => [
                    'requires_evidence' => true,
                    'uploader' => [
                        'enabled'     => true,
                        'label'       => [
                            'en' => 'Attach any related files as evidence.',
                            'fi' => 'Liitä asiaan liittyvät tiedostot todisteeksi.',
                        ],
                        'max_files'   => 5,
                        'max_size_mb' => 10,
                        'mimes'       => ['pdf','jpg','jpeg','png','doc','docx','xls','xlsx'],
                        'path_pattern'=> 'reports/{report_id}/b4.q1',
                    ],
                    'fields' => [[
                        'key'         => 'desc',
                        'type'        => 'textarea',
                        'label'       => ['en' => 'Description', 'fi' => 'Kuvaus'],
                        'placeholder' => ['en' => 'Describe scope, pollutants, link to public source...', 'fi' => 'Kuvaa laajuus, päästöt, linkki…'],
                        'max'         => 1000,
                    ]],
                ],
            ]
        );

        // NO
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            [
                'value'     => 'no',
                'label'     => ['en' => 'No', 'fi' => 'Ei'],
                'sort'      => 2,
                'is_active' => true,
                'extra'     => ['requires_evidence' => false, 'fields' => []],
            ]
        );
    }



    protected function seedB5Q1_Gate(Disclosure $b5): void
    {
        if (!$b5) return;

        $q = Question::updateOrCreate(
            ['disclosure_id' => $b5->id, 'key' => 'b5.q1'],
            [
                'number' => 1,
                'type'   => 'radio-cards',
                'title'  => [
                    'en' => 'Does your company own, lease or manage sites in or near biodiversity sensitive areas?',
                    'fi' => 'Omistaako, vuokraako tai hallinnoiko yrityksenne kohteita luonnon monimuotoisuuden kannalta herkissä alueissa tai niiden läheisyydessä?',
                ],
                'help_official' => [
                    'en' => 'Answer Yes if any of your sites are inside or near a biodiversity sensitive area.',
                    'fi' => 'Vastaa Kyllä, jos yksikin kohteistanne sijaitsee tai on lähellä luonnon monimuotoisuuden kannalta herkkää aluetta.',
                ],
                'rules' => [
                    'choice' => ['required','in:yes,no'],
                ],
                'order'     => 1,
                'is_active' => true,
                // اختیاری: فلگ برای نمایش سوال بعدی فقط وقتی Yes است
                'meta' => [
                    'gate' => [
                        'if' => ['choice' => 'yes'],
                        'reveal_keys' => ['b5.q2'], // سوال جزئیات به‌ازای هر سایت
                    ],
                ],
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'yes'],
            ['value' => 'yes', 'label' => ['en' => 'Yes', 'fi' => 'Kyllä'], 'sort' => 1, 'is_active' => true]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            ['value' => 'no', 'label' => ['en' => 'No', 'fi' => 'Ei'], 'sort' => 2, 'is_active' => true]
        );
    }

    protected function seedB5Q2(Disclosure $b5): void
    {
        if (!$b5) return;

        $q = Question::updateOrCreate(
            ['disclosure_id' => $b5->id, 'key' => 'b5.q2'],
            [
                'number' => 2,
                'type'   => 'repeatable-group',
                'title'  => [
                    'en' => 'Sites in/near biodiversity sensitive areas — details per site',
                    'fi' => 'Luontokohteiden lähellä/sisällä olevat toimipaikat — tiedot per kohde',
                ],
                'help_official' => [
                    'en' => "Select each relevant site and provide: area (hectares), which biodiversity sensitive area is nearby, and whether the site is inside the area.",
                    'fi' => "Valitse kukin kohde ja anna: pinta-ala (hehtaareina), mikä luontokohde on lähellä sekä onko kohde alueen sisällä.",
                ],
                'help_friendly' => [
                    'en' => 'Tip: area unit is hectares (ha). If the site is not inside, choose No (meaning near).',
                    'fi' => 'Vinkki: pinta-ala hehtaareina (ha). Jos ei alueen sisällä, valitse Ei (eli lähellä).',
                ],
                'rules' => [
                    // فقط وقتی B5.Q1 = yes نمایش داده می‌شود (با visible_if). در غیراینصورت در UI اسکپ می‌شود.
                    'required'   => true,
                    'array'      => true,
                    'min'        => 1,
                    'item_rules' => [
                        'site_uid'              => ['required','string','max:50'],
                        'area_ha'               => ['required','numeric','min:0'],
                        'sensitive_area_name'   => ['required','string','max:200'],
                        'inside_sensitive_area' => ['required','in:yes,no'],
                    ],
                ],
                'order'     => 2,
                'is_active' => true,
                'meta'      => [
                    // برای گزارش: گزارش بتواند این ردیف‌ها را با role پیدا کند
                    'role' => 'biodiversity_sites',

                    // این پرسش فقط وقتی B5.Q1 = yes باشد نمایش داده شود
                    'visible_if' => [
                        ['when' => ['key' => 'b5.q1', 'eq' => 'yes']],
                    ],

                    // منبع انتخاب سایت‌ها از پاسخ B1.Q3 (لیست سایت‌ها)
                    'sources' => [
                        'site_list' => [
                            'from_question' => 'b1.q3',
                            'value_key'     => '_uid',
                            'label_tpl'     => '{{name}} — {{city}}, {{country}}',
                        ],
                    ],

                    // تنظیمات UI
                    'ui' => [
                        'compact' => true,
                    ],
                ],
            ]
        );

        // فیلد: انتخاب سایت (Select داینامیک از B1.Q3)
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'site_uid'],
            [
                'label' => ['en' => 'Site', 'fi' => 'Toimipaikka'],
                'extra' => [
                    'type'        => 'select',
                    'choices'     => [], // در Front از sources.site_list پر می‌شود
                    'placeholder' => 'Select a site…',
                    'hint'        => 'Pick from your sites defined in B1.Q3.',
                ],
                'sort'      => 1,
                'is_active' => true,
            ]
        );

        // فیلد: مساحت (هکتار)
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'area_ha'],
            [
                'label' => ['en' => 'Area (hectares)', 'fi' => 'Pinta-ala (hehtaaria)'],
                'extra' => [
                    'type'        => 'number',
                    'step'        => 'any',
                    'min'         => 0,
                    'suffix'      => 'ha',
                    'placeholder' => '12.5',
                ],
                'sort'      => 2,
                'is_active' => true,
            ]
        );

        // فیلد: نام/نوع ناحیه حساس
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'sensitive_area_name'],
            [
                'label' => ['en' => 'Which biodiversity sensitive area?', 'fi' => 'Mikä luontokohde?'],
                'extra' => [
                    'type'        => 'text',
                    'placeholder' => 'Natura 2000 – FI0200071',
                    'maxlength'   => 200,
                ],
                'sort'      => 3,
                'is_active' => true,
            ]
        );

        // فیلد: داخل/نزدیک ناحیه حساس
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'inside_sensitive_area'],
            [
                'label' => ['en' => 'Is the site inside the sensitive area?', 'fi' => 'Onko kohde alueen sisällä?'],
                'extra' => [
                    'type'    => 'select',
                    'choices' => [
                        ['value' => 'yes', 'label' => 'Yes'],
                        ['value' => 'no',  'label' => 'No (nearby)'],
                    ],
                ],
                'sort'      => 4,
                'is_active' => true,
            ]
        );
    }

    protected function seedB5Q3_LandUseTotals(Disclosure $b5): void
    {
        if (!$b5) return;

        $q = Question::updateOrCreate(
            ['disclosure_id' => $b5->id, 'key' => 'b5.q3'],
            [
                'number' => 3,
                'type'   => 'multi-input',
                'title'  => [
                    'en' => 'Land-use metrics (totals, optional)',
                ],
                'help_official' => [
                    'en' => 'Optional aggregate land-use metrics at undertaking level (in hectares).',
                ],
                'help_friendly' => [
                    'en' => 'If available, provide totals in hectares. Leave blank if not applicable.',
                ],
                'rules' => [
                    'array' => true, 'required' => false,
                    'item_rules' => [
                        'total_land_ha'       => ['nullable','numeric','min:0'],
                        'sealed_area_ha'      => ['nullable','numeric','min:0'],
                        'nature_on_site_ha'   => ['nullable','numeric','min:0'],
                        'nature_off_site_ha'  => ['nullable','numeric','min:0'],
                    ],
                ],
                'order'     => 3,
                'is_active' => true,
                'meta'      => [
                    'visible_if' => [
                        ['when' => ['key' => 'b5.q1', 'eq' => 'yes']],
                    ],
                ],
            ]
        );

        $fields = [
            ['key' => 'total_land_ha',      'label' => 'Total use of land (ha)'],
            ['key' => 'sealed_area_ha',     'label' => 'Total sealed area (ha)'],
            ['key' => 'nature_on_site_ha',  'label' => 'Total nature-oriented area on-site (ha)'],
            ['key' => 'nature_off_site_ha', 'label' => 'Total nature-oriented area off-site (ha)'],
        ];

        $sort = 1;
        foreach ($fields as $f) {
            QuestionOption::updateOrCreate(
                ['question_id' => $q->id, 'kind' => 'field', 'key' => $f['key']],
                [
                    'label' => ['en' => $f['label']],
                    'extra' => ['type'=>'number','step'=>'any','min'=>0,'suffix'=>'ha','placeholder'=>'100.0'],
                    'sort'  => $sort++,
                    'is_active' => true,
                ]
            );
        }
    }


    protected function seedB6Q1(Disclosure $b6): void
    {
        if (!$b6) return;

        $q = Question::updateOrCreate(
            ['disclosure_id' => $b6->id, 'key' => 'b6.q1'],
            [
                'number' => 1,
                'type'   => 'multi-input',
                'title'  => [
                    'en' => 'Total water withdrawal and high water-stress sites',
                    'fi' => 'Veden kokonaisotto ja korkean vesistressin alueet',
                ],
                'help_official' => [
                    'en' => "Disclose your total annual water withdrawal (litres). Also indicate if you have sites in areas of high water stress and, if yes, the amount of water withdrawn at those sites.",
                    'fi' => "Ilmoita vuotuinen veden kokonaisotto (litraa). Ilmoita myös, onko sinulla kohteita korkean vesistressin alueilla ja jos on, vedenotto näissä kohteissa.",
                ],
                'help_friendly' => [
                    'en' => "Estimates are okay for now. Use litres (L). If you select Yes for high water-stress areas, enter the litres withdrawn at those locations.",
                    'fi' => "Arviot ovat tällä hetkellä hyväksyttäviä. Käytä litroja (L). Jos valitset Kyllä korkean vesistressin alueille, syötä näiden sijaintien vedenotto litroina.",
                ],
                'rules' => [
                    'array'    => true,
                    'required' => true,
                    'item_rules' => [
                        'withdrawal_l' => ['required','numeric','min:0'],
                        'has_high_stress' => ['required','in:yes,no'],
                        'withdrawal_high_stress_l' => [
                            'nullable','numeric','min:0',
                            'required_if:answers.b6.q1.has_high_stress,yes'
                        ],
                    ],
                ],
                'order'     => 1,
                'is_active' => true,
                'meta'      => [
                    'ui' => [
                        'compact' => true,
                    ],
                ],
            ]
        );

        // Field 1: Total water withdrawal (L)
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'withdrawal_l'],
            [
                'label' => [
                    'en' => 'Total water withdrawal (L)',
                    'fi' => 'Veden kokonaisotto (L)',
                ],
                'extra' => [
                    'type'        => 'number',
                    'step'        => 'any',
                    'min'         => 0,
                    'suffix'      => 'L',
                    'placeholder' => 'esim. 150000',
                ],
                'sort'      => 1,
                'is_active' => true,
            ]
        );

        // Field 2: Are there locations in high water-stress areas?
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'has_high_stress'],
            [
                'label' => [
                    'en' => 'Are there locations in high water-stress areas?',
                    'fi' => 'Onko sijainteja korkean vesistressin alueilla?',
                ],
                'extra' => [
                    'type'        => 'select',
                    'placeholder' => '— Select —',
                    'choices'     => [
                        ['value' => 'yes', 'label' => 'Yes'],
                        ['value' => 'no',  'label' => 'No'],
                    ],
                ],
                'sort'      => 2,
                'is_active' => true,
            ]
        );


        // Field 3: Water withdrawal at high water-stress locations (L)
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'withdrawal_high_stress_l'],
            [
                'label' => [
                    'en' => 'Water withdrawal at high water-stress locations (L)',
                    'fi' => 'Vedenotto korkean vesistressin sijainneissa (L)',
                ],
                'extra' => [
                    'type'        => 'number',
                    'step'        => 'any',
                    'min'         => 0,
                    'suffix'      => 'L',
                    'placeholder' => 'esim. 25000',
                    'visible_if'  => ['key' => 'has_high_stress', 'eq' => 'yes'],
                ],
                'sort'      => 3,
                'is_active' => true,
            ]
        );
    }

    protected function seedB6Q2_SignificantWaterUse(Disclosure $b6): void
    {
        if (!$b6) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b6->id, 'key' => 'b6.q2'],
            [
                'number' => 2,
                'type'   => 'radio-cards',
                'title'  => [
                    'en' => 'Does your company use a significant amount of water?',
                    'fi' => 'Käyttääkö yrityksenne merkittävästi vettä?',
                ],
                'help_official' => [
                    'en' => 'If yes, disclose the amount of water discharged.',
                    'fi' => 'Jos kyllä, ilmoita yrityksen vedenpoiston määrä (purku).',
                ],
                'help_friendly' => [
                    'en' => 'Rule of thumb: answer “Yes” if water is material to your operations.',
                    'fi' => 'Nyrkkisääntö: vastaa ”Kyllä”, jos vesi on toiminnalle olennainen.',
                ],
                'rules' => [
                    'choice'           => ['required','in:yes,no'],

                    'discharge_liters' => ['nullable','numeric','min:0','required_if:choice,yes'],
                ],
                'order'     => 2,
                'is_active' => true,
                'meta'      => [
                    'ui' => ['compact' => true],
                ],
            ]
        );


        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'yes'],
            [
                'value'     => 'yes',
                'label'     => ['en' => 'Yes', 'fi' => 'Kyllä'],
                'sort'      => 1,
                'is_active' => true,
                'extra'     => [
                    'fields' => [[
                        'key'         => 'discharge_liters',
                        'type'        => 'number',
                        'label'       => ['en' => 'Water discharged (liters)', 'fi' => 'Poistettu vesi (litraa)'],
                        'placeholder' => ['en' => '150000', 'fi' => 'esim. 150000'],
                        'min'         => 0,
                        'step'        => 'any',
                        'suffix'      => 'L',
                    ]],
                ],
            ]
        );

        // NO → هیچ فیلدی ندارد
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            [
                'value'     => 'no',
                'label'     => ['en' => 'No', 'fi' => 'Ei'],
                'sort'      => 2,
                'is_active' => true,
                'extra'     => ['fields' => []],
            ]
        );
    }




    protected function seedB7Q1_CircularPrinciples(\App\Models\Disclosure $b7): void
    {
        if (!$b7) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b7->id, 'key' => 'b7.q1'],
            [
                'number' => 1,
                'type'   => 'radio-cards',
                'title'  => [
                    'en' => 'Does your company apply circular economy principles?',
                    'fi' => 'Soveltaako yrityksenne kiertotalouden periaatteita?',
                ],
                'help_official' => [
                    'en' => 'State whether circular economy principles are applied and briefly explain how, if yes.',
                    'fi' => 'Ilmoita, sovelletaanko kiertotalouden periaatteita ja kuvaile lyhyesti miten, jos vastaus on kyllä.',
                ],
                'help_friendly' => [
                    'en' => 'Examples: design for reuse, remanufacturing, recycled inputs, product-as-a-service.',
                    'fi' => 'Esimerkkejä: uudelleenkäyttöön suunnittelu, uudelleenvalmistus, kierrätysmateriaalit, palvelullistaminen.',
                ],
                'rules' => [
                    'choice' => ['required','in:yes,no'],
                    // desc فقط در حالت yes اجباری است:
                    'desc'   => ['nullable','string','max:500','required_if:choice,yes'],
                ],
                'order'     => 1,
                'is_active' => true,
                'meta'      => [
                    'ui' => ['compact' => true],
                ],
            ]
        );

        // گزینه YES با textarea توضیح
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'yes'],
            [
                'value'     => 'yes',
                'label'     => ['en' => 'Yes', 'fi' => 'Kyllä'],
                'sort'      => 1,
                'is_active' => true,
                'extra'     => [
                    // فیلد توضیح که در کامپوننت radio-cards شما به value.desc بایند می‌شود
                    'fields' => [[
                        'key'         => 'desc',
                        'type'        => 'textarea',
                        'label'       => ['en' => 'How do you apply these principles?', 'fi' => 'Miten sovellatte näitä periaatteita?'],
                        'placeholder' => ['en' => 'recycled inputs, take-back scheme, remanufacturing…', 'fi' => 'esim. kierrätysmateriaalit, takaisinottopalvelu, uudelleenvalmistus…'],
                        'max'         => 500,
                    ]],
                ],
            ]
        );

        // گزینه NO
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            [
                'value'     => 'no',
                'label'     => ['en' => 'No', 'fi' => 'Ei'],
                'sort'      => 2,
                'is_active' => true,
                'extra'     => [
                    'fields' => [], // ورودی اضافه ندارد
                ],
            ]
        );
    }

    protected function seedB7Q2_WasteGeneration(\App\Models\Disclosure $b7): void
    {
        if (!$b7) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b7->id, 'key' => 'b7.q2'],
            [
                'number' => 2,
                'type'   => 'multi-input',
                'title'  => [
                    'en' => 'Annual waste generation (kg)',
                    'fi' => 'Vuosittainen jätemäärä (kg)',
                ],
                'help_official' => [
                    'en' => 'Report total annual waste generated, split into non-hazardous and hazardous.',
                    'fi' => 'Ilmoita vuosittain syntyvä jätemäärä eriteltynä: ei-vaarallinen ja vaarallinen.',
                ],
                'help_friendly' => [
                    'en' => 'If unsure, use your best estimate. Units: kilograms (kg).',
                    'fi' => 'Jos et ole varma, anna paras arviosi. Yksikkö: kilogrammaa (kg).',
                ],
                'rules' => [
                    'array'      => true,
                    'required'   => true,
                    'item_rules' => [
                        'non_hazardous_waste_kg' => ['required','numeric','min:0'],
                        'hazardous_waste_kg'     => ['required','numeric','min:0'],
                    ],
                ],
                'order'     => 2,
                'is_active' => true,
            ]
        );

        // فیلد: پسماند غیرخطرناک (kg)
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'non_hazardous_waste_kg'],
            [
                'label' => [
                    'en' => 'Non-hazardous waste (kg)',
                    'fi' => 'Ei-vaarallinen jäte (kg)',
                ],
                'extra' => [
                    'type'        => 'number',
                    'step'        => 'any',
                    'min'         => 0,
                    'suffix'      => 'kg',
                    'placeholder' => '12000',
                ],
                'sort'      => 1,
                'is_active' => true,
            ]
        );

        // فیلد: پسماند خطرناک (kg)
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'hazardous_waste_kg'],
            [
                'label' => [
                    'en' => 'Hazardous waste (kg)',
                    'fi' => 'Vaarallinen jäte (kg)',
                ],
                'extra' => [
                    'type'        => 'number',
                    'step'        => 'any',
                    'min'         => 0,
                    'suffix'      => 'kg',
                    'placeholder' => '350',
                ],
                'sort'      => 2,
                'is_active' => true,
            ]
        );
    }


}
