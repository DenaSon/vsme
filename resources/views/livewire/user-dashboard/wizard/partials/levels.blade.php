@php $active = $active ?? 1; @endphp
<div class="mt-1 flex flex-wrap gap-1">
    @foreach (range(1,8) as $lvl)
        <span class="badge badge-ghost badge-xs px-2 py-1
             {{ $lvl == $active ? 'bg-primary/10 text-primary font-semibold border border-primary/30' : 'text-base-content/70' }}">
    Level {{ $lvl }}
</span>
    @endforeach
</div>
