<div
    x-data="{
        /* ── Image Capture State ─────────────────────────────── */
        hasImage: false,

        /**
         * Called by the hidden file <input>.
         * Reads the file, compresses it to JPEG (max 1024px), encodes to base64,
         * then pushes the data into Livewire via $wire.receiveImage().
         */
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            const mimeType = file.type || 'image/jpeg';
            const reader   = new FileReader();

            reader.onload = (e) => {
                /* Compress via canvas to keep payload under ~1MB */
                const img = new Image();
                img.onload = () => {
                    const MAX   = 800;
                    let   w     = img.width;
                    let   h     = img.height;
                    if (w > MAX || h > MAX) {
                        const ratio = Math.min(MAX / w, MAX / h);
                        w = Math.round(w * ratio);
                        h = Math.round(h * ratio);
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width  = w;
                    canvas.height = h;
                    canvas.getContext('2d').drawImage(img, 0, 0, w, h);

                    const previewUrl  = canvas.toDataURL('image/jpeg', 0.70);
                    const base64Only  = previewUrl.split(',')[1];

                    this.hasImage = true;
                    $wire.receiveImage(base64Only, 'image/jpeg', previewUrl);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        /* Trigger the hidden file input */
        triggerGallery() {
            this.$refs.fileInput.click();
        },
        triggerCamera() {
            const input = this.$refs.cameraInput;
            if (input) input.click();
        }
    }"
>
    {{-- ═══════════════════════════════════════════════════════════════════════════
         CTA BUTTON — Always visible on home screen (rendered by service-grid.blade)
         ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- This component only renders its modals. The CTA pill is embedded in service-grid.blade.php --}}


    {{-- ═══════════════════════════════════════════════════════════════════════════
         MODAL 1 — Image Capture Overlay
         ═══════════════════════════════════════════════════════════════════════════ --}}
    @if($showCaptureModal)
    <div class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center"
         wire:click.self="closeCaptureModal">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

        {{-- Sheet --}}
        <div class="relative w-full sm:max-w-md mx-auto bg-gradient-to-b from-[#1e0b35] to-[#120720] rounded-t-3xl sm:rounded-3xl border border-white/10 shadow-2xl max-h-[92dvh] overflow-y-auto">

            {{-- Handle --}}
            <div class="flex justify-center pt-3 sm:hidden">
                <div class="w-10 h-1 bg-white/20 rounded-full"></div>
            </div>

            {{-- Header --}}
            <div class="px-6 pt-5 pb-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    {{-- Animated camera icon --}}
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-orange-500 to-violet-600 flex items-center justify-center shadow-lg shadow-orange-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                            <circle cx="12" cy="13" r="4" stroke-width="1.8"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold font-[Outfit] text-base">
                            {{ app()->getLocale() === 'ar' ? 'صور مشكلتك' : 'Picture Your Problem' }}
                        </h3>
                        <p class="text-white/50 text-xs mt-0.5">
                            {{ app()->getLocale() === 'ar' ? 'الذكاء الاصطناعي سيشخّص المشكلة' : 'AI will diagnose the issue' }}
                        </p>
                    </div>
                </div>
                <button wire:click="closeCaptureModal"
                    class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all">
                    <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-6 pb-8 space-y-5">
                {{-- ── Image Preview / Upload Zone ─────────────────────────── --}}
                <div class="relative">
                    {{-- Preview if image selected --}}
                    @if($imagePreview)
                        <div class="relative w-full h-52 rounded-2xl overflow-hidden border border-white/10">
                            <img src="{{ $imagePreview }}" alt="Preview" class="w-full h-full object-cover">
                            {{-- Re-select overlay --}}
                            <button @click="triggerGallery()"
                                class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                <span class="text-white text-sm font-semibold bg-black/50 px-4 py-2 rounded-xl">
                                    {{ app()->getLocale() === 'ar' ? 'تغيير الصورة' : 'Change Image' }}
                                </span>
                            </button>
                        </div>
                    @else
                        {{-- Empty upload zone --}}
                        <button @click="triggerGallery()"
                            class="w-full h-52 rounded-2xl border-2 border-dashed border-white/20 hover:border-orange-400/60 bg-white/5 hover:bg-white/8 flex flex-col items-center justify-center gap-3 transition-all group">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500/20 to-violet-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7 text-white/50 group-hover:text-orange-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-white/70 font-semibold text-sm">
                                    {{ app()->getLocale() === 'ar' ? 'اضغط لاختيار صورة' : 'Tap to select image' }}
                                </p>
                                <p class="text-white/30 text-xs mt-1">
                                    {{ app()->getLocale() === 'ar' ? 'أو التقط صورة جديدة' : 'or take a new photo' }}
                                </p>
                            </div>
                        </button>
                    @endif

                    {{-- Hidden file inputs --}}
                    <input x-ref="fileInput" type="file" accept="image/*"
                        class="hidden" @change="handleFileSelect($event)">
                    <input x-ref="cameraInput" type="file" accept="image/*" capture="environment"
                        class="hidden" @change="handleFileSelect($event)">
                </div>

                {{-- Camera vs Gallery quick-pick (mobile) --}}
                @if(!$imagePreview)
                <div class="grid grid-cols-2 gap-3">
                    <button @click="triggerCamera()"
                        class="flex items-center justify-center gap-2 py-3 rounded-2xl bg-white/8 hover:bg-white/12 border border-white/10 text-white/80 text-sm font-semibold transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                            <circle cx="12" cy="13" r="4" stroke-width="1.8"/>
                        </svg>
                        {{ app()->getLocale() === 'ar' ? 'الكاميرا' : 'Camera' }}
                    </button>
                    <button @click="triggerGallery()"
                        class="flex items-center justify-center gap-2 py-3 rounded-2xl bg-white/8 hover:bg-white/12 border border-white/10 text-white/80 text-sm font-semibold transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ app()->getLocale() === 'ar' ? 'المعرض' : 'Gallery' }}
                    </button>
                </div>
                @endif

                {{-- ── Optional Context Input ───────────────────────────────── --}}
                <div wire:loading.remove wire:target="analyzeImage">
                    <label class="text-white/60 text-xs font-semibold mb-2 block">
                        {{ app()->getLocale() === 'ar' ? 'أضف وصفاً (اختياري)' : 'Add a description (optional)' }}
                    </label>
                    <textarea wire:model="userContext" rows="2"
                        placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: تسرب مياه من تحت الحوض منذ يومين...' : 'e.g. Water leaking under sink for 2 days...' }}"
                        class="w-full px-4 py-3 rounded-2xl bg-white/8 border border-white/10 focus:border-orange-400/60 focus:ring-1 focus:ring-orange-400/30 text-white placeholder-white/30 text-sm resize-none outline-none transition-all leading-relaxed"
                        dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"></textarea>
                </div>

                {{-- ── Analyzing State (AlKifah Avatar) ─────────────────────── --}}
                <div wire:loading.flex wire:target="analyzeImage" class="flex-col items-center justify-center gap-3 py-5 rounded-2xl bg-linear-to-b from-[#1e0b35] to-[#120720] border border-orange-500/20 shadow-xl shadow-orange-500/10 transition-all w-full">
                    <div class="relative">
                        <div class="w-16 h-16 rounded-full bg-linear-to-br from-orange-500 to-violet-600 p-[2px] shadow-lg shadow-orange-500/30">
                            <div class="w-full h-full rounded-full bg-[#120720] flex items-center justify-center overflow-hidden">
                                <svg class="w-9 h-9 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <span class="absolute -bottom-1 -right-1 flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-[#1e0b35]"></span>
                        </span>
                    </div>

                    <div class="text-center px-4 mt-2">
                        <h4 class="text-orange-400 font-bold font-[Outfit] text-lg mb-1.5 flex items-center justify-center gap-2">
                            <span dir="rtl">⏳ جاري التحليل بالذكاء الاصطناعي...</span>
                            <svg class="w-4 h-4 animate-spin text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                        </h4>
                        <div class="inline-block bg-white/10 text-white/80 text-sm leading-relaxed rounded-2xl rounded-tr-sm px-4 py-3 border border-white/10 mt-1 text-right" dir="rtl">
                            🔍 قاعد نحلّل صورتك... الأسعار والتوصيات المعروضة هي <strong class="text-orange-300">تقديرية فقط</strong>، وسيؤكدها الفني عند الزيارة.
                        </div>
                        <p class="text-white/30 text-[10px] mt-2">AI is analyzing your image — estimates may vary</p>
                    </div>
                </div>

                {{-- ── Error Alert ──────────────────────────────────────────── --}}
                @if($analyzeError)
                <div class="flex items-start gap-3 p-4 rounded-2xl bg-red-500/10 border border-red-500/20">
                    <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-red-300 text-sm">{{ $analyzeError }}</p>
                </div>
                @endif

                {{-- ── Analyze Button ───────────────────────────────────────── --}}
                <div wire:loading.remove wire:target="analyzeImage" class="w-full">
                    <button wire:click="analyzeImage"
                        @disabled(!$imagePreview)
                        class="w-full py-4 rounded-2xl font-bold text-base transition-all
                            {{ $imagePreview
                                ? 'bg-linear-to-r from-orange-500 to-violet-600 text-white shadow-lg shadow-orange-500/30 hover:shadow-orange-500/50 hover:scale-[1.02] active:scale-[0.98]'
                                : 'bg-white/10 text-white/30 cursor-not-allowed' }}">
                        <span class="flex items-center justify-center gap-2">
                            {{-- Sparkle icon --}}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M5 3l1.5 4.5L11 9l-4.5 1.5L5 15l-1.5-4.5L-.5 9l4.5-1.5L5 3zM19 11l1 3 3 1-3 1-1 3-1-3-3-1 3-1 1-3z"/>
                            </svg>
                            {{ app()->getLocale() === 'ar' ? 'حلّل المشكلة بالذكاء الاصطناعي' : 'Analyze with AI' }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif


    {{-- ═══════════════════════════════════════════════════════════════════════════
         MODAL 2 — AI Result Bottom Sheet
         ═══════════════════════════════════════════════════════════════════════════ --}}
    @if($showResultSheet && !empty($diagnosisResult))
    @php
        $d          = $diagnosisResult;
        $isAr       = app()->getLocale() === 'ar';
        $title      = $isAr ? ($d['issue_title_ar'] ?? '') : ($d['issue_title_en'] ?? $d['issue_title_ar'] ?? '');
        $message    = $d['dialogue_message'] ?? '';
        $urgency    = $d['urgency_suggestion'] ?? 'scheduled';
        $parts      = $d['suggested_parts'] ?? [];
        $budget     = $d['budget_estimate'] ?? [];
        $isUnclear  = $d['is_unclear'] ?? false;
        $confidence = (int)(($d['confidence_score'] ?? 0) * 100);
        $svcNameAr  = $d['service_name_ar'] ?? '';
        $svcNameEn  = $d['service_name_en'] ?? '';
        $svcColor   = $d['service_color'] ?? '#7c3aed';
    @endphp

    <div class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/75 backdrop-blur-sm" wire:click="closeResultSheet"></div>

        {{-- Sheet --}}
        <div class="relative w-full sm:max-w-lg mx-auto bg-gradient-to-b from-[#1e0b35] to-[#0f0620] rounded-t-3xl sm:rounded-3xl border border-white/10 shadow-2xl max-h-[92dvh] overflow-y-auto">

            {{-- Handle --}}
            <div class="flex justify-center pt-3 sm:hidden">
                <div class="w-10 h-1 bg-white/20 rounded-full"></div>
            </div>

            {{-- ── Header: AI Branding ──────────────────────────────────────── --}}
            <div class="px-6 pt-5 pb-4 border-b border-white/8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        {{-- AI Brain icon --}}
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-violet-500 to-orange-500 flex items-center justify-center shadow-lg shadow-violet-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-white font-bold font-[Outfit] text-base">
                                    {{ $isAr ? 'تشخيص الذكاء الاصطناعي' : 'AI Diagnosis' }}
                                </h3>
                                {{-- Confidence badge --}}
                                @if($confidence > 0)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                    {{ $confidence >= 70 ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                                    {{ $confidence }}%
                                </span>
                                @endif
                            </div>
                            <p class="text-white/40 text-xs mt-0.5">Powered by Google Gemini</p>
                        </div>
                    </div>
                    <button wire:click="closeResultSheet"
                        class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all">
                        <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-5 space-y-5">

                {{-- ── Unclear Image Warning ─────────────────────────────────── --}}
                @if($isUnclear)
                <div class="flex items-start gap-3 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20">
                    <svg class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-amber-200 text-sm leading-relaxed">
                        {{ $isAr
                            ? 'لم تكن الصورة واضحة بما يكفي. النتيجة أدناه تقديرية. ننصح بالتقاط صورة أوضح للحصول على نتائج أدق.'
                            : 'The image was not clear enough. The result below is an estimate. We recommend taking a clearer photo.' }}
                    </p>
                </div>
                @endif

                {{-- ── Diagnosed Issue Card ─────────────────────────────────── --}}
                <div class="p-5 rounded-2xl border border-white/10"
                     style="background: linear-gradient(135deg, {{ $svcColor }}22 0%, {{ $svcColor }}08 100%);">
                    {{-- Service Category Badge --}}
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs font-bold px-3 py-1 rounded-full text-white"
                              style="background: {{ $svcColor }};">
                            {{ $isAr ? $svcNameAr : $svcNameEn }}
                        </span>
                        {{-- Urgency badge --}}
                        <span class="text-xs font-bold px-3 py-1 rounded-full
                            {{ $urgency === 'urgent' ? 'bg-red-500/20 text-red-300 border border-red-500/20' : 'bg-blue-500/20 text-blue-300 border border-blue-500/20' }}">
                            @if($urgency === 'urgent')
                                ⚡ {{ $isAr ? 'عاجل' : 'Urgent' }}
                            @else
                                📅 {{ $isAr ? 'مجدول' : 'Scheduled' }}
                            @endif
                        </span>
                    </div>

                    {{-- Issue Title --}}
                    <h4 class="text-white font-bold font-[Outfit] text-xl mb-3 leading-snug" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
                        {{ $title }}
                    </h4>

                    {{-- AI Message (friendly explanation) --}}
                    <p class="text-white/70 text-sm leading-relaxed" dir="rtl">{{ $message }}</p>
                </div>

                {{-- ── Budget Estimate ──────────────────────────────────────── --}}
                @if(!empty($budget))
                <div class="p-5 rounded-2xl bg-emerald-500/8 border border-emerald-500/20">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                        <span class="text-emerald-300 text-sm font-semibold">
                            {{ $isAr ? 'التكلفة التقديرية' : 'Estimated Cost' }}
                        </span>
                        {{-- Phase 2 placeholder indicator --}}
                        <span class="text-[10px] text-white/30 border border-white/10 px-1.5 py-0.5 rounded-full">
                            {{ $isAr ? 'تقريبي' : 'approx' }}
                        </span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black font-[Outfit] text-white">
                            {{ number_format($budget['min_sar'] ?? 0) }}
                        </span>
                        <span class="text-white/50 text-lg font-bold">—</span>
                        <span class="text-3xl font-black font-[Outfit] text-white">
                            {{ number_format($budget['max_sar'] ?? 0) }}
                        </span>
                        <span class="text-white/60 text-sm font-semibold">{{ $isAr ? 'ريال' : 'SAR' }}</span>
                    </div>
                    @if(!empty($budget['note_ar']))
                    <p class="text-white/40 text-xs mt-2 leading-relaxed" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
                        {{ $isAr ? $budget['note_ar'] : ($budget['note_en'] ?? $budget['note_ar']) }}
                    </p>
                    @endif
                </div>
                @endif

                {{-- ── Suggested Parts ──────────────────────────────────────── --}}
                @if(!empty($parts))
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3" stroke-width="2"/>
                        </svg>
                        <span class="text-orange-300 text-sm font-semibold">
                            {{ $isAr ? 'المواد المقترحة' : 'Suggested Materials' }}
                        </span>
                    </div>
                    <div class="space-y-2">
                        @foreach($parts as $part)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/8">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-orange-400 shrink-0"></div>
                                <span class="text-white/80 text-sm" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
                                    {{ $isAr ? ($part['part_name_ar'] ?? '') : ($part['part_name_en'] ?? $part['part_name_ar'] ?? '') }}
                                </span>
                            </div>
                            {{-- Phase 2: will show store price when store_sku is connected --}}
                            @if(!empty($part['estimated_unit_price_sar']))
                            <span class="text-emerald-300 text-xs font-bold">
                                ~{{ number_format($part['estimated_unit_price_sar']) }} {{ $isAr ? 'ر' : 'SAR' }}
                            </span>
                            @else
                            <span class="text-white/25 text-[10px]">
                                {{ $isAr ? 'السعر قريباً' : 'Price soon' }}
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ── Action Buttons ───────────────────────────────────────── --}}
                <div class="sticky bottom-0 bg-gradient-to-t from-[#0f0620] via-[#0f0620] to-transparent pt-4 pb-6 -mx-6 px-6 mt-2">
                    <div class="grid grid-cols-2 gap-3">
                        {{-- Cancel --}}
                        <button wire:click="closeResultSheet"
                            class="py-4 rounded-2xl bg-white/8 hover:bg-white/12 border border-white/10 text-white/80 font-bold text-sm transition-all active:scale-95">
                            {{ $isAr ? 'إلغاء' : 'Cancel' }}
                        </button>

                        {{-- Confirm & Route --}}
                        <button wire:click="confirmAndRoute"
                            class="py-4 rounded-2xl bg-gradient-to-r from-orange-500 to-violet-600 text-white font-bold text-sm shadow-lg shadow-orange-500/30 hover:shadow-orange-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all leading-tight text-center">
                            <span class="block">{{ $isAr ? 'تأكيد وطلب الخدمة' : 'Confirm & Request' }}</span>
                        </button>
                    </div>

                    <p class="text-center text-white/30 text-[10px] mt-3">
                        {{ $isAr
                            ? '* التشخيص تقريبي. الفني سيقيّم الحالة عند الوصول.'
                            : '* Diagnosis is approximate. Technician will assess on-site.' }}
                    </p>
                </div>

            </div>
        </div>
    </div>
    @endif

</div>
