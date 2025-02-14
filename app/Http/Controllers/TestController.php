<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Program;
use App\Models\Test;
use App\Models\TestQuestion;
use App\Services\CertificateService;
use App\Services\ProgramService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Storage;


class TestController extends Controller
{

    protected $certificateService, $programService;

    public function __construct(CertificateService $certificateService, ProgramService $programService)
    {
        $this->certificateService = $certificateService;
        $this->programService = $programService;
    }

    public function show($program_id)
    {
        $test = optional(Program::where('id', $program_id)->with('test', function ($q) {
            $q->with('testQuestions.questionAnswers')->get();
        })->get()->first())->test;
        return $this->formatResponse('success', 'test-get', $test);
    }
    public function submitTest(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'test' => 'required',
            'test.program_id' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $correctAnswers = 0;
        $wrongAnswers = 0;
        $enrollment = Enrollment::where('program_id', $request->test['program_id'])
            ->withProgramTitle()
            ->where('user_id', Auth::id())
            ->first();
        foreach ($request->test['test_questions'] as $value) {

            if ($value['question_type'] == 'MCQ') {
                $checkAnswer = null;
                foreach ($value['question_answers'] as $answer) {
                    if ($answer['selected']) {
                        $checkAnswer = $answer['order_number'];
                    }
                }

                $isAnswer = TestQuestion::where('id', $value['id'])
                    ->where('correct_answer', $checkAnswer)
                    ->first();

                if ($isAnswer) {
                    $correctAnswers += $isAnswer['marks'];
                } else {
                    $wrongAnswers += $value['marks'];
                }
            } else if ($value['question_type'] == 'TEXT')
                $correctAnswers += $value['marks'];
        }

        $test = Test::find($request->test['id']);

        if ($correctAnswers >= $test->passing_criteria && !$enrollment->is_certified) {
            $this->certificateService->generateCertificate(Auth::user()->name, $enrollment, 'enrollment-' . $enrollment->id);
            $enrollment->test_score = $correctAnswers;
            $enrollment->total_test_score = $test->total_marks;
            $enrollment->save();
            return $this->formatResponse(
                'success',
                'quiz-done',
                [
                    'correct_answers_marks' => $correctAnswers,
                    'wrong_answers' => $wrongAnswers,
                    'certificate_url' => $enrollment->certificate_image,
                    'passing_criteria' => $test->passing_criteria,
                ]
            );
        } else {
            return $this->formatResponse('success', 'quiz-failed', [
                'correct_answers_marks' => $correctAnswers,
                'wrong_answers' => $wrongAnswers, 'certificate_url' => $enrollment->certificate_url, 'passing_criteria' => $test->passingCriteria
            ]);
        }
    }
}
