<div class="flex items-center justify-between gap-4 p-3 border-b border-base-200">
    <!-- Breadcrumbs -->
    <nav class="breadcrumbs text-sm text-gray-600">
        <ul class="flex gap-2">
            <li><a href="#" class="hover:text-gray-800 transition-colors">{{ __('ui.dashboard') }}</a></li>
            <li><a href="#" class="hover:text-gray-800 transition-colors">{{ __('ui.survey') }}</a></li>
            <li class="text-gray-400 font-medium">{{ __('ui.vsme_survey') }}</li>
            <li class="text-gray-400 font-thin">
                {{ $this->currentDisclosureTitle  }}
            </li>
        </ul>
    </nav>

    <!-- Auto answer by AI -->
    <div class="flex items-center gap-2 text-xs bg-gray-100 px-2.5 py-1 rounded-full border border-gray-200">
        <span class="text-gray-600 font-medium">{{ __('ui.auto_answer_by_ai') }}</span>
        <x-heroicon-o-pause class="w-4 h-4 text-gray-500"/>
    </div>
</div>
