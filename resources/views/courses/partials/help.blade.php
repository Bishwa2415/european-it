{{-- Blue band above the footer: call us / join the Facebook community --}}
<section class="bg-[#B6EAFF] px-5 py-[60px] lg:py-[120px]">
    <div class="mx-auto grid max-w-[990px] gap-6 md:grid-cols-2">
        <div class="course-help-card">
            <h3 class="course-help-title">{{ $page['help_title'] }}</h3>
            <p class="course-help-text">{{ $page['help_text'] }}</p>
            <div class="mt-[27px] flex items-center justify-center gap-2.5">
                <span class="flex h-[45px] w-[50px] items-center justify-center rounded-md bg-[#25D366] text-2xl text-white"><i class="bi bi-whatsapp"></i></span>
                <a href="tel:{{ preg_replace('/[^\d+]/', '', $page['help_phone']) }}" class="rounded-md bg-white px-3 py-[9px] text-lg leading-7 font-bold text-[#268E2E]">{{ $page['help_phone'] }}</a>
            </div>
        </div>

        <div class="course-help-card">
            <h3 class="course-help-title">{{ $page['community_title'] }}</h3>
            <p class="course-help-text">{{ $page['community_text'] }}</p>
            <div class="mt-[27px] flex items-center justify-center gap-2.5">
                <span class="flex h-[41px] w-[50px] items-center justify-center rounded-md bg-[#0866FF] text-2xl text-white"><i class="bi bi-facebook"></i></span>
                <a href="{{ $page['community_url'] }}" target="_blank" rel="noopener" class="rounded-[5px] bg-[#0073E6] px-5 py-[9px] text-white transition hover:bg-[#005BB8]">{{ $page['community_label'] }}</a>
            </div>
        </div>
    </div>
</section>
