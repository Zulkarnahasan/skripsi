@extends('layouts.user')

@section('user-content')
<style>
    .test-question-nav {
        position: sticky;
        top: 1rem;
        z-index: 12;
        box-shadow: 0 16px 36px rgba(15, 23, 42, .12);
        max-height: min(360px, 48vh);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }

    .test-question-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(44px, 1fr));
        gap: .5rem;
    }

    .test-question-grid .btn {
        min-height: 40px;
        border-radius: 8px;
        font-weight: 800;
        transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease, background .16s ease;
    }

    .test-question-grid .btn:hover {
        transform: translateY(-1px);
    }

    .test-question-grid .btn.is-current {
        border-color: #f59e0b;
        box-shadow: 0 0 0 .2rem rgba(245, 158, 11, .22), 0 12px 24px rgba(15, 23, 42, .16);
        transform: translateY(-1px) scale(1.03);
    }

    [data-question-card] {
        scroll-margin-top: 150px;
    }

    [data-question-card].is-current > .border {
        border-color: rgba(245, 158, 11, .72) !important;
        box-shadow: 0 16px 34px rgba(15, 23, 42, .08);
    }

    @media (max-width: 767.98px) {
        .test-question-nav {
            top: .75rem;
            max-height: 34vh;
            padding: .75rem !important;
        }

        .test-question-grid {
            grid-template-columns: repeat(auto-fill, minmax(38px, 1fr));
            gap: .4rem;
        }

        [data-question-card] {
            scroll-margin-top: 170px;
        }
    }

    @media (max-width: 575.98px) {
        .test-question-grid {
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }

        .test-question-grid .btn {
            min-height: 36px;
            padding-left: .35rem;
            padding-right: .35rem;
        }

        [data-active-question-label] {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="content-card p-4" data-reveal>
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-3">
        <div>
            <div class="hero-kicker">Isi Tes</div>
            <h1 class="h4 mb-1">Tes Soal KIP-K</h1>
            <p class="text-secondary mb-0">Tekan mulai tes terlebih dahulu, lalu pilih nomor soal yang ingin dikerjakan.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <span class="badge badge-soft">{{ $questions->count() }} soal</span>
            <span class="badge badge-soft">{{ $durationMinutes }} menit</span>
        </div>
    </div>

    @if(! $alternative)
        <div class="alert alert-warning mb-0">
            Lengkapi profil terlebih dahulu sebelum mengisi tes.
            <a href="{{ route('user.profile') }}">Buka profil</a>
        </div>
    @elseif($questions->isEmpty())
        <div class="alert alert-info mb-0">Soal tes belum tersedia. Silakan cek kembali nanti.</div>
    @elseif(! $setting->is_open)
        <div class="text-center py-5">
            <div class="mx-auto mb-3 timeline-dot" style="width: 58px; height: 58px;">i</div>
            <h2 class="h4">Tes belum dibuka</h2>
            <p class="text-secondary mb-0">Silakan tunggu instruksi dari admin sebelum memulai tes.</p>
        </div>
    @elseif($completed)
        <div class="text-center py-5">
            <div class="mx-auto mb-3 timeline-dot" style="width: 58px; height: 58px;">✓</div>
            <h2 class="h4">Selamat telah mengerjakan tes</h2>
            <p class="text-secondary mb-0">Semoga hasilnya sesuai dengan harapan.</p>
        </div>
    @elseif(! $testStarted)
        <div class="row g-3 align-items-center">
            <div class="col-lg-8">
                <div class="border rounded bg-white p-3">
                    <h2 class="h5 mb-2">Siap memulai tes?</h2>
                    <p class="text-secondary mb-2">{{ $setting->instruction }}</p>
                    <p class="text-secondary mb-0">Setelah dimulai, waktu tes berjalan selama {{ $durationMinutes }} menit. Gunakan tabel nomor untuk memilih soal yang ingin dikerjakan terlebih dahulu.</p>
                </div>
                <div class="alert alert-danger mt-3 mb-0">
                    Penting: jangan membuka tab, aplikasi, atau halaman web lain selama tes berlangsung. Jika halaman tes ditinggalkan, jawaban akan otomatis tersubmit dan tes dianggap selesai.
                </div>
            </div>
            <div class="col-lg-4">
                <form method="post" action="{{ route('user.test.start') }}">
                    @csrf
                    <button class="btn btn-primary w-100">Mulai Tes</button>
                </form>
            </div>
        </div>
    @else
        <div class="border rounded bg-white p-3 mb-4 d-flex flex-wrap justify-content-between gap-3 align-items-center">
            <div>
                <div class="hero-kicker mb-1">Waktu Tersisa</div>
                <div class="fs-3 fw-bold" data-countdown data-remaining="{{ $remainingSeconds }}">--:--:--</div>
            </div>
            <span class="badge text-bg-primary">Tes sedang berlangsung</span>
        </div>
        <div class="alert alert-danger mb-4">
            Jangan membuka tab, aplikasi, atau halaman web lain. Jika halaman tes ditinggalkan, jawaban akan otomatis tersubmit dan tes selesai.
        </div>

        <div class="border rounded bg-white p-3 mb-4 test-question-nav">
            <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-2">
                <div>
                    <h2 class="h6 mb-0">Pilih Nomor Soal</h2>
                    <small class="text-secondary">Nomor aktif mengikuti posisi scroll.</small>
                </div>
                <span class="badge text-bg-warning" data-active-question-label>Soal 1</span>
            </div>
            <div class="test-question-grid">
                @foreach($questions as $question)
                    <a class="btn btn-sm {{ isset($answers[$question->id]) ? 'btn-primary' : 'btn-outline-primary' }}" href="#question-{{ $loop->iteration }}" data-question-nav="{{ $question->id }}" data-question-number="{{ $loop->iteration }}" aria-label="Lompat ke soal {{ $loop->iteration }}">
                        {{ $loop->iteration }}
                    </a>
                @endforeach
            </div>
        </div>

        <form method="post" action="{{ route('user.test') }}" id="testForm">
            @csrf
            <input type="hidden" name="time_expired" value="0" data-time-expired>
            <input type="hidden" name="auto_submit" value="0" data-auto-submit>
            <div class="alert alert-warning d-none" data-unanswered-alert>
                Masih ada soal yang belum dijawab. Silakan lengkapi semua jawaban sebelum submit.
            </div>
            <div class="alert alert-danger d-none" data-auto-submit-alert>
                Tes otomatis tersubmit karena Anda meninggalkan halaman tes.
            </div>

            <div class="row g-3">
                @foreach($questions as $question)
                    <div class="col-12" id="question-{{ $loop->iteration }}" data-question-card="{{ $question->id }}">
                        <div class="border rounded p-3 bg-white">
                            <div class="d-flex flex-wrap gap-2 align-items-start mb-3">
                                <span class="badge text-bg-primary">Soal {{ $loop->iteration }}</span>
                                <div class="fw-semibold flex-grow-1">{{ $question->question }}</div>
                            </div>
                            @foreach(['A' => $question->option_a, 'B' => $question->option_b, 'C' => $question->option_c, 'D' => $question->option_d] as $key => $option)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="question-{{ $question->id }}-{{ $key }}" value="{{ $key }}" data-question-answer="{{ $question->id }}" @checked(old('answers.'.$question->id, $answers[$question->id] ?? '') === $key)>
                                    <label class="form-check-label" for="question-{{ $question->id }}-{{ $key }}">
                                        <strong>{{ $key }}.</strong> {{ $option }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="btn btn-primary mt-3">Simpan Jawaban Tes</button>
        </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
(() => {
    const countdown = document.querySelector('[data-countdown]');
    const form = document.getElementById('testForm');
    if (!countdown || !form) return;

    const alertBox = document.querySelector('[data-unanswered-alert]');
    const autoSubmitAlert = document.querySelector('[data-auto-submit-alert]');
    const expiredInput = document.querySelector('[data-time-expired]');
    const autoSubmitInput = document.querySelector('[data-auto-submit]');
    const questionCards = [...document.querySelectorAll('[data-question-card]')];
    const questionIds = questionCards.map((card) => card.dataset.questionCard);
    const activeQuestionLabel = document.querySelector('[data-active-question-label]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    let remaining = Number(countdown.dataset.remaining || 0);
    let submitted = false;
    let manualSubmit = false;

    const isAnswered = (questionId) => Boolean(document.querySelector(`[data-question-answer="${questionId}"]:checked`));
    const markQuestion = (questionId) => {
        const nav = document.querySelector(`[data-question-nav="${questionId}"]`);
        if (!nav) return;
        nav.classList.toggle('btn-primary', isAnswered(questionId));
        nav.classList.toggle('btn-outline-primary', !isAnswered(questionId));
    };
    const unansweredIds = () => questionIds.filter((questionId) => !isAnswered(questionId));
    const setCurrentQuestion = (questionId) => {
        const nav = document.querySelector(`[data-question-nav="${questionId}"]`);
        const card = document.querySelector(`[data-question-card="${questionId}"]`);
        const number = nav?.dataset.questionNumber;

        document.querySelectorAll('[data-question-nav]').forEach((item) => {
            item.classList.toggle('is-current', item.dataset.questionNav === questionId);
            item.setAttribute('aria-current', item.dataset.questionNav === questionId ? 'true' : 'false');
        });

        questionCards.forEach((item) => {
            item.classList.toggle('is-current', item.dataset.questionCard === questionId);
        });

        if (activeQuestionLabel && number) {
            activeQuestionLabel.textContent = `Soal ${number}`;
        }
    };

    questionIds.forEach(markQuestion);
    if (questionIds[0]) setCurrentQuestion(questionIds[0]);

    if ('IntersectionObserver' in window) {
        const visibleQuestions = new Map();
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                const questionId = entry.target.dataset.questionCard;
                if (entry.isIntersecting) {
                    visibleQuestions.set(questionId, entry.intersectionRatio);
                } else {
                    visibleQuestions.delete(questionId);
                }
            });

            const current = [...visibleQuestions.entries()]
                .sort((first, second) => second[1] - first[1])[0]?.[0];

            if (current) setCurrentQuestion(current);
        }, {
            root: null,
            threshold: [0.18, 0.35, 0.55, 0.75],
            rootMargin: '-18% 0px -58% 0px',
        });

        questionCards.forEach((card) => observer.observe(card));
    }

    document.querySelectorAll('[data-question-answer]').forEach((input) => {
        input.addEventListener('change', () => {
            markQuestion(input.dataset.questionAnswer);
            setCurrentQuestion(input.dataset.questionAnswer);
            alertBox?.classList.add('d-none');
        });
    });

    const lockTestView = () => {
        document.querySelectorAll('[data-question-answer]').forEach((input) => {
            input.disabled = true;
        });
        form.querySelector('button[type="submit"], button:not([type])')?.setAttribute('disabled', 'disabled');
        autoSubmitAlert?.classList.remove('d-none');
    };

    const submitTest = () => {
        if (submitted) return;
        submitted = true;
        if (autoSubmitInput) autoSubmitInput.value = '1';
        lockTestView();

        const formData = new FormData(form);
        if (navigator.sendBeacon) {
            const payload = new Blob([new URLSearchParams(formData).toString()], {
                type: 'application/x-www-form-urlencoded;charset=UTF-8',
            });
            navigator.sendBeacon(form.action, payload);
            return;
        }

        form.submit();
    };

    const submitTestNow = async () => {
        if (submitted) return;
        submitted = true;
        if (autoSubmitInput) autoSubmitInput.value = '1';
        lockTestView();

        try {
            const response = await fetch('{{ route('user.test.auto-submit') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: new FormData(form),
                keepalive: true,
            });

            if (response.ok) {
                window.location.href = '{{ route('user.test') }}';
                return;
            }
        } catch (error) {
            submitTest();
        }
    };

    form.addEventListener('submit', (event) => {
        manualSubmit = true;
        if (expiredInput?.value === '1') return;

        const missing = unansweredIds();
        if (missing.length === 0) return;

        manualSubmit = false;
        event.preventDefault();
        alertBox?.classList.remove('d-none');
        const firstMissing = document.querySelector(`[data-question-card="${missing[0]}"]`);
        firstMissing?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    const render = () => {
        const hours = Math.floor(remaining / 3600);
        const minutes = Math.floor((remaining % 3600) / 60);
        const seconds = remaining % 60;
        countdown.textContent = [hours, minutes, seconds]
            .map((value) => String(value).padStart(2, '0'))
            .join(':');

        if (remaining <= 0 && !submitted) {
            submitted = true;
            if (expiredInput) expiredInput.value = '1';
            form.submit();
            return;
        }

        remaining -= 1;
    };

    render();
    setInterval(render, 1000);

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden' && !manualSubmit) {
            submitTest();
        } else if (document.visibilityState === 'visible' && submitted) {
            window.location.href = '{{ route('user.test') }}';
        }
    });

    window.addEventListener('blur', () => {
        if (!manualSubmit) {
            submitTestNow();
        }
    });

    window.addEventListener('pagehide', () => {
        if (!manualSubmit) {
            submitTest();
        }
    });
})();
</script>
@endpush
