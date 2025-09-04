<x-dropdown label="{{ __('Language') }}" class="btn-sm">
    <x-menu-item as="a" href="{{ route('locale.switch','en') }}" title="English" :active="app()->getLocale()==='en'" />
    <x-menu-item as="a" href="{{ route('locale.switch','fi') }}" title="Finnish" :active="app()->getLocale()==='fi'" />
</x-dropdown>
