<div class="lg:hidden fixed bottom-8 left-6 right-6 z-[100] animate-in fade-in slide-in-from-bottom-10 duration-700">
    <div class="bg-slate-900/90 backdrop-blur-xl shadow-2xl rounded-3xl p-4 flex items-center justify-around border border-white/10">
        <a href="/" class="flex flex-col items-center gap-1.5 {{ request()->is('/') ? 'text-[#006d5b]' : 'text-slate-400' }} hover:text-white transition-all">
            <i class="ti ti-smart-home text-2xl"></i>
            <span class="text-[8px] font-black uppercase tracking-[0.2em]">Home</span>
        </a>
        <a href="{{ route('account') }}" class="flex flex-col items-center gap-1.5 {{ request()->is('account*') ? 'text-[#006d5b]' : 'text-slate-400' }} hover:text-white transition-all">
            <i class="ti ti-package text-2xl"></i>
            <span class="text-[8px] font-black uppercase tracking-[0.2em]">Orders</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1.5 {{ request()->is('profile*') ? 'text-[#006d5b]' : 'text-slate-400' }} hover:text-white transition-all">
            <i class="ti ti-user-circle text-2xl"></i>
            <span class="text-[8px] font-black uppercase tracking-[0.2em]">Profile</span>
        </a>
        <a href="https://wa.me/6281234567890" target="_blank" class="flex flex-col items-center gap-1.5 text-slate-400 hover:text-emerald-400 transition-all">
            <i class="ti ti-brand-whatsapp text-2xl"></i>
            <span class="text-[8px] font-black uppercase tracking-[0.2em]">Chat</span>
        </a>
    </div>
</div>
