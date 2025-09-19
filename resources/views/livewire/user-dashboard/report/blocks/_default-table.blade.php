<section class="card bg-base-100 shadow">
    <div class="card-body">
        <h3 class="card-title">
            {{ strtoupper(data_get($block,'code')) }}
            <span class="text-base-content/70 font-normal">— {{ data_get($block,'title') }}</span>
        </h3>

        <div class="mt-4 overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                <tr class="text-xs uppercase">
                    <th class="w-40">{{ __('Question Key') }}</th>
                    <th>{{ __('Label') }}</th>
                    <th class="w-1/3">{{ __('Answer') }}</th>
{{--                    <th class="w-28 text-center">{{ __('Flags') }}</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach(data_get($block,'items',[]) as $item)
                    @php($val = data_get($item,'value'))
                    @php($flags = data_get($item,'flags',[]))
                    <tr>
                        <td class="align-top text-xs text-base-content/60">{{ data_get($item,'question_key') }}</td>
                        <td class="align-top font-medium">{{ data_get($item,'label') }}</td>
                        <td class="align-top text-sm">
                            @if(is_array($val))
                                @if(Arr::isAssoc($val))
                                    <pre class="whitespace-pre-wrap text-xs bg-base-200 rounded p-2">
{{ json_encode($val, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) }}</pre>
                                @else
                                    <ul class="list-disc ms-5">
                                        @foreach($val as $v)
                                            <li>{{ is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $v }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            @else
                                <div>{{ $val ?? '—' }}</div>
                            @endif
                        </td>
{{--                        <td class="align-top text-center">--}}
{{--                            <div class="flex flex-col items-center gap-1">--}}
{{--                                @if(data_get($flags,'classified')) <span class="badge badge-warning">🔒 {{ __('Classified') }}</span> @endif--}}
{{--                                @if(data_get($flags,'na'))         <span class="badge badge-ghost">{{ __('N/A') }}</span> @endif--}}
{{--                                @if(data_get($flags,'skipped'))    <span class="badge badge-neutral">{{ __('Skipped') }}</span> @endif--}}
{{--                                @if(!data_get($flags,'classified') && !data_get($flags,'na') && !data_get($flags,'skipped'))--}}
{{--                                    <span class="badge badge-ghost">—</span>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </td>--}}
                    </tr>
                @endforeach

                @if(!count(data_get($block,'items',[])))
                    <tr>
                        <td colspan="4" class="text-center text-sm py-6 opacity-70">{{ __('No items to display.') }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</section>
