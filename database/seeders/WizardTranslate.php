<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\TranslationLoader\LanguageLine;

class WizardTranslate extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->wizardText() as $t) {
            LanguageLine::updateOrCreate(
                ['group' => $t['group'], 'key' => $t['key']],
                ['text' => $t['text']]
            );
        }
    }

    protected function wizardText(): array
    {
        return [
            ['group' => 'ui', 'key' => 'start', 'text' => ['en' => 'Start', 'fi' => 'Aloita']],
            ['group' => 'ui', 'key' => 'next', 'text' => ['en' => 'Next', 'fi' => 'Seuraava']],
            ['group' => 'ui', 'key' => 'back', 'text' => ['en' => 'Back', 'fi' => 'Takaisin']],
            ['group' => 'ui', 'key' => 'finish', 'text' => ['en' => 'Finish', 'fi' => 'Valmis']],
            ['group' => 'ui', 'key' => 'save', 'text' => ['en' => 'Save', 'fi' => 'Tallenna']],
            ['group' => 'ui', 'key' => 'cancel', 'text' => ['en' => 'Cancel', 'fi' => 'Peruuta']],
            ['group' => 'ui', 'key' => 'continue', 'text' => ['en' => 'Continue', 'fi' => 'Jatka']],
            ['group' => 'ui', 'key' => 'skip', 'text' => ['en' => 'Skip', 'fi' => 'Ohita']],
            ['group' => 'ui', 'key' => 'retry', 'text' => ['en' => 'Retry', 'fi' => 'Yritä uudelleen']],
            ['group' => 'ui', 'key' => 'submit', 'text' => ['en' => 'Submit', 'fi' => 'Lähetä']],
            ['group' => 'ui', 'key' => 'previous', 'text' => ['en' => 'Previous', 'fi' => 'Edellinen']],
            ['group' => 'ui', 'key' => 'done', 'text' => ['en' => 'Done', 'fi' => 'Valmis']],
            ['group' => 'ui', 'key' => 'confirm', 'text' => ['en' => 'Confirm', 'fi' => 'Vahvista']],
            ['group' => 'ui', 'key' => 'close', 'text' => ['en' => 'Close', 'fi' => 'Sulje']],
            ['group' => 'ui', 'key' => 'exit', 'text' => ['en' => 'Exit', 'fi' => 'Poistu']],
            ['group' => 'ui', 'key' => 'help', 'text' => ['en' => 'Help', 'fi' => 'Ohje']],
            ['group' => 'ui', 'key' => 'upload', 'text' => ['en' => 'Upload', 'fi' => 'Lataa']],
            ['group' => 'ui', 'key' => 'classified_information', 'text' => ['en' => 'Classified Information', 'fi' => 'Luokiteltu tieto']],

            ['group' => 'ui', 'key' => 'dashboard', 'text' => ['en' => 'Dashboard', 'fi' => 'Hallintapaneeli']],
            ['group' => 'ui', 'key' => 'survey', 'text' => ['en' => 'Survey', 'fi' => 'Kysely']],
            ['group' => 'ui', 'key' => 'vsme_survey', 'text' => ['en' => 'VSME Survey', 'fi' => 'VSME-kysely']],
            ['group' => 'ui', 'key' => 'auto_answer_by_ai', 'text' => ['en' => 'Auto answer by AI', 'fi' => 'Automaattinen vastaus AI:lla']],

            ['group' => 'ui', 'key' => 'edit', 'text' => ['en' => 'Edit', 'fi' => 'Muokkaa']],
            ['group' => 'ui', 'key' => 'delete', 'text' => ['en' => 'Delete', 'fi' => 'Poista']],
            ['group' => 'ui', 'key' => 'view', 'text' => ['en' => 'View', 'fi' => 'Näytä']],
            ['group' => 'ui', 'key' => 'search', 'text' => ['en' => 'Search', 'fi' => 'Haku']],
            ['group' => 'ui', 'key' => 'filter', 'text' => ['en' => 'Filter', 'fi' => 'Suodata']],
            ['group' => 'ui', 'key' => 'reset', 'text' => ['en' => 'Reset', 'fi' => 'Nollaa']],
            ['group' => 'ui', 'key' => 'download', 'text' => ['en' => 'Download', 'fi' => 'Lataa']],
            ['group' => 'ui', 'key' => 'upload_file', 'text' => ['en' => 'Upload File', 'fi' => 'Lataa tiedosto']],
            ['group' => 'ui', 'key' => 'select', 'text' => ['en' => 'Select', 'fi' => 'Valitse']],
            ['group' => 'ui', 'key' => 'cancel_changes', 'text' => ['en' => 'Cancel Changes', 'fi' => 'Peruuta muutokset']],
            ['group' => 'ui', 'key' => 'apply', 'text' => ['en' => 'Apply', 'fi' => 'Käytä']],
            ['group' => 'ui', 'key' => 'settings', 'text' => ['en' => 'Settings', 'fi' => 'Asetukset']],
            ['group' => 'ui', 'key' => 'profile', 'text' => ['en' => 'Profile', 'fi' => 'Profiili']],
            ['group' => 'ui', 'key' => 'logout', 'text' => ['en' => 'Logout', 'fi' => 'Kirjaudu ulos']],
            ['group' => 'ui', 'key' => 'notifications', 'text' => ['en' => 'Notifications', 'fi' => 'Ilmoitukset']],
            ['group' => 'ui', 'key' => 'messages', 'text' => ['en' => 'Messages', 'fi' => 'Viestit']],
            ['group' => 'ui', 'key' => 'home', 'text' => ['en' => 'Home', 'fi' => 'Koti']],
            ['group' => 'ui', 'key' => 'welcome', 'text' => ['en' => 'Welcome', 'fi' => 'Tervetuloa']],
            ['group' => 'ui', 'key' => 'language', 'text' => ['en' => 'Language', 'fi' => 'Kieli']],
            ['group' => 'ui', 'key' => 'notifications_settings', 'text' => ['en' => 'Notification Settings', 'fi' => 'Ilmoitusasetukset']],
            ['group' => 'ui', 'key' => 'question', 'text' => ['en' => 'Question', 'fi' => 'Kysymys']],
            ['group' => 'ui', 'key' => 'level', 'text' => ['en' => 'Level', 'fi' => 'Taso']],

            ['group' => 'ui', 'key' => 'attach_files', 'text' => ['en' => 'Attach files', 'fi' => 'Liitä tiedostot']],
            ['group' => 'ui', 'key' => 'attach_files_limit', 'text' => ['en' => 'You can attach up to :n files.', 'fi' => 'Voit liittää enintään :n tiedostoa.']],
            ['group' => 'ui', 'key' => 'files_uploaded', 'text' => ['en' => 'Files uploaded.', 'fi' => 'Tiedostot ladattu']],



        ];
    }
}
