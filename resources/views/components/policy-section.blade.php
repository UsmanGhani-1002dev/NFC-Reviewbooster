@props(['number', 'title'])
 
<div class="scroll-mt-24" id="section-{{ $number }}">
    <div class="flex items-center gap-4 mb-5">
        <span class="flex-shrink-0 w-9 h-9 bg-slate-900 text-white rounded-full flex items-center justify-center text-sm font-bold">
            {{ $number }}
        </span>
        <h2 class="text-xl md:text-2xl font-bold text-slate-900">{{ $title }}</h2>
    </div>
    <div class="pl-[3.25rem] prose-custom text-slate-600 leading-relaxed text-[15px] space-y-3">
        {{ $slot }}
    </div>
</div>