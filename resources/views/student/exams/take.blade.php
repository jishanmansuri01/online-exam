@extends('layouts.student')

@section('title', 'Taking Exam')
@section('page-title', $exam->title)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css">
<style>
    .timer-box { position: sticky; top: 20px; z-index: 10; }
    .timer-display { font-size: 2.5rem; font-weight: 700; font-family: monospace; letter-spacing: 2px; }
    .timer-danger { color: #dc3545 !important; animation: blink 1s infinite; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.4} }
    .question-card { border-left: 4px solid #0d6efd; }
    .question-card.answered { border-left-color: #198754; }
    .option-label {
        cursor: pointer; padding: 10px 15px;
        border: 2px solid #e0e0e0; border-radius: 8px;
        display: block; transition: all 0.2s; margin-bottom: 8px;
    }
    .option-label:hover { border-color: #0d6efd; background: #f0f5ff; }
    input[type="radio"]:checked + .option-label {
        border-color: #198754; background: #f0fff4;
        color: #198754; font-weight: 600;
    }
    .CodeMirror { height: 250px; border-radius: 8px; font-size: 14px; }
    .output-box {
        background: #1e1e1e; color: #00ff00;
        border-radius: 8px; padding: 15px;
        font-family: monospace; font-size: 13px;
        min-height: 80px; margin-top: 10px;
        white-space: pre-wrap;
    }
    .lang-select { width: 150px; }
</style>
@endpush

@section('content')
<form id="exam-form" action="{{ route('student.exams.submit', $exam) }}" method="POST">
    @csrf

    <div class="row g-4">

        <!-- Timer Sidebar -->
        <div class="col-md-3">
            <div class="card timer-box text-center">
                <div class="card-body p-4">
                    <div class="text-muted small mb-1">Time Remaining</div>
                    <div class="timer-display text-primary" id="timer">00:00:00</div>
                    <hr>
                    <div class="text-muted small">{{ $exam->title }}</div>
                    <div class="mt-2">
                        <span class="badge bg-info">{{ $questions->count() }} Questions</span>
                        <span class="badge bg-warning text-dark">{{ $exam->total_marks }} Marks</span>
                    </div>
                    <hr>
                    <button type="button" class="btn btn-success w-100" onclick="confirmSubmit()">
                        <i class="bi bi-check-circle me-1"></i> Submit Exam
                    </button>
                </div>
            </div>
        </div>

        <!-- Questions -->
        <div class="col-md-9">
            @foreach($questions as $index => $question)
            <div class="card mb-3 question-card" id="qcard-{{ $question->id }}">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Q{{ $index + 1 }}. {{ $question->question_text }}</span>
                        <div class="d-flex gap-2 align-items-center">
                            @if($question->question_type === 'mcq')
                                <span class="badge bg-info">MCQ</span>
                            @elseif($question->question_type === 'programming')
                                <span class="badge bg-warning text-dark">Programming</span>
                            @else
                                <span class="badge bg-secondary">Short Answer</span>
                            @endif
                            <span class="badge bg-dark">{{ $question->marks }} mark(s)</span>
                        </div>
                    </div>

                    @if($question->question_type === 'mcq')
                        @foreach($question->options as $option)
                        <div>
                            <input type="radio"
                                   name="answers[{{ $question->id }}]"
                                   id="opt-{{ $option->id }}"
                                   value="{{ $option->id }}"
                                   class="d-none"
                                   onchange="markAnswered({{ $question->id }})">
                            <label for="opt-{{ $option->id }}" class="option-label">
                                {{ $option->option_text }}
                            </label>
                        </div>
                        @endforeach

                    @elseif($question->question_type === 'short_answer')
                        <textarea name="answers[{{ $question->id }}]"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Write your answer here..."
                                  onkeyup="markAnswered({{ $question->id }})"></textarea>

                    @elseif($question->question_type === 'programming')
                        <!-- Language Selector -->
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <select class="form-select lang-select" id="lang-{{ $question->id }}"
                                    onchange="changeLanguage({{ $question->id }})">
                                <option value="c">C</option>
                                <option value="cpp">C++</option>
                                <option value="python">Python</option>
                                <option value="java">Java</option>
                            </select>
                            <button type="button" class="btn btn-sm btn-dark"
                                    onclick="runCode({{ $question->id }})">
                                <i class="bi bi-play-fill me-1"></i> Run Code
                            </button>
                            <span class="spinner-border spinner-border-sm text-primary d-none"
                                  id="spinner-{{ $question->id }}"></span>
                        </div>

                        <!-- Code Editor -->
                        <textarea id="code-{{ $question->id }}"
                                  class="code-editor">{{ old('code.'.$question->id, '// Write your code here') }}</textarea>

                        <!-- Hidden inputs to store code, output, language -->
                        <input type="hidden" name="code[{{ $question->id }}]"
                               id="hidden-code-{{ $question->id }}">
                        <input type="hidden" name="code_output[{{ $question->id }}]"
                               id="hidden-output-{{ $question->id }}">
                        <input type="hidden" name="code_lang[{{ $question->id }}]"
                               id="hidden-lang-{{ $question->id }}" value="c">

                        <!-- Output Box -->
                        <div class="output-box" id="output-{{ $question->id }}">
                            // Output will appear here after running...
                        </div>
                    @endif

                </div>
            </div>
            @endforeach

            <div class="text-end mt-3 mb-4">
                <button type="button" class="btn btn-success btn-lg px-5" onclick="confirmSubmit()">
                    <i class="bi bi-check-circle me-2"></i> Submit Exam
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Time up modal -->
<div class="modal fade" id="timeupModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-alarm me-2"></i>Time's Up!</h5>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-hourglass-bottom fs-1 text-danger"></i>
                <p class="mt-3 mb-0">Your time has expired. Submitting automatically...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/clike/clike.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/python/python.min.js"></script>
<script>
// Initialize CodeMirror editors
const editors = {};

document.querySelectorAll('.code-editor').forEach(function(textarea) {
    const qid = textarea.id.replace('code-', '');
    editors[qid] = CodeMirror.fromTextArea(textarea, {
        lineNumbers: true,
        theme: 'dracula',
        mode: 'text/x-csrc',
        indentWithTabs: true,
        lineWrapping: true,
    });

    editors[qid].on('change', function() {
        document.getElementById('hidden-code-' + qid).value = editors[qid].getValue();
        markAnswered(qid);
    });
});

// Change language mode
function changeLanguage(qid) {
    const lang = document.getElementById('lang-' + qid).value;
    document.getElementById('hidden-lang-' + qid).value = lang;

    const modeMap = {
        'c':      'text/x-csrc',
        'cpp':    'text/x-c++src',
        'python': 'python',
        'java':   'text/x-java',
    };

    editors[qid].setOption('mode', modeMap[lang] || 'text/x-csrc');
}

// Language IDs for Judge0
const judge0Languages = {
    'c':      50,
    'cpp':    54,
    'python': 71,
    'java':   62,
};

// Run code using Judge0 API
async function runCode(qid) {
    const code     = editors[qid].getValue();
    const lang     = document.getElementById('lang-' + qid).value;
    const outputEl = document.getElementById('output-' + qid);
    const spinner  = document.getElementById('spinner-' + qid);

    if (!code.trim()) {
        outputEl.textContent = '⚠️ Please write some code first!';
        return;
    }

    spinner.classList.remove('d-none');
    outputEl.textContent = '⏳ Running your code...';

    try {
        const res = await fetch('{{ route("student.exams.compile") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            body: JSON.stringify({
                code:     code,
                language: lang,
            }),
        });

        const result = await res.json();
        console.log('Compile result:', result);

        let output = '';

        if (result.stdout && result.stdout.trim()) {
            output = result.stdout;
        } else if (result.stderr && result.stderr.trim()) {
            output = '❌ Error:\n' + result.stderr;
        } else if (result.error && result.error.trim()) {
            output = '❌ Error:\n' + result.error;
        } else {
            output = '✅ Code ran successfully with no output!';
        }

        outputEl.textContent = output;
        document.getElementById('hidden-output-' + qid).value = output;
        markAnswered(qid);

    } catch (err) {
        outputEl.textContent = '❌ Error: ' + err.message;
        console.error('Run code error:', err);
    }

    spinner.classList.add('d-none');
}
// Timer
let timeLeft = {{ $timeLeft }};

function pad(n) { return n < 10 ? '0' + n : n; }

function updateTimer() {
    const h = Math.floor(timeLeft / 3600);
    const m = Math.floor((timeLeft % 3600) / 60);
    const s = timeLeft % 60;
    $('#timer').text(pad(h) + ':' + pad(m) + ':' + pad(s));

    if (timeLeft <= 60) {
        $('#timer').addClass('timer-danger').removeClass('text-primary');
    }
    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        $('#timeupModal').modal('show');
        setTimeout(function() { submitExam(); }, 3000);
        return;
    }
    timeLeft--;
}

const timerInterval = setInterval(updateTimer, 1000);
updateTimer();

function markAnswered(qid) {
    $('#qcard-' + qid).addClass('answered');
}

function submitExam() {
    // Copy all editor values to hidden inputs before submit
    Object.keys(editors).forEach(function(qid) {
        const el = document.getElementById('hidden-code-' + qid);
        if (el) el.value = editors[qid].getValue();
    });
    window.onbeforeunload = null;
    document.getElementById('exam-form').submit();
}

function confirmSubmit() {
    if (confirm('Are you sure you want to submit the exam?')) {
        clearInterval(timerInterval);
        submitExam();
    }
}

window.onbeforeunload = function() {
    return 'Are you sure you want to leave?';
};
</script>
@endpush