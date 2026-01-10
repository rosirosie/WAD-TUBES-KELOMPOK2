<div class="bg-red-50 rounded-xl shadow-sm border-l-8 border-red-600 p-6 relative overflow-hidden transition-all duration-500">
            
    <div class="absolute -right-6 -top-6 opacity-10">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-24 h-24 text-red-600">
            <path fill-rule="evenodd" d="M5.25 9a6.75 6.75 0 0113.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 01-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 11-7.48 0 24.585 24.585 0 01-4.831-1.244.75.75 0 01-.298-1.205A8.217 8.217 0 005.25 9.75V9zm4.502 8.9a2.25 2.25 0 104.496 0 25.057 25.057 0 01-4.496 0z" clip-rule="evenodd" />
            </svg>
    </div>

    <div class="flex items-start relative z-10">
        <div class="flex-shrink-0 bg-red-100 p-3 rounded-full border-2 border-red-200">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-red-600">
                <path fill-rule="evenodd" d="M12 2.25a.75.75 0 01.75.75v.756a49.106 49.106 0 019.152 1 .75.75 0 01-.152 1.485h-1.918l2.474 10.124a.75.75 0 01-.375.84A6.723 6.723 0 0112 18a6.723 6.723 0 01-9.931-1.795.75.75 0 01-.375-.84l2.474-10.124H2.25a.75.75 0 01-.152-1.485 49.106 49.106 0 019.152-1V3a.75.75 0 01.75-.75zm4.878 13.5a5.25 5.25 0 00-9.756 0L4.5 4.5h15l-2.622 11.25zm-6.628 2.75a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-1.75a.75.75 0 01.75-.75z" clip-rule="evenodd" />
            </svg>
        </div>
        
        <div class="ml-5 w-full">
            <div class="flex items-center gap-2 mb-2">
                <span class="bg-red-600 text-white text-xs font-bold uppercase px-3 py-1 rounded-full tracking-wide">
                    {{ $announcement->type ?? 'PENTING' }}
                </span>
                <h3 class="font-bold text-xl text-red-900 tracking-tight">
                    {{ $announcement->title }}
                </h3>
            </div>
            <p class="text-red-800 text-base font-medium leading-relaxed">
                {{ $announcement->content }}
            </p>
            <p class="text-red-400 text-xs mt-3 text-right italic">
                Diposting: {{ $announcement->created_at->diffForHumans() }}
            </p>
        </div>
    </div>
</div>