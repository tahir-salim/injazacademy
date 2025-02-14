<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqAnswered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public $relatedType;
    public function index(Request $request)
    {
        $category = $request->query('category_name') ?? null;
        $search = $request->query('search_query') ?? null;
        $limit_on_related = $request->query('no_limit_on_related') ?? null;

        $category_types = [Faq::ALL ,Faq::MENTOR, Faq::STUDENT, Faq::COURSE, Faq::WORKSHOP, Faq::ENROLLMENT];

        if ($category && !in_array($category, $category_types)) {
            return $this->formatResponse('error', 'validation-error', 'category-not-found');
        }

        $this->relatedType = null;
        if($search)
        {
            foreach($category_types as $type)
            {
                if(str_contains(strtolower($search), strtolower($type)))
                {
                    $this->relatedType = strtoupper($type);
                    break;
                }

            }
        }

        return $this->formatResponse('success', 'faq-questions', [
            'questions' => Faq::with('faqAnsweredByMe')->when($category != Faq::ALL, function($q) use($category){
                 $q->where('category' , $category);
            }) ->when($search, function($q) use($search){
                $q->where('question' , 'like' , "%$search%");
            })->paginate(10),
            'relatedQuestions' => $search && $this->relatedType && $this->relatedType!='ALL' ?
                Faq::where('category', $this->relatedType)->get()
            : null
        ]);
    }

    public function markHelpful(Request $request)
    {
        $helpstat = FaqAnswered::updateOrCreate([
            'user_id' => Auth::id(),
            'faq_id' => $request->faq_id
        ],[
            'yes' => $request->helpstat
        ]);

        return $this->formatResponse('success', 'question-marked', $helpstat);
        }

}
