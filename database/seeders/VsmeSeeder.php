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
                'is_active' => true,
                'published_at' => now(),
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
        $basicCodes = ['b1', 'b2', 'b3', 'b4', 'b5', 'b6', 'b7', 'b8', 'b9', 'b10', 'b11'];
        $compCodes = ['c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7', 'c8', 'c9'];

        foreach ($basicCodes as $i => $code) {
            Disclosure::query()->updateOrCreate(
                ['questionnaire_id' => $q->id, 'module_id' => $basic->id, 'code' => $code],
                ['order' => $i + 1, 'is_active' => true, 'is_applicable_by_default' => true]
            );
        }

        foreach ($basicCodes as $i => $code) {
            $titles = match ($code) {
                'b1' => ['en' => 'Basis for preparation', 'fi' => 'Laatimisperusta'],
                'b2' => ['en' => 'Practices, policies and future initiatives', 'fi' => 'Käytännöt, politiikat ja tulevat aloitteet'],
                'b3' => ['en' => 'Energy', 'fi' => 'Energia'],
                'b4' => ['en' => 'Emissions', 'fi' => 'Päästöt'],
                'b5' => ['en' => 'Biodiversity', 'fi' => 'Luonnon monimuotoisuus'],
                'b6' => ['en' => 'Water', 'fi' => 'Vesi'],
                'b7' => ['en' => 'Resource use, circular economy and waste', 'fi' => 'Resurssien käyttö, kiertotalous ja jätteet'],
                'b8' => ['en' => 'Workforce – General characteristics', 'fi' => 'Työvoima – Yleiset ominaisuudet'],
                'b9' => ['en' => 'Workforce – Health and safety', 'fi' => 'Työvoima – Terveys ja turvallisuus'],
                'b10'=> ['en' => 'Workforce – Remuneration, bargaining and training', 'fi' => 'Työvoima – Palkkaus, työehtosopimukset ja koulutus'],
                'b11'=> ['en' => 'Governance – Convictions and fines for corruption and bribery', 'fi' => 'Hallinto – Korruptio- ja lahjontatuomiot ja sakot'],
                default => ['en' => strtoupper($code), 'fi' => strtoupper($code)],
            };

            Disclosure::query()->updateOrCreate(
                ['questionnaire_id' => $q->id, 'module_id' => $basic->id, 'code' => $code],
                [
                    'order' => $i + 1,
                    'is_active' => true,
                    'is_applicable_by_default' => true,
                    'title' => $titles,
                ]
            );
        }

        // 4) Disclosures refs
        $b1 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id' => $basic->id,
            'code' => 'b1'
        ])->first();

        $b2 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id' => $basic->id,
            'code' => 'b2',
        ])->first();

        $b3 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id' => $basic->id,
            'code' => 'b3',
        ])->first();


        $b4 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id' => $basic->id,
            'code' => 'b4',
        ])->first();

        $b5 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id' => $basic->id,
            'code' => 'b5',

        ])->first();

        $b6 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id' => $basic->id,
            'code' => 'b6',
        ])->first();

        $b7 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id' => $basic->id,
            'code' => 'b7',
        ])->first();

        $b8 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id' => $basic->id,
            'code' => 'b8',
        ])->first();

        $b9 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id' => $basic->id,
            'code' => 'b9',
        ])->first();

        $b10 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id' => $basic->id,
            'code' => 'b10',
        ])->first();

        $b11 = Disclosure::where([
            'questionnaire_id' => $q->id,
            'module_id' => $basic->id,
            'code' => 'b11',
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

        $this->seedB1Q9($b1);


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

        //B5  --> 3 Questions with visible_if
        $this->seedB5Q1_Gate($b5);
        $this->seedB5Q2($b5);
        $this->seedB5Q3_LandUseTotals($b5);

        //B6 -> 2 Questions
        $this->seedB6Q1($b6);
        $this->seedB6Q2_SignificantWaterUse($b6);

        //B7 --> 5 Questions with visible_if
        $this->seedB7Q1_CircularPrinciples($b7);
        $this->seedB7Q2_WasteGeneration($b7);
        $this->seedB7Q3_RecyclingReuse($b7);

        $this->seedB7Q4_MaterialFlowsGate($b7);
        $this->seedB7Q5_MaterialsList($b7);

        //B8-Questions with dependency
        $this->seedB8Q1_Contracts($b8);
        $this->seedB8Q2_Gender($b8);

        $this->seedB8Q3_MultiCountryGate($b8);
        $this->seedB8Q4_EmployeesByCountry($b8);
        $this->seedB8Q5_EmployeesLeft($b8);

        //B9
        $this->seedB9Q1_WorkAccidents($b9);
        $this->seedB9Q2_Fatalities($b9);

        //B10
        $this->seedB10Q1_MinWageCompliance($b10);
        $this->seedB10Q2_GenderPayAvg($b10);
        $this->seedB10Q3_CollectiveBargaining($b10);
        $this->seedB10Q4_TrainingHoursByGender($b10);

        //B11 - GAME OVER for basic :)

        $this->seedB11Q1($b11);


    }

    /* ====================== B1 ====================== */

    protected function seedB1Q1(Disclosure $b1): void
    {
        $q1 = Question::updateOrCreate(
            ['disclosure_id' => $b1->id, 'key' => 'b1.q1'],
            [
                'number' => 1,
                'type' => 'radio-cards',
                'title' => [
                    'en' => 'Which option has the undertaking selected?',
                    'fi' => 'Minkä vaihtoehdon yritys on valinnut?'
                ],
                'rules' => [
                    'choice' => ['required'],
                ],
                'order' => 1,
                'is_active' => true,
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
            ['question_id' => $q1->id, 'kind' => 'option', 'key' => 'option_a'],
            ['value' => 'A', 'label' => ['en' => 'Basic Module'], 'sort' => 1, 'is_active' => true]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q1->id, 'kind' => 'option', 'key' => 'option_b'],
            ['value' => 'B', 'label' => ['en' => 'Basic + Comprehensive'], 'sort' => 2, 'is_active' => true]
        );
    }



    protected function seedB1Q2(Disclosure $b1): void
    {
        if (!$b1) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b1->id, 'key' => 'b1.q2'],
            [
                'number' => 2,
                'type'   => 'radio-cards',
                'title'  => [
                    'en' => 'Reporting scope',
                    'fi' => 'Raportoinnin laajuus',
                ],
                'help_official' => [
                    'en' => 'Choose individual (parent only) or consolidated (parent + subsidiaries).',
                    'fi' => 'Valitse yksittäinen (emoyhtiö) tai konserni (emo + tytäryhtiöt).',
                ],
                'rules'     => ['choice' => ['required','in:individual,consolidated']],
                'order'     => 2,
                'is_active' => true,
                'meta'      => [
                    'ui'   => ['compact' => true],
                    'gate' => [
                        'if'          => ['choice' => 'consolidated'],
                        'reveal_keys' => ['b1.q3'], // نشان‌دادن لیست تابعه‌ها فقط در حالت consolidated
                    ],
                ],
            ]
        );

        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'individual'],
            ['value' => 'individual', 'label' => ['en' => 'Individual (parent only)', 'fi' => 'Yksittäinen (emoyhtiö)'], 'sort' => 1, 'is_active' => true]
        );
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'consolidated'],
            ['value' => 'consolidated', 'label' => ['en' => 'Consolidated (parent + subsidiaries)', 'fi' => 'Konserni (emo + tytäryhtiöt)'], 'sort' => 2, 'is_active' => true]
        );
    } // This is a gate for subsiders

    protected function seedB1Q3(Disclosure $b1): void
    {
        if (!$b1) return;

        $q3 = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b1->id, 'key' => 'b1.q3'],
            [
                'number' => 3,
                'type'   => 'repeatable-group',
                'title'  => [
                    'en' => 'Subsidiaries list',
                    'fi' => 'Tytäryhtiöiden luettelo',
                ],
                'help_official' => [
                    'en' => 'List each subsidiary: company name and registered address (street, city, country).',
                    'fi' => 'Luettele jokainen tytäryhtiö: nimi ja rekisteröity osoite (katu, kaupunki, maa).',
                ],
                'help_friendly' => [
                    'en' => 'Add one row per subsidiary. Use official registered address.',
                    'fi' => 'Lisää yksi rivi per tytäryhtiö. Käytä virallista rekisteröityä osoitetta.',
                ],
                'rules' => [
                    'required' => true,
                    'array'    => true,
                    'min'      => 1,
                    'item_rules' => [
                        'name'   => ['required', 'string', 'max:200'],
                        'street' => ['required', 'string', 'max:300'],
                        'city'   => ['required', 'string', 'max:120'],
                        'country'=> ['required'], // ISO2 recommended in UI
                    ],
                ],
                'order'     => 3,
                'is_active' => true,
                'meta'      => [
                    'role' => 'subsidiaries',
                    // Visible only when reporting scope is consolidated
                    'visible_if' => [
                        ['when' => ['key' => 'b1.q2', 'eq' => 'consolidated']],
                    ],
                    'ui' => ['compact' => true],
                ],
            ]
        );

        // Fields: Company name
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'name'],
            [
                'label' => ['en' => 'Company name', 'fi' => 'Yrityksen nimi'],
                'extra' => ['type' => 'text', 'placeholder' => 'Acme Subsidiary Ltd'],
                'sort'  => 1,
                'is_active' => true,
            ]
        );

        // Registered address — Street
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'street'],
            [
                'label' => ['en' => 'Street address', 'fi' => 'Katuosoite'],
                'extra' => ['type' => 'text', 'placeholder' => 'Mäkelänkatu 25 B 13'],
                'sort'  => 2,
                'is_active' => true,
            ]
        );

        // Registered address — City
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'city'],
            [
                'label' => ['en' => 'City / Town', 'fi' => 'Kaupunki / Paikkakunta'],
                'extra' => ['type' => 'text', 'placeholder' => 'Helsinki'],
                'sort'  => 3,
                'is_active' => true,
            ]
        );

        // Registered address — Country (example list, keep consistent with B1)
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q3->id, 'kind' => 'field', 'key' => 'country'],
            [
                'label' => ['en' => 'Country', 'fi' => 'Maa'],
                'extra' => [
                    'type' => 'select',
                    'choices' => [
                        ['value' => 'FI', 'label' => 'Finland'],
                        ['value' => 'SE', 'label' => 'Sweden'],
                        ['value' => 'DE', 'label' => 'Germany'],
                        ['value' => 'FR', 'label' => 'France'],
                        ['value' => 'IR', 'label' => 'Iran'],
                    ],
                ],
                'sort'  => 4,
                'is_active' => true,
            ]
        );
    }



    protected function seedB1Q4(Disclosure $b1): void
    {
        $q4 = Question::updateOrCreate(
            ['disclosure_id' => $b1->id, 'key' => 'b1.q4'],
            [
                'number' => 4,
                'type' => 'radio-with-other',
                'title' => [
                    'en' => "What is your company's legal form?",
                    'fi' => "Mikä on yrityksesi oikeudellinen muoto?",
                ],
                'rules' => [
                    'type' => 'radio-with-other',
                    'choice' => ['required' => true, 'in' => ['pll', 'sole', 'partnership', 'cooperative', 'other']],
                ],
                'order' => 4,
                'is_active' => true,
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
            ['key' => 'pll', 'value' => 'pll', 'label' => ['en' => 'Private limited liability undertaking'], 'sort' => 1],
            ['key' => 'sole', 'value' => 'sole', 'label' => ['en' => 'Sole proprietorship'], 'sort' => 2],
            ['key' => 'partnership', 'value' => 'partnership', 'label' => ['en' => 'Partnership'], 'sort' => 3],
            ['key' => 'cooperative', 'value' => 'cooperative', 'label' => ['en' => 'Cooperative'], 'sort' => 4],
            ['key' => 'other', 'value' => 'other', 'label' => ['en' => 'Other'], 'sort' => 5, 'extra' => ['shows_text' => true, 'placeholder' => 'Type your legal form...']],
        ];

        foreach ($opts as $o) {
            QuestionOption::updateOrCreate(
                ['question_id' => $q4->id, 'kind' => 'option', 'key' => $o['key']],
                ['value' => $o['value'], 'label' => $o['label'], 'sort' => $o['sort'], 'is_active' => true, 'extra' => $o['extra'] ?? null]
            );
        }
    }

    protected function seedB1Q5(Disclosure $b1): void
    {
        $q5 = Question::updateOrCreate(
            ['disclosure_id' => $b1->id, 'key' => 'b1.q5'],
            [
                'number' => 5,
                'type' => 'radio-cards',
                'title' => [
                    'en' => 'Does your company have sustainability certificates or labels?',
                    'fi' => 'Onko yritykselläsi kestävyystodistuksia tai -merkintöjä?',
                ],
                'rules' => [
                    'choice' => ['required', 'in:yes,no'],
                    'desc' => ['nullable', 'string', 'max:500', 'required_if:choice,yes'],
                ],
                'order' => 5,
                'is_active' => true,
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
                'value' => 'yes', 'label' => ['en' => 'Yes', 'fi' => 'Kyllä'], 'sort' => 1, 'is_active' => true,
                'extra' => [
                    'requires_evidence' => true,
                    'uploader' => [
                        'enabled' => true,
                        'label' => ['en' => 'Attach any related files as evidence.', 'fi' => 'Liitä asiaan liittyvät tiedostot todisteeksi.'],
                        'max_files' => 5, 'max_size_mb' => 10,
                        'mimes' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'],
                        'path_pattern' => 'reports/{report_id}/b1.q5',
                    ],
                    'fields' => [[
                        'key' => 'desc', 'type' => 'textarea',
                        'label' => ['en' => 'Which certificates/labels? Issuer & validity', 'fi' => 'Mitkä sertifikaatit/merkinnät?'],
                        'placeholder' => ['en' => 'ISO 14001 (SGS, valid till 2026-05)', 'fi' => 'esim. ISO 14001 ...'],
                        'max' => 500,
                    ]],
                ],
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q5->id, 'kind' => 'option', 'key' => 'no'],
            ['value' => 'no', 'label' => ['en' => 'No', 'fi' => 'Ei'], 'sort' => 2, 'is_active' => true, 'extra' => ['requires_evidence' => false, 'fields' => []]]
        );
    }


    protected function seedB1Q6(Disclosure $b1): void
    {
        $q = Question::updateOrCreate(
            ['disclosure_id' => $b1->id, 'key' => 'b1.q6'],
            [
                'number' => 6,
                'type' => 'multi-input', // تک‌فیلدی؛ برای suffix واحد پول
                'title' => [
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
                    'array' => true,
                    'required' => true,
                    'item_rules' => [
                        'balance_sheet_eur' => ['required', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 6,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'balance_sheet_eur'],
            [
                'label' => ['en' => 'Balance sheet total', 'fi' => 'Taseen loppusumma'],
                'extra' => [
                    'type' => 'number',
                    'step' => '0.01',
                    'min' => 0,
                    'suffix' => '€',
                    'placeholder' => '1000000',
                ],
                'sort' => 1,
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
                'type' => 'multi-input', // تک‌فیلدی برای نمایش واحد پول
                'title' => [
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
                    'array' => true,
                    'required' => true,
                    'item_rules' => [
                        'turnover_eur' => ['required', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 7,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'turnover_eur'],
            [
                'label' => ['en' => 'Turnover', 'fi' => 'Liikevaihto'],
                'extra' => [
                    'type' => 'number',
                    'step' => '0.01',
                    'min' => 0,
                    'suffix' => '€', // یا 'EUR' اگر می‌خواهی نوشتاری باشد
                    'placeholder' => '2500000',
                ],
                'sort' => 1,
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
                'type' => 'multi-input', // تک‌فیلدی
                'title' => [
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
                    'array' => true,
                    'required' => true,
                    'item_rules' => [
                        'employees' => ['required', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 8,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'employees'],
            [
                'label' => ['en' => 'Employees (headcount/FTE)', 'fi' => 'Henkilöstö (hlö/HTV)'],
                'extra' => [
                    'type' => 'number',
                    'step' => '0.1',        // برای FTE
                    'min' => 0,
                    'placeholder' => '42 or 38.5',
                ],
                'sort' => 1,
                'is_active' => true,
            ]
        );
    }


    protected function seedB1Q9(Disclosure $b1): void
    {
        if (!$b1) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b1->id, 'key' => 'b1.q9'],
            [
                'number' => 9,
                'type'   => 'repeatable-group',
                'title'  => [
                    'en' => 'Significant sites list',
                    'fi' => 'Merkittävien toimipaikkojen luettelo',
                ],
                'help_official' => [
                    'en' => 'Provide each significant site: name/label, registered address (street, postcode, city, country) and geolocation.',
                    'fi' => 'Anna jokaisesta merkittävästä toimipaikasta: nimi, rekisteröity osoite (katu, postinumero, kaupunki, maa) ja geolocation.',
                ],
                'help_friendly' => [
                    'en' => 'Add one row per site. Use this format for geolocation: "lat 60.17 | lon 24.94".',
                    'fi' => 'Lisää yksi rivi per toimipaikka. Käytä geolocation-muotoa: "lat 60.17 | lon 24.94".',
                ],
                // ✅ Rules به‌روزشده (بدون site_type)
                'rules' => [
                    'required' => true,
                    'array'    => true,
                    'min'      => 1,
                    'item_rules' => [
                        'site_name'  => ['required','string','max:200'],
                        'street'     => ['required','string','max:300'],
                        'postcode'   => ['required','string','max:20'],
                        'city'       => ['required','string','max:120'],
                        'country'    => ['required'], // مطابق choices
                        // الگوی "lat 60.17 | lon 24.94"
                        'geolocation'=> ['required','string'],
                    ],
                ],
                'order'     => 9,
                'is_active' => true,
                'meta'      => [
                    'role' => 'significant_sites',
                    'ui'   => ['compact' => true],
                ],
            ]
        );

        // Site name / label
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'site_name'],
            [
                'label' => ['en' => 'Site name / label', 'fi' => 'Toimipaikan nimi'],
                'extra' => ['type' => 'text', 'placeholder' => 'Headquarters / Plant A'],
                'sort'  => 1,
                'is_active' => true,
            ]
        );

        // Street address
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'street'],
            [
                'label' => ['en' => 'Street address', 'fi' => 'Katuosoite'],
                'extra' => ['type' => 'text', 'placeholder' => 'Mannerheimintie 10'],
                'sort'  => 2,
                'is_active' => true,
            ]
        );

        // Postcode
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'postcode'],
            [
                'label' => ['en' => 'Postcode', 'fi' => 'Postinumero'],
                'extra' => ['type' => 'text', 'placeholder' => '00100'],
                'sort'  => 3,
                'is_active' => true,
            ]
        );

        // City
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'city'],
            [
                'label' => ['en' => 'City / Town', 'fi' => 'Kaupunki / Paikkakunta'],
                'extra' => ['type' => 'text', 'placeholder' => 'Helsinki'],
                'sort'  => 4,
                'is_active' => true,
            ]
        );

        // Country (select with fixed choices)
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'country'],
            [
                'label' => ['en' => 'Country', 'fi' => 'Maa'],
                'extra' => [
                    'type' => 'select',
                    'choices' => [
                        ['value' => 'FI', 'label' => 'Finland'],
                        ['value' => 'SE', 'label' => 'Sweden'],
                        ['value' => 'DE', 'label' => 'Germany'],
                        ['value' => 'FR', 'label' => 'France'],
                        ['value' => 'IR', 'label' => 'Iran'],
                    ],
                ],
                'sort'  => 5,
                'is_active' => true,
            ]
        );

        // Geolocation (single text field)
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'geolocation'],
            [
                'label' => ['en' => 'Geolocation', 'fi' => 'Sijainti (koordinaatit)'],
                'extra' => ['type' => 'text', 'placeholder' => 'lat 60.17 | lon 24.94'],
                'sort'  => 6,
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
                'type' => 'radio-cards',
                'title' => [
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
                    'desc' => ['nullable', 'string', 'max:500', 'required_if:choice,yes'],
                ],
                'order' => 1,
                'is_active' => true,
                'meta' => ['ui' => ['show_evidence_badge' => true]],
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
                        'label' => ['en' => 'Attach any related files as evidence.', 'fi' => 'Liitä asiaan liittyvät tiedostot todisteeksi.'],
                        'max_files' => 5, 'max_size_mb' => 10,
                        'mimes' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'],
                        'path_pattern' => 'reports/{report_id}/b2.q1',
                    ],
                    'fields' => [[
                        'key' => 'desc', 'type' => 'textarea',
                        'label' => ['en' => 'Describe your practices', 'fi' => 'Kuvaile käytäntöjä'],
                        'placeholder' => ['en' => 'circular design program, supplier ESG screening...', 'fi' => 'esim. kiertotalouden...'],
                        'max' => 500,
                    ]],
                ],
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            ['value' => 'no', 'label' => ['en' => 'No', 'fi' => 'Ei'], 'sort' => 2, 'is_active' => true, 'extra' => ['requires_evidence' => false, 'fields' => []]]
        );
    }

    // (b) Policies + publicly available
    protected function seedB2Q2_Policies(Disclosure $b2): void
    {
        $q = Question::updateOrCreate(
            ['disclosure_id' => $b2->id, 'key' => 'b2.q2'],
            [
                'number' => 2,
                'type' => 'radio-cards',
                'title' => [
                    'en' => 'Does your company have any policies on sustainability issues?',
                    'fi' => 'Onko yrityksellänne kestävyyspolitiikkoja?',
                ],
                'help_official' => [
                    'en' => 'State whether policies exist and whether they are publicly available.',
                    'fi' => 'Kerro, onko politiikkoja ja ovatko ne julkisesti saatavilla.',
                ],
                'rules' => [
                    'choice' => ['required', 'in:yes,no'],
                    'desc' => ['nullable', 'string', 'max:500', 'required_if:choice,yes'],
                    'publicly_available' => ['nullable', 'in:yes,no', 'required_if:choice,yes'],
                ],
                'order' => 2,
                'is_active' => true,
                'meta' => ['ui' => ['show_evidence_badge' => true]],
            ]
        );

        // YES
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'yes'],
            [
                'value' => 'yes', 'label' => ['en' => 'Yes', 'fi' => 'Kyllä'], 'sort' => 1, 'is_active' => true,
                'extra' => [
                    'requires_evidence' => true,
                    'uploader' => [
                        'enabled' => true,
                        'label' => ['en' => 'Attach any related files as evidence.', 'fi' => 'Liitä asiaan liittyvät tiedostot.'],
                        'max_files' => 5, 'max_size_mb' => 10,
                        'mimes' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'],
                        'path_pattern' => 'reports/{report_id}/b2.q2',
                    ],
                    'fields' => [
                        [
                            'key' => 'desc', 'type' => 'textarea',
                            'label' => ['en' => 'Describe the policies', 'fi' => 'Kuvaile politiikat'],
                            'placeholder' => ['en' => 'Environmental, social or governance policies…', 'fi' => 'Ympäristö-, sosiaali- tai hallintopolitiikat…'],
                            'max' => 500,
                        ],
                        [
                            'key' => 'publicly_available', 'type' => 'radio',
                            'label' => ['en' => 'Are the policies publicly available?', 'fi' => 'Ovatko politiikat julkisia?'],
                            'choices' => [
                                ['value' => 'yes', 'label' => 'Yes'],
                                ['value' => 'no', 'label' => 'No'],
                            ],
                        ],
                    ],
                ],
            ]
        );

        // NO
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            ['value' => 'no', 'label' => ['en' => 'No', 'fi' => 'Ei'], 'sort' => 2, 'is_active' => true, 'extra' => ['requires_evidence' => false, 'fields' => []]]
        );
    }

    // (c) Future initiatives
    protected function seedB2Q3_FutureInitiatives(Disclosure $b2): void
    {
        $q = Question::updateOrCreate(
            ['disclosure_id' => $b2->id, 'key' => 'b2.q3'],
            [
                'number' => 3,
                'type' => 'radio-cards',
                'title' => [
                    'en' => 'Does your company have any future initiatives or forward-looking plans on sustainability issues?',
                    'fi' => 'Onko yrityksellänne tulevia aloitteita tai suunnitelmia kestävyyskysymyksissä?',
                ],
                'help_official' => ['en' => 'Please specify.', 'fi' => 'Täsmennä.'],
                'help_friendly' => [
                    'en' => 'Examples: upcoming targets, roadmaps, pilot projects, transition plans, etc.',
                    'fi' => 'Esim. tulevat tavoitteet, tiekartat, pilottihankkeet, siirtymäsuunnitelmat jne.',
                ],
                'rules' => [
                    'choice' => ['required', 'in:yes,no'],
                    'desc' => ['nullable', 'string', 'max:500', 'required_if:choice,yes'],
                ],
                'order' => 3,
                'is_active' => true,
                'meta' => ['ui' => ['show_evidence_badge' => true]],
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
                        'label' => ['en' => 'Attach any related files as evidence.', 'fi' => 'Liitä asiaan liittyvät tiedostot todisteeksi.'],
                        'max_files' => 5, 'max_size_mb' => 10,
                        'mimes' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'],
                        'path_pattern' => 'reports/{report_id}/b2.q3',
                    ],
                    'fields' => [[
                        'key' => 'desc', 'type' => 'textarea',
                        'label' => ['en' => 'Describe the planned initiatives / timelines', 'fi' => 'Kuvaile suunnitellut aloitteet / aikataulut'],
                        'placeholder' => ['en' => 'net-zero roadmap by 2030, product redesign pilot…', 'fi' => 'hiilineutraali tiekartta 2030, ...'],
                        'max' => 500,
                    ]],
                ],
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            ['value' => 'no', 'label' => ['en' => 'No', 'fi' => 'Ei'], 'sort' => 2, 'is_active' => true, 'extra' => ['requires_evidence' => false, 'fields' => []]]
        );
    }

    // (d) Targets
    protected function seedB2Q4_Targets(Disclosure $b2): void
    {
        $q = Question::updateOrCreate(
            ['disclosure_id' => $b2->id, 'key' => 'b2.q4'],
            [
                'number' => 4,
                'type' => 'radio-cards',
                'title' => [
                    'en' => 'Has your company set any targets to monitor the implementation of the policies and the progress achieved?',
                    'fi' => 'Onko yrityksenne asettanut tavoitteita politiikkojen toimeenpanon ja edistymisen seuraamiseksi?',
                ],
                'help_official' => ['en' => 'Please specify.', 'fi' => 'Täsmennä.'],
                'help_friendly' => [
                    'en' => 'Examples: KPI targets, interim milestones, validation method, review frequency.',
                    'fi' => 'Esim. KPI-tavoitteet, välitavoitteet, validointitapa, tarkistuksen tiheys.',
                ],
                'rules' => [
                    'choice' => ['required', 'in:yes,no'],
                    'desc' => ['nullable', 'string', 'max:500', 'required_if:choice,yes'],
                ],
                'order' => 4,
                'is_active' => true,
                'meta' => ['ui' => ['show_evidence_badge' => true]],
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
                        'label' => ['en' => 'Attach any related files as evidence.', 'fi' => 'Liitä asiaan liittyvät tiedostot todisteeksi.'],
                        'max_files' => 5, 'max_size_mb' => 10,
                        'mimes' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'],
                        'path_pattern' => 'reports/{report_id}/b2.q4',
                    ],
                    'fields' => [[
                        'key' => 'desc', 'type' => 'textarea',
                        'label' => ['en' => 'Describe the targets / metrics and review cadence', 'fi' => 'Kuvaa tavoitteet / mittarit ja tarkistussykli'],
                        'placeholder' => ['en' => '30% emissions cut by 2027, quarterly KPI review…', 'fi' => '30 % päästövähennys 2027, KPI-katsaus neljännesvuosittain…'],
                        'max' => 500,
                    ]],
                ],
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            ['value' => 'no', 'label' => ['en' => 'No', 'fi' => 'Ei'], 'sort' => 2, 'is_active' => true, 'extra' => ['requires_evidence' => false, 'fields' => []]]
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
                'type' => 'multi-input',
                'title' => [
                    'en' => 'Annual energy consumption by source and renewability (MWh)',
                    'fi' => 'Vuosittainen energiankulutus lähteittäin ja uusiutuvuuden mukaan (MWh)',
                ],
                'help_official' => [
                    'en' => 'Enter annual consumption split into renewable and non-renewable for both electricity and fuels. Use MWh.',
                    'fi' => 'Anna vuosikulutus jaaettuna uusiutuvaan ja ei-uusiutuvaan sekä sähköön ja polttoaineisiin. Yksikkö: MWh.',
                ],
                'help_friendly' => [
                    'en' => 'If your data is in kWh, liters or m³, convert to MWh before entering. Estimates are fine.',
                    'fi' => 'Jos data on kWh, litroina tai m³, muunna MWh:ksi ennen syöttöä. Arviot käyvät.',
                ],
                'rules' => [
                    'array' => true,
                    'required' => true,
                    'item_rules' => [
                        'electricity_renewable_mwh'     => ['nullable', 'numeric', 'min:0'],
                        'electricity_nonrenewable_mwh'  => ['nullable', 'numeric', 'min:0'],
                        'fuel_renewable_mwh'            => ['nullable', 'numeric', 'min:0'],
                        'fuel_nonrenewable_mwh'         => ['nullable', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 1,
                'is_active' => true,
            ]
        );

        // Electricity — Renewable
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'electricity_renewable_mwh'],
            [
                'label' => [
                    'en' => 'Electricity from renewable sources (MWh)',
                    'fi' => 'Sähkö uusiutuvista lähteistä (MWh)',
                ],
                'extra' => [
                    'type' => 'number', 'placeholder' => '100', 'step' => 'any', 'min' => 0, 'suffix' => 'MWh'
                ],
                'sort' => 1,
                'is_active' => true,
            ]
        );

        // Electricity — Non-renewable
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'electricity_nonrenewable_mwh'],
            [
                'label' => [
                    'en' => 'Electricity from non-renewable sources (MWh)',
                    'fi' => 'Sähkö ei-uusiutuvista lähteistä (MWh)',
                ],
                'extra' => [
                    'type' => 'number', 'placeholder' => '100', 'step' => 'any', 'min' => 0, 'suffix' => 'MWh'
                ],
                'sort' => 2,
                'is_active' => true,
            ]
        );

        // Fuels — Renewable
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'fuel_renewable_mwh'],
            [
                'label' => [
                    'en' => 'Renewable fuels (MWh)',
                    'fi' => 'Uusiutuvat polttoaineet (MWh)',
                ],
                'extra' => [
                    'type' => 'number', 'placeholder' => '50', 'step' => 'any', 'min' => 0, 'suffix' => 'MWh'
                ],
                'sort' => 3,
                'is_active' => true,
            ]
        );

        // Fuels — Non-renewable
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'fuel_nonrenewable_mwh'],
            [
                'label' => [
                    'en' => 'Non-renewable fuels (MWh)',
                    'fi' => 'Ei-uusiutuvat polttoaineet (MWh)',
                ],
                'extra' => [
                    'type' => 'number', 'placeholder' => '50', 'step' => 'any', 'min' => 0, 'suffix' => 'MWh'
                ],
                'sort' => 4,
                'is_active' => true,
            ]
        );
    }



    // B3.Q2 — Boundary approach (radio-cards)
    protected function seedB3Q2_Boundary(Disclosure $b3): void
    {
        $q = Question::updateOrCreate(
            ['disclosure_id' => $b3->id, 'key' => 'b3.q2'],
            [
                'number' => 2,
                'type' => 'radio-cards',
                'title' => [
                    'en' => 'Which consolidation/boundary approach do you use for GHG accounting?',
                    'fi' => 'Mitä rajaustapaa käytätte GHG-laskennassa?',
                ],
                'help_official' => [
                    'en' => 'Choose Equity share or Control approach (Operational/Financial) as defined by the GHG Protocol.',
                    'fi' => 'Valitse Equity share tai Control (Operational/Financial) GHG-protokollan mukaan.',
                ],
                'rules' => [
                    'choice' => ['required', 'in:equity_share,control_operational,control_financial'],
                    'desc' => ['nullable', 'string', 'max:500'],
                ],
                'order' => 2,
                'is_active' => true,
            ]
        );

        $opts = [
            ['key' => 'equity', 'value' => 'equity_share', 'label' => ['en' => 'Equity share approach'], 'hint' => 'Account emissions by equity share.'],
            ['key' => 'ctrl_op', 'value' => 'control_operational', 'label' => ['en' => 'Control approach — Operational control'], 'hint' => 'Full authority to implement operating policies.'],
            ['key' => 'ctrl_fin', 'value' => 'control_financial', 'label' => ['en' => 'Control approach — Financial control'], 'hint' => 'Ability to direct financial & operating policies.'],
        ];

        foreach ($opts as $i => $o) {
            QuestionOption::updateOrCreate(
                ['question_id' => $q->id, 'kind' => 'option', 'key' => $o['key']],
                [
                    'value' => $o['value'],
                    'label' => $o['label'],
                    'sort' => $i + 1,
                    'is_active' => true,
                    'extra' => [
                        'hint' => $o['hint'],
                        // textarea اختیاری (رادیوکارت شما به طور خودکار اولین textarea را به value.desc بایند می‌کند)
                        'fields' => [[
                            'key' => 'desc', 'type' => 'textarea',
                            'label' => ['en' => 'Notes (optional)'],
                            'placeholder' => ['en' => 'Any clarification about boundaries...'],
                            'max' => 500,
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
            ['disclosure_id' => $b3->id, 'key' => 'b3.q3'],
            [
                'number' => 3,
                'type' => 'multi-input',
                'title' => ['en' => 'Scope 1 GHG emissions (tCO2e)'],
                'help_official' => ['en' => 'Report annual gross Scope 1 emissions. Unit: tCO2e.'],
                'rules' => ['array' => true, 'required' => true, 'item_rules' => [
                    'scope1_tco2e' => ['required', 'numeric', 'min:0'],
                ]],
                'order' => 3,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'scope1_tco2e'],
            [
                'label' => ['en' => 'Scope 1'],
                'extra' => ['type' => 'number', 'step' => 'any', 'min' => 0, 'suffix' => 'tCO2e', 'placeholder' => '150.0'],
                'sort' => 1, 'is_active' => true,
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
                'type' => 'multi-input',
                'title' => [
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
                    'array' => true,
                    'required' => true,
                    'item_rules' => [
                        'scope2_loc_tco2e' => ['required', 'numeric', 'min:0'],

                    ],
                ],
                'order' => 4,
                'is_active' => true,
            ]
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'scope2_loc_tco2e'],
            [
                'label' => ['en' => 'Scope 2 (location-based)'],
                'extra' => ['type' => 'number', 'step' => 'any', 'min' => 0, 'suffix' => 'tCO2e', 'placeholder' => '250.0'],
                'sort' => 1,
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
                'type' => 'radio-cards',
                'title' => [
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
                    'desc' => ['nullable', 'string', 'max:1000', 'required_if:choice,yes'],
                ],
                'order' => 1,
                'is_active' => true,
                'meta' => ['ui' => ['show_evidence_badge' => true]],
            ]
        );


        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'yes'],
            [
                'value' => 'yes',
                'label' => ['en' => 'Yes', 'fi' => 'Kyllä'],
                'sort' => 1,
                'is_active' => true,
                'extra' => [
                    'requires_evidence' => true,
                    'uploader' => [
                        'enabled' => true,
                        'label' => [
                            'en' => 'Attach any related files as evidence.',
                            'fi' => 'Liitä asiaan liittyvät tiedostot todisteeksi.',
                        ],
                        'max_files' => 5,
                        'max_size_mb' => 10,
                        'mimes' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'],
                        'path_pattern' => 'reports/{report_id}/b4.q1',
                    ],
                    'fields' => [[
                        'key' => 'desc',
                        'type' => 'textarea',
                        'label' => ['en' => 'Description', 'fi' => 'Kuvaus'],
                        'placeholder' => ['en' => 'Describe scope, pollutants, link to public source...', 'fi' => 'Kuvaa laajuus, päästöt, linkki…'],
                        'max' => 1000,
                    ]],
                ],
            ]
        );

        // NO
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            [
                'value' => 'no',
                'label' => ['en' => 'No', 'fi' => 'Ei'],
                'sort' => 2,
                'is_active' => true,
                'extra' => ['requires_evidence' => false, 'fields' => []],
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
                'type' => 'radio-cards',
                'title' => [
                    'en' => 'Does your company own, lease or manage sites in or near biodiversity sensitive areas?',
                    'fi' => 'Omistaako, vuokraako tai hallinnoiko yrityksenne kohteita luonnon monimuotoisuuden kannalta herkissä alueissa tai niiden läheisyydessä?',
                ],
                'help_official' => [
                    'en' => 'Answer Yes if any of your sites are inside or near a biodiversity sensitive area.',
                    'fi' => 'Vastaa Kyllä, jos yksikin kohteistanne sijaitsee tai on lähellä luonnon monimuotoisuuden kannalta herkkää aluetta.',
                ],
                'rules' => [
                    'choice' => ['required', 'in:yes,no'],
                ],
                'order' => 1,
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
                'type' => 'repeatable-group',
                'title' => [
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

                    'required' => true,
                    'array' => true,
                    'min' => 1,
                    'item_rules' => [
                        'site_uid' => ['required', 'string', 'max:50'],
                        'area_ha' => ['required', 'numeric', 'min:0'],
                        'sensitive_area_name' => ['required', 'string', 'max:200'],
                        'inside_sensitive_area' => ['required', 'in:yes,no'],
                    ],
                ],
                'order' => 2,
                'is_active' => true,
                'meta' => [
                    // برای گزارش: گزارش بتواند این ردیف‌ها را با role پیدا کند
                    'role' => 'biodiversity_sites',


                    'visible_if' => [
                        ['when' => ['key' => 'b5.q1', 'eq' => 'yes']],
                    ],


                    'sources' => [
                        'site_list' => [
                            'from_question' => 'b1.q9',
                            'value_key' => '_uid',
                            'label_tpl' => '{{name}} — {{city}}, {{country}}',
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
                    'type' => 'select',
                    'choices' => [], // در Front از sources.site_list پر می‌شود
                    'placeholder' => 'Select a site…',
                    'hint' => 'Pick from your sites defined',
                ],
                'sort' => 1,
                'is_active' => true,
            ]
        );

        // فیلد: مساحت (هکتار)
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'area_ha'],
            [
                'label' => ['en' => 'Area (hectares)', 'fi' => 'Pinta-ala (hehtaaria)'],
                'extra' => [
                    'type' => 'number',
                    'step' => 'any',
                    'min' => 0,
                    'suffix' => 'ha',
                    'placeholder' => '12.5',
                ],
                'sort' => 2,
                'is_active' => true,
            ]
        );

        // فیلد: نام/نوع ناحیه حساس
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'sensitive_area_name'],
            [
                'label' => ['en' => 'Which biodiversity sensitive area?', 'fi' => 'Mikä luontokohde?'],
                'extra' => [
                    'type' => 'text',
                    'placeholder' => 'Natura 2000 – FI0200071',
                    'maxlength' => 200,
                ],
                'sort' => 3,
                'is_active' => true,
            ]
        );

        // فیلد: داخل/نزدیک ناحیه حساس
        QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'inside_sensitive_area'],
            [
                'label' => ['en' => 'Is the site inside the sensitive area?', 'fi' => 'Onko kohde alueen sisällä?'],
                'extra' => [
                    'type' => 'select',
                    'choices' => [
                        ['value' => 'yes', 'label' => 'Yes'],
                        ['value' => 'no', 'label' => 'No (nearby)'],
                    ],
                ],
                'sort' => 4,
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
                'type' => 'multi-input',
                'title' => [
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
                        'total_land_ha' => ['nullable', 'numeric', 'min:0'],
                        'sealed_area_ha' => ['nullable', 'numeric', 'min:0'],
                        'nature_on_site_ha' => ['nullable', 'numeric', 'min:0'],
                        'nature_off_site_ha' => ['nullable', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 3,
                'is_active' => true,
                'meta' => [
                    'visible_if' => [
                        ['when' => ['key' => 'b5.q1', 'eq' => 'yes']],
                    ],
                ],
            ]
        );

        $fields = [
            ['key' => 'total_land_ha', 'label' => 'Total use of land (ha)'],
            ['key' => 'sealed_area_ha', 'label' => 'Total sealed area (ha)'],
            ['key' => 'nature_on_site_ha', 'label' => 'Total nature-oriented area on-site (ha)'],
            ['key' => 'nature_off_site_ha', 'label' => 'Total nature-oriented area off-site (ha)'],
        ];

        $sort = 1;
        foreach ($fields as $f) {
            QuestionOption::updateOrCreate(
                ['question_id' => $q->id, 'kind' => 'field', 'key' => $f['key']],
                [
                    'label' => ['en' => $f['label']],
                    'extra' => ['type' => 'number', 'step' => 'any', 'min' => 0, 'suffix' => 'ha', 'placeholder' => '100.0'],
                    'sort' => $sort++,
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
                'type' => 'multi-input',
                'title' => [
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
                    'array' => true,
                    'required' => true,
                    'item_rules' => [
                        'withdrawal_l' => ['required', 'numeric', 'min:0'],
                        'has_high_stress' => ['required', 'in:yes,no'],
                        'withdrawal_high_stress_l' => [
                            'nullable', 'numeric', 'min:0',
                            'required_if:answers.b6.q1.has_high_stress,yes'
                        ],
                    ],
                ],
                'order' => 1,
                'is_active' => true,
                'meta' => [
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
                    'type' => 'number',
                    'step' => 'any',
                    'min' => 0,
                    'suffix' => 'L',
                    'placeholder' => '150000',
                ],
                'sort' => 1,
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
                    'type' => 'select',
                    'placeholder' => '— Select —',
                    'choices' => [
                        ['value' => 'yes', 'label' => 'Yes'],
                        ['value' => 'no', 'label' => 'No'],
                    ],
                ],
                'sort' => 2,
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
                    'type' => 'number',
                    'step' => 'any',
                    'min' => 0,
                    'suffix' => 'L',
                    'placeholder' => '25000',
                    'visible_if' => ['key' => 'has_high_stress', 'eq' => 'yes'],
                ],
                'sort' => 3,
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
                'type' => 'radio-cards',
                'title' => [
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
                    'choice' => ['required', 'in:yes,no'],

                    'discharge_liters' => ['nullable', 'numeric', 'min:0', 'required_if:choice,yes'],
                ],
                'order' => 2,
                'is_active' => true,
                'meta' => [
                    'ui' => ['compact' => true],
                ],
            ]
        );


        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'yes'],
            [
                'value' => 'yes',
                'label' => ['en' => 'Yes', 'fi' => 'Kyllä'],
                'sort' => 1,
                'is_active' => true,
                'extra' => [
                    'fields' => [[
                        'key' => 'discharge_liters',
                        'type' => 'number',
                        'label' => ['en' => 'Water discharged (liters)', 'fi' => 'Poistettu vesi (litraa)'],
                        'placeholder' => ['en' => '150000', 'fi' => '150000'],
                        'min' => 0,
                        'step' => 'any',
                        'suffix' => 'L',
                    ]],
                ],
            ]
        );

        // NO → هیچ فیلدی ندارد
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            [
                'value' => 'no',
                'label' => ['en' => 'No', 'fi' => 'Ei'],
                'sort' => 2,
                'is_active' => true,
                'extra' => ['fields' => []],
            ]
        );
    }


    protected function seedB7Q1_CircularPrinciples(Disclosure $b7): void
    {
        if (!$b7) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b7->id, 'key' => 'b7.q1'],
            [
                'number' => 1,
                'type' => 'radio-cards',
                'title' => [
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
                    'choice' => ['required', 'in:yes,no'],
                    // desc فقط در حالت yes اجباری است:
                    'desc' => ['nullable', 'string', 'max:500', 'required_if:choice,yes'],
                ],
                'order' => 1,
                'is_active' => true,
                'meta' => [
                    'ui' => ['compact' => true],
                ],
            ]
        );

        // گزینه YES با textarea توضیح
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'yes'],
            [
                'value' => 'yes',
                'label' => ['en' => 'Yes', 'fi' => 'Kyllä'],
                'sort' => 1,
                'is_active' => true,
                'extra' => [
                    // فیلد توضیح که در کامپوننت radio-cards شما به value.desc بایند می‌شود
                    'fields' => [[
                        'key' => 'desc',
                        'type' => 'textarea',
                        'label' => ['en' => 'How do you apply these principles?', 'fi' => 'Miten sovellatte näitä periaatteita?'],
                        'placeholder' => ['en' => 'recycled inputs, take-back scheme, remanufacturing…', 'fi' => 'esim. kierrätysmateriaalit, takaisinottopalvelu, uudelleenvalmistus…'],
                        'max' => 500,
                    ]],
                ],
            ]
        );

        // گزینه NO
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            [
                'value' => 'no',
                'label' => ['en' => 'No', 'fi' => 'Ei'],
                'sort' => 2,
                'is_active' => true,
                'extra' => [
                    'fields' => [], // ورودی اضافه ندارد
                ],
            ]
        );
    }

    protected function seedB7Q2_WasteGeneration(Disclosure $b7): void
    {
        if (!$b7) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b7->id, 'key' => 'b7.q2'],
            [
                'number' => 2,
                'type' => 'multi-input',
                'title' => [
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
                    'array' => true,
                    'required' => true,
                    'item_rules' => [
                        'non_hazardous_waste_kg' => ['required', 'numeric', 'min:0'],
                        'hazardous_waste_kg' => ['required', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 2,
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
                    'type' => 'number',
                    'step' => 'any',
                    'min' => 0,
                    'suffix' => 'kg',
                    'placeholder' => '12000',
                ],
                'sort' => 1,
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
                    'type' => 'number',
                    'step' => 'any',
                    'min' => 0,
                    'suffix' => 'kg',
                    'placeholder' => '350',
                ],
                'sort' => 2,
                'is_active' => true,
            ]
        );
    }

    protected function seedB7Q3_RecyclingReuse(Disclosure $b7): void
    {
        if (!$b7) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b7->id, 'key' => 'b7.q3'],
            [
                'number' => 3,
                'type' => 'multi-input',
                'title' => [
                    'en' => 'Waste directed to recycling and re-use (kg)',
                    'fi' => 'Kierrätykseen ja uudelleenkäyttöön ohjattu jäte (kg)',
                ],
                'help_official' => [
                    'en' => 'Report the annual amounts of waste directed to recycling and to re-use. Unit: kg.',
                    'fi' => 'Ilmoita vuosittain kierrätykseen ja uudelleenkäyttöön ohjatut jätemäärät. Yksikkö: kg.',
                ],
                'help_friendly' => [
                    'en' => 'If unsure, provide best estimates. Use kilograms (kg).',
                    'fi' => 'Jos et ole varma, anna paras arviosi. Käytä kilogrammoja (kg).',
                ],
                'rules' => [
                    'array' => true,
                    'required' => true,
                    'item_rules' => [
                        'waste_recycling_kg' => ['required', 'numeric', 'min:0'],
                        'waste_reuse_kg' => ['required', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 3,
                'is_active' => true,
            ]
        );

        // فیلد: مقدار هدایت‌شده به بازیافت (kg)
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'waste_recycling_kg'],
            [
                'label' => [
                    'en' => 'Waste directed to recycling (kg)',
                    'fi' => 'Kierrätykseen ohjattu jäte (kg)',
                ],
                'extra' => [
                    'type' => 'number',
                    'step' => 'any',
                    'min' => 0,
                    'suffix' => 'kg',
                    'placeholder' => '8000',
                ],
                'sort' => 1,
                'is_active' => true,
            ]
        );


        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'waste_reuse_kg'],
            [
                'label' => [
                    'en' => 'Waste directed to re-use (kg)',
                    'fi' => 'Uudelleenkäyttöön ohjattu jäte (kg)',
                ],
                'extra' => [
                    'type' => 'number',
                    'step' => 'any',
                    'min' => 0,
                    'suffix' => 'kg',
                    'placeholder' => '1200',
                ],
                'sort' => 2,
                'is_active' => true,
            ]
        );
    }

    protected function seedB7Q4_MaterialFlowsGate(Disclosure $b7): void
    {
        if (!$b7) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b7->id, 'key' => 'b7.q4'],
            [
                'number' => 4,
                'type' => 'radio-cards',
                'title' => [
                    'en' => 'Does the company operate in industries with significant material flows?',
                    'fi' => 'Toimiiko yritys aloilla, joilla on merkittäviä materiaalivirtoja?',
                ],
                'help_official' => [
                    'en' => 'Answer Yes if your sector involves significant material throughputs (manufacturing, construction, packaging).',
                    'fi' => 'Vastaa Kyllä, jos toimialaanne liittyy merkittäviä materiaalivirtoja (esim. valmistus, rakentaminen, pakkaus).',
                ],
                'rules' => [
                    'choice' => ['required', 'in:yes,no'],
                ],
                'order' => 4,
                'is_active' => true,
                'meta' => [
                    'ui' => ['compact' => true],
                    'gate' => [
                        'if' => ['choice' => 'yes'],
                        'reveal_keys' => ['b7.q5'], // نمایش لیست مواد فقط وقتی Yes
                    ],
                ],
            ]
        );

        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'yes'],
            ['value' => 'yes', 'label' => ['en' => 'Yes', 'fi' => 'Kyllä'], 'sort' => 1, 'is_active' => true]
        );

        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            ['value' => 'no', 'label' => ['en' => 'No', 'fi' => 'Ei'], 'sort' => 2, 'is_active' => true]
        );
    }

    protected function seedB7Q5_MaterialsList(Disclosure $b7): void
    {
        if (!$b7) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b7->id, 'key' => 'b7.q5'],
            [
                'number' => 5,
                'type' => 'repeatable-group',
                'title' => [
                    'en' => 'Materials used (name and amount)',
                    'fi' => 'Käytetyt materiaalit (nimi ja määrä)',
                ],
                'help_official' => [
                    'en' => 'List each relevant material and provide its annual amount. Unit: kg.',
                    'fi' => 'Luettele keskeiset materiaalit ja ilmoita vuosittainen määrä. Yksikkö: kg.',
                ],
                'help_friendly' => [
                    'en' => 'Add rows for each material (steel, concrete, plastic). Use kilograms.',
                    'fi' => 'Lisää rivejä kullekin materiaalille (esim. teräs, betoni, muovi). Käytä kilogrammoja.',
                ],
                'rules' => [
                    'required' => true,
                    'array' => true,
                    'min' => 1,
                    'item_rules' => [
                        'material_name' => ['required', 'string', 'max:200'],
                        'amount_kg' => ['required', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 5,
                'is_active' => true,
                'meta' => [
                    'role' => 'materials_used',
                    'visible_if' => [
                        ['when' => ['key' => 'b7.q4', 'eq' => 'yes']],
                    ],
                    'ui' => [
                        'compact' => true,
                    ],
                ],
            ]
        );

        // فیلد: نام ماده
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'material_name'],
            [
                'label' => [
                    'en' => 'Material name',
                    'fi' => 'Materiaalin nimi',
                ],
                'extra' => [
                    'type' => 'text',
                    'placeholder' => 'Steel / Concrete / Plastic',
                    'maxlength' => 200,
                ],
                'sort' => 1,
                'is_active' => true,
            ]
        );

        // فیلد: مقدار (kg)
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'amount_kg'],
            [
                'label' => [
                    'en' => 'Amount (kg)',
                    'fi' => 'Määrä (kg)',
                ],
                'extra' => [
                    'type' => 'number',
                    'step' => 'any',
                    'min' => 0,
                    'suffix' => 'kg',
                    'placeholder' => '150000',
                ],
                'sort' => 2,
                'is_active' => true,
            ]
        );
    }


    protected function seedB8Q1_Contracts(Disclosure $b8): void
    {
        if (!$b8) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b8->id, 'key' => 'b8.q1'],
            [
                'number' => 1,
                'type' => 'multi-input',
                'title' => [
                    'en' => 'Employees by contract type',
                    'fi' => 'Työsuhdetyypin mukaan',
                ],
                'help_official' => [
                    'en' => 'Report headcount on temporary and permanent contracts for the reporting period.',
                    'fi' => 'Ilmoita määräaikaiset ja vakituiset työntekijät (headcount) raportointikaudelta.',
                ],
                'help_friendly' => [
                    'en' => 'Whole persons (headcount). If none, enter 0.',
                    'fi' => 'Henkilömäärä (headcount). Jos ei ole, syötä 0.',
                ],
                'rules' => [
                    'array' => true,
                    'required' => true,
                    'item_rules' => [
                        'temporary_contracts' => ['required', 'numeric', 'min:0'],
                        'permanent_contracts' => ['required', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 1,
                'is_active' => true,
                'meta' => [
                    'ui' => ['compact' => true],
                ],
            ]
        );

        // Field: Employees on temporary contracts
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'temporary_contracts'],
            [
                'label' => [
                    'en' => 'Employees on temporary contracts',
                    'fi' => 'Määräaikaiset työntekijät',
                ],
                'extra' => [
                    'type' => 'number',
                    'step' => '1',
                    'min' => 0,
                    'placeholder' => '5',
                ],
                'sort' => 1,
                'is_active' => true,
            ]
        );

        // Field: Employees on permanent contracts
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'permanent_contracts'],
            [
                'label' => [
                    'en' => 'Employees on permanent contracts',
                    'fi' => 'Vakinaiset työntekijät',
                ],
                'extra' => [
                    'type' => 'number',
                    'step' => '1',
                    'min' => 0,
                    'placeholder' => '20',
                ],
                'sort' => 2,
                'is_active' => true,
            ]
        );
    }

    protected function seedB8Q2_Gender(Disclosure $b8): void
    {
        if (!$b8) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b8->id, 'key' => 'b8.q2'],
            [
                'number' => 2,
                'type' => 'multi-input',
                'title' => [
                    'en' => 'Employees by gender',
                    'fi' => 'Työntekijät sukupuolen mukaan',
                ],
                'help_official' => [
                    'en' => 'Report headcount by gender categories for the reporting period.',
                    'fi' => 'Ilmoita henkilöstömäärä sukupuolittain raportointikaudelta.',
                ],
                'help_friendly' => [
                    'en' => 'Whole persons (headcount). If none, enter 0.',
                    'fi' => 'Henkilömäärä (headcount). Jos ei ole, syötä 0.',
                ],
                'rules' => [
                    'array' => true,
                    'required' => true,
                    'item_rules' => [
                        'female' => ['required', 'numeric', 'min:0'],
                        'male' => ['required', 'numeric', 'min:0'],
                        'other' => ['required', 'numeric', 'min:0'],
                        'not_specified' => ['required', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 2,
                'is_active' => true,
                'meta' => [
                    'ui' => ['compact' => true],
                ],
            ]
        );

        // Female
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'female'],
            [
                'label' => ['en' => 'Female employees', 'fi' => 'Naiset'],
                'extra' => ['type' => 'number', 'step' => '1', 'min' => 0, 'placeholder' => '20'],
                'sort' => 1,
                'is_active' => true,
            ]
        );

        // Male
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'male'],
            [
                'label' => ['en' => 'Male employees', 'fi' => 'Miehet'],
                'extra' => ['type' => 'number', 'step' => '1', 'min' => 0, 'placeholder' => '18'],
                'sort' => 2,
                'is_active' => true,
            ]
        );

        // Other
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'other'],
            [
                'label' => ['en' => 'Other', 'fi' => 'Muu'],
                'extra' => ['type' => 'number', 'step' => '1', 'min' => 0, 'placeholder' => '1'],
                'sort' => 3,
                'is_active' => true,
            ]
        );

        // Not specified
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'not_specified'],
            [
                'label' => ['en' => 'Not specified', 'fi' => 'Ei määritelty'],
                'extra' => ['type' => 'number', 'step' => '1', 'min' => 0, 'placeholder' => '0'],
                'sort' => 4,
                'is_active' => true,
            ]
        );
    }


    protected function seedB8Q3_MultiCountryGate(Disclosure $b8): void
    {
        if (!$b8) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b8->id, 'key' => 'b8.q3'],
            [
                'number' => 3,
                'type' => 'radio-cards',
                'title' => [
                    'en' => 'Does the company operate in more than one country?',
                    'fi' => 'Toimiiko yritys useammassa kuin yhdessä maassa?',
                ],
                'help_official' => [
                    'en' => 'Answer Yes if the undertaking has employees under employment contracts in multiple countries.',
                    'fi' => 'Vastaa Kyllä, jos yrityksellä on työntekijöitä useissa maissa.',
                ],
                'rules' => [
                    'choice' => ['required', 'in:yes,no'],
                ],
                'order' => 3,
                'is_active' => true,
                'meta' => [
                    'ui' => ['compact' => true],
                    'gate' => [
                        'if' => ['choice' => 'yes'],
                        'reveal_keys' => ['b8.q4'], // فقط وقتی Yes، سوال بعدی باز شود
                    ],
                ],
            ]
        );

        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'yes'],
            ['value' => 'yes', 'label' => ['en' => 'Yes', 'fi' => 'Kyllä'], 'sort' => 1, 'is_active' => true]
        );

        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            ['value' => 'no', 'label' => ['en' => 'No', 'fi' => 'Ei'], 'sort' => 2, 'is_active' => true]
        );
    }

    protected function seedB8Q4_EmployeesByCountry(Disclosure $b8): void
    {
        if (!$b8) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b8->id, 'key' => 'b8.q4'],
            [
                'number' => 4,
                'type' => 'repeatable-group',
                'title' => [
                    'en' => 'Employees by country (if operating in multiple countries)',
                    'fi' => 'Työntekijät maittain (jos toimintaa useissa maissa)',
                ],
                'help_official' => [
                    'en' => 'Select a country and enter the number of employees (headcount). Add rows for each additional country.',
                    'fi' => 'Valitse maa ja syötä työntekijöiden määrä. Lisää rivi jokaiselle maalle.',
                ],
                'rules' => [
                    'required' => true,
                    'array' => true,
                    'min' => 1,
                    'item_rules' => [
                        'country' => ['required', 'string', 'max:3'], // ISO code
                        'employees' => ['required', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 4,
                'is_active' => true,
                'meta' => [
                    'role' => 'employees_by_country',
                    // فقط وقتی Gate=Yes
                    'visible_if' => [
                        ['when' => ['key' => 'b8.q3', 'eq' => 'yes']],
                    ],
                    // UI hint: country select will be populated from Countries table on frontend
                    'sources' => [
                        'countries' => [
                            'value_key' => 'code',
                            'label_tpl' => '{{name}}',
                        ],
                    ],
                    'ui' => ['compact' => true],
                ],
            ]
        );

        // Country (select)
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'country'],
            [
                'label' => ['en' => 'Country', 'fi' => 'Maa'],
                'extra' => [
                    'type' => 'select',
                    'choices' => [], // در Front از Countries پر می‌شود
                    'placeholder' => 'Select a country…',
                ],
                'sort' => 1,
                'is_active' => true,
            ]
        );

        // Employees (numeric)
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'employees'],
            [
                'label' => ['en' => 'Employees (headcount)', 'fi' => 'Työntekijät (hlö)'],
                'extra' => [
                    'type' => 'number',
                    'step' => '1',
                    'min' => 0,
                    'placeholder' => '25',
                ],
                'sort' => 2,
                'is_active' => true,
            ]
        );
    }

    protected function seedB8Q5_EmployeesLeft(Disclosure $b8): void
    {
        if (!$b8) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b8->id, 'key' => 'b8.q5'],
            [
                'number' => 5,
                'type' => 'multi-input',
                'title' => [
                    'en' => 'How many employees have left the company (for any reason) in the previous reporting period?',
                    'fi' => 'Kuinka monta työntekijää on lähtenyt yrityksestä (mistä tahansa syystä) edellisellä raportointijaksolla?',
                ],
                'help_official' => [
                    'en' => 'Reporting period means the time between reports. If this is the first report, use the year before the expected release of the report.',
                    'fi' => 'Raportointijakso tarkoittaa raporttien välistä aikaa. Jos tämä on ensimmäinen raportti, käytä vuotta ennen raportin julkaisuajankohtaa.',
                ],
                'help_friendly' => [
                    'en' => 'Enter the number of employees who left (resignations, retirements, terminations, etc.)',
                    'fi' => 'Syötä lähteneiden työntekijöiden määrä (irtisanoutumiset, eläkkeelle siirtymiset, irtisanomiset jne.)',
                ],
                'rules' => [
                    'array' => true,
                    'required' => true,
                    'item_rules' => [
                        'employees_left' => ['required', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 5,
                'is_active' => true,
                'meta' => [
                    // در گزارش بررسی می‌کنیم اگر کارکنان ≥ 50 → نرخ ترک خدمت محاسبه شود
                    'calculation_hint' => 'Turnover rate = (employees_left / avg_employees) × 100',
                ],
            ]
        );

        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'employees_left'],
            [
                'label' => ['en' => 'Employees who left', 'fi' => 'Lähteneet työntekijät'],
                'extra' => [
                    'type' => 'number',
                    'step' => 1,
                    'min' => 0,
                    'placeholder' => '12',
                ],
                'sort' => 1,
                'is_active' => true,
            ]
        );
    }


    protected function seedB9Q1_WorkAccidents(Disclosure $b9): void
    {
        if (!$b9) return;

        $q = Question::updateOrCreate(
            ['disclosure_id' => $b9->id, 'key' => 'b9.q1'],
            [
                'number' => 1,
                'type' => 'multi-input',
                'title' => [
                    'en' => 'Work-related accidents and total hours worked (reporting period)',
                    'fi' => 'Työhön liittyvät tapaturmat ja kokonaistyötunnit (raportointijakso)',
                ],
                'help_official' => [
                    'en' => 'Provide the number of recordable work-related accidents and the total hours worked by all employees during the reporting period.',
                    'fi' => 'Ilmoita työhön liittyvien rekisteröitävien tapaturmien määrä sekä kaikkien työntekijöiden tekemien tuntien kokonaismäärä raportointijaksolla.',
                ],
                'help_friendly' => [
                    'en' => 'These values enable calculating the accident rate later in reporting.',
                    'fi' => 'Näiden arvojen avulla voidaan laskea tapaturmataajuus myöhemmin raportoinnissa.',
                ],
                'rules' => [
                    'array' => true,
                    'required' => true,
                    'item_rules' => [
                        'accidents_count' => ['required', 'numeric', 'min:0'],
                        'hours_worked_total' => ['required', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 1,
                'is_active' => true,
                'meta' => [
                    // hint برای مرحله گزارش‌سازی
                    'calculation_hint' => 'Accident rate = (accidents_count / hours_worked_total) × 200,000',
                    'ui' => ['compact' => true],
                ],
            ]
        );

        // Field: number of work-related accidents
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'accidents_count'],
            [
                'label' => [
                    'en' => 'Number of work-related accidents',
                    'fi' => 'Työhön liittyvien tapaturmien määrä',
                ],
                'extra' => [
                    'type' => 'number',
                    'step' => 1,
                    'min' => 0,
                    'placeholder' => '3',
                ],
                'sort' => 1,
                'is_active' => true,
            ]
        );

        // Field: total hours worked by all employees
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'hours_worked_total'],
            [
                'label' => [
                    'en' => 'Total hours worked by all employees',
                    'fi' => 'Kaikkien työntekijöiden tekemien tuntien kokonaismäärä',
                ],
                'extra' => [
                    'type' => 'number',
                    'step' => 'any',
                    'min' => 0,
                    'suffix' => 'h',
                    'placeholder' => '120000',
                ],
                'sort' => 2,
                'is_active' => true,
            ]
        );
    }

    protected function seedB9Q2_Fatalities(Disclosure $b9): void
    {
        if (!$b9) return;

        $q = Question::updateOrCreate(
            ['disclosure_id' => $b9->id, 'key' => 'b9.q2'],
            [
                'number' => 2,
                'type' => 'multi-input',
                'title' => [
                    'en' => 'How many fatalities have occurred over the reporting period as a result of work-related injuries or ill health?',
                    'fi' => 'Kuinka monta kuolemantapausta on tapahtunut raportointijaksolla työhön liittyvien tapaturmien tai työperäisten sairauksien seurauksena?',
                ],
                'help_official' => [
                    'en' => 'Provide the number of fatalities during the reporting period that resulted from work-related injuries or ill health.',
                    'fi' => 'Ilmoita raportointijaksolla tapahtuneiden työhön liittyvien kuolemantapausten määrä (tapaturma tai työperäinen sairaus).',
                ],
                'help_friendly' => [
                    'en' => 'Enter a whole number. If none, enter 0.',
                    'fi' => 'Syötä kokonaisluku. Jos ei yhtään, syötä 0.',
                ],
                'rules' => [
                    'array' => true,
                    'required' => true,
                    'item_rules' => [
                        'fatalities_count' => ['required', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 2,
                'is_active' => true,
                'meta' => ['ui' => ['compact' => true]],
            ]
        );

        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'fatalities_count'],
            [
                'label' => [
                    'en' => 'Number of work-related fatalities',
                    'fi' => 'Työhön liittyvien kuolemantapausten määrä',
                ],
                'extra' => [
                    'type' => 'number',
                    'step' => 1,
                    'min' => 0,
                    'placeholder' => '0',
                ],
                'sort' => 1,
                'is_active' => true,
            ]
        );
    }

    protected function seedB10Q1_MinWageCompliance(Disclosure $b10): void
    {
        if (!$b10) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b10->id, 'key' => 'b10.q1'],
            [
                'number' => 1,
                'type' => 'radio-cards',
                'title' => [
                    'en' => 'Do employees receive pay that is equal or above the applicable minimum wage (by law or through a collective bargaining agreement)?',
                    'fi' => 'Saavatko työntekijät vähintään lakisääteisen tai työehtosopimuksen mukaisen vähimmäispalkan?',
                ],
                'help_official' => [
                    'en' => 'Answer Yes if all employees receive at least the applicable minimum wage as determined by national law or collective bargaining agreements.',
                    'fi' => 'Vastaa Kyllä, jos kaikki työntekijät saavat vähintään lakisääteisen tai työehtosopimuksen mukaisen vähimmäispalkan.',
                ],
                'help_friendly' => [
                    'en' => 'If Yes, briefly explain how compliance is ensured (policy, audits, CBAs).',
                    'fi' => 'Jos Kyllä, kuvaile lyhyesti, miten noudattaminen varmistetaan (esim. käytännöt, auditoinnit, TES).',
                ],
                'rules' => [
                    'choice' => ['required', 'in:yes,no'],
                    'desc' => ['nullable', 'string', 'max:1000', 'required_if:choice,yes'],
                ],
                'order' => 1,
                'is_active' => true,
                'meta' => [
                    'ui' => [
                        'compact' => true,
                    ],
                ],
            ]
        );

        // YES
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'yes'],
            [
                'value' => 'yes',
                'label' => ['en' => 'Yes', 'fi' => 'Kyllä'],
                'sort' => 1,
                'is_active' => true,
                'extra' => [

                    'fields' => [[
                        'key' => 'desc',
                        'type' => 'textarea',
                        'label' => ['en' => 'Explain how compliance is ensured', 'fi' => 'Kuvaile, miten noudattaminen varmistetaan'],
                        'placeholder' => ['en' => 'national minimum wage policy, collective agreements, periodic audits…', 'fi' => 'esim. vähimmäispalkkapolitiikka, työehtosopimukset, säännölliset auditoinnit…'],
                        'max' => 1000,
                    ]],
                ],
            ]
        );

        // NO
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            [
                'value' => 'no',
                'label' => ['en' => 'No', 'fi' => 'Ei'],
                'sort' => 2,
                'is_active' => true,
                'extra' => [
                    'fields' => [],
                ],
            ]
        );
    }

    protected function seedB10Q2_GenderPayAvg(Disclosure $b10): void
    {
        if (!$b10) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b10->id, 'key' => 'b10.q2'],
            [
                'number' => 2,
                'type' => 'multi-input',
                'title' => [
                    'en' => 'Average gross hourly pay by gender (EUR/hour)',
                    'fi' => 'Keskimääräinen bruttopalkka tunnilta sukupuolen mukaan (EUR/h)',
                ],
                'help_official' => [
                    'en' => 'Provide average gross hourly pay for male and female employees. If your headcount is below 150, this disclosure may be omitted in reporting.',
                    'fi' => 'Anna miesten ja naisten keskimääräinen bruttopalkka tunnilta. Jos henkilöstömäärä on alle 150, tämän tiedon voi jättää raportoimatta.',
                ],
                'help_friendly' => [
                    'en' => 'Decimals are allowed. Unit: EUR per hour.',
                    'fi' => 'Desimaalit sallittu. Yksikkö: EUR per tunti.',
                ],
                'rules' => [
                    // اختیاری می‌گذاریم؛ در گزارش‌سازی شرط 150 نفر را اعمال می‌کنیم
                    'array' => true,
                    'required' => false,
                    'item_rules' => [
                        'avg_male_eur_per_h' => ['nullable', 'numeric', 'min:0'],
                        'avg_female_eur_per_h' => ['nullable', 'numeric', 'min:0'],
                    ],
                ],
                'order' => 2,
                'is_active' => true,
                'meta' => [
                    'ui' => [
                        'compact' => true,
                    ],
                    // می‌توانید از این اشاره استفاده کنید تا در UI نوتیس نرم نشان دهید
                    'note' => 'If headcount >= 150 (see B8 totals), this disclosure is required in reporting.',
                ],
            ]
        );

        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'avg_male_eur_per_h'],
            [
                'label' => ['en' => 'Average gross hourly pay — male (EUR/h)', 'fi' => 'Miehet — keskim. bruttopalkka (EUR/h)'],
                'extra' => [
                    'type' => 'number',
                    'step' => '0.01',
                    'min' => 0,
                    'suffix' => '€/h',
                    'placeholder' => '22.50',
                ],
                'sort' => 1,
                'is_active' => true,
            ]
        );

        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'avg_female_eur_per_h'],
            [
                'label' => ['en' => 'Average gross hourly pay — female (EUR/h)', 'fi' => 'Naiset — keskim. bruttopalkka (EUR/h)'],
                'extra' => [
                    'type' => 'number',
                    'step' => '0.01',
                    'min' => 0,
                    'suffix' => '€/h',
                    'placeholder' => '20.90',
                ],
                'sort' => 2,
                'is_active' => true,
            ]
        );
    }

    protected function seedB10Q3_CollectiveBargaining(Disclosure $b10): void
    {
        if (!$b10) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b10->id, 'key' => 'b10.q3'],
            [
                'number' => 3,
                'type'   => 'multi-input',
                'title'  => [
                    'en' => 'Employees covered by collective bargaining agreements',
                    'fi' => 'Yhteistyösopimusten piirissä olevien työntekijöiden määrä',
                ],
                'help_official' => [
                    'en' => 'Provide the number of employees covered by collective bargaining agreements during the reporting period.',
                    'fi' => 'Ilmoita raportointijakson aikana työehtosopimusten piirissä olevien työntekijöiden määrä.',
                ],
                'help_friendly' => [
                    'en' => 'Integer values only. If none, leave as 0.',
                    'fi' => 'Vain kokonaisluvut. Jos ei ole, jätä arvoksi 0.',
                ],
                'rules' => [
                    'array'      => true,
                    'required'   => false, // می‌توانید در مرحله گزارش الزام را اعمال کنید
                    'item_rules' => [
                        'employees_covered_cba' => ['required','integer','min:0'],
                    ],
                ],
                'order'     => 3,
                'is_active' => true,
                'meta'      => [
                    'ui' => ['compact' => true],
                ],
            ]
        );

        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'employees_covered_cba'],
            [
                'label' => [
                    'en' => 'Number of employees covered by collective bargaining agreements',
                    'fi' => 'Työehtosopimusten piirissä olevien työntekijöiden lukumäärä',
                ],
                'extra' => [
                    'type'        => 'number',
                    'step'        => '1',
                    'min'         => 0,
                    'placeholder' => '120',
                ],
                'sort'      => 1,
                'is_active' => true,
            ]
        );
    }

    protected function seedB10Q4_TrainingHoursByGender(Disclosure $b10): void
    {
        if (!$b10) return;

        $q = \App\Models\Question::updateOrCreate(
            ['disclosure_id' => $b10->id, 'key' => 'b10.q4'],
            [
                'number' => 4,
                'type'   => 'multi-input',
                'title'  => [
                    'en' => 'Average number of training hours per employee, by gender',
                    'fi' => 'Keskimääräiset koulutustunnit työntekijää kohden, sukupuolen mukaan',
                ],
                'help_official' => [
                    'en' => 'Report the average annual training hours per employee for male and female employees.',
                    'fi' => 'Ilmoita keskimääräiset vuotuiset koulutustunnit työntekijää kohden mies- ja naistyöntekijöille.',
                ],
                'help_friendly' => [
                    'en' => 'Use hours (h). Decimals allowed.',
                    'fi' => 'Käytä tunteja (h). Desimaalit sallittu.',
                ],
                'rules' => [
                    'array'      => true,
                    'required'   => false,
                    'item_rules' => [
                        'training_hours_male'   => ['required','numeric','min:0'],
                        'training_hours_female' => ['required','numeric','min:0'],
                    ],
                ],
                'order'     => 4,
                'is_active' => true,
                'meta'      => [
                    'ui' => ['compact' => true],
                ],
            ]
        );

        // Male
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'training_hours_male'],
            [
                'label' => [
                    'en' => 'Average number of training hours for male employees (h)',
                    'fi' => 'Miespuolisten työntekijöiden keskimääräinen koulutustuntimäärä (h)',
                ],
                'extra' => [
                    'type'        => 'number',
                    'step'        => 'any',
                    'min'         => 0,
                    'suffix'      => 'h',
                    'placeholder' => '12.5',
                ],
                'sort'      => 1,
                'is_active' => true,
            ]
        );

        // Female
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'field', 'key' => 'training_hours_female'],
            [
                'label' => [
                    'en' => 'Average number of training hours for female employees (h)',
                    'fi' => 'Naispuolisten työntekijöiden keskimääräinen koulutustuntimäärä (h)',
                ],
                'extra' => [
                    'type'        => 'number',
                    'step'        => 'any',
                    'min'         => 0,
                    'suffix'      => 'h',
                    'placeholder' => '11',
                ],
                'sort'      => 2,
                'is_active' => true,
            ]
        );
    }

    protected function seedB11Q1(Disclosure $b11): void
    {
        if (!$b11) return;

        $q = Question::updateOrCreate(
            ['disclosure_id' => $b11->id, 'key' => 'b11.q1'],
            [
                'number' => 1,
                'type'   => 'radio-cards',
                'title'  => [
                    'en' => 'Have there been convictions related to corruption or bribery?',
                    'fi' => 'Onko raportoitu tuomioita korruptiosta tai lahjonnasta?',
                ],
                'help_official' => [
                    'en' => 'If your company has been convicted of corruption or bribery during the reporting period, disclose the number of convictions and the total amount of fines incurred.',
                    'fi' => 'Jos yrityksenne on saanut tuomion korruptiosta tai lahjonnasta raportointijakson aikana, ilmoittakaa tuomioiden lukumäärä ja sakkojen kokonaismäärä.',
                ],
                'rules' => [
                    'choice' => ['required', 'in:yes,no'],
                    'convictions' => ['nullable', 'numeric', 'min:0', 'required_if:answers.b11.q1.choice,yes'],
                    'fines'       => ['nullable', 'numeric', 'min:0', 'required_if:answers.b11.q1.choice,yes'],
                ],
                'order'     => 1,
                'is_active' => true,
                'meta'      => [
                    'ui' => ['compact' => true],
                ],
            ]
        );

        // Option: Yes
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'yes'],
            [
                'value' => 'yes',
                'label' => ['en' => 'Yes', 'fi' => 'Kyllä'],
                'sort'  => 1,
                'is_active' => true,
                'extra' => [
                    'fields' => [
                        [
                            'type'  => 'number',
                            'key'   => 'convictions',
                            'label' => ['en' => 'How many convictions?', 'fi' => 'Kuinka monta tuomiota?'],
                            'min'   => 0,
                            'step'  => 1,
                        ],
                        [
                            'type'  => 'number',
                            'key'   => 'fines',
                            'label' => ['en' => 'Total amount of fines (EUR)', 'fi' => 'Sakkojen kokonaismäärä (€)'],
                            'min'   => 0,
                            'step'  => 'any',
                            'suffix'=> '€',
                        ],
                    ],
                ],
            ]
        );

        // Option: No
        \App\Models\QuestionOption::updateOrCreate(
            ['question_id' => $q->id, 'kind' => 'option', 'key' => 'no'],
            [
                'value' => 'no',
                'label' => ['en' => 'No', 'fi' => 'Ei'],
                'sort'  => 2,
                'is_active' => true,
            ]
        );
    }



}
