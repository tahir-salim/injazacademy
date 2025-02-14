<template>
    <div>
        <heading class="mb-6">Create Evaluation</heading>
        <form v-if="!noPrograms" action="" @submit.prevent="createTest">
                    <div class="card">
                        <!-- Field title -->
                            <div class="flex border-b border-40">
                                <div class="flex ">
                                     <div class=" px-8 py-6" style="width:150px;">
                                        <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                            Title
                                            <span class="text-danger text-sm">*</span></label>
                                    </div>
                                    <div class="py-6 px-8" style="width:450px;">
                                        <div class="flex items-center">
                                            <input v-on:change="clearError('title')" name="title" id="title" v-model="payload.title" dusk="title" list="title-list" type="text" placeholder="Title" class="w-full form-control form-input form-input-bordered">
                                        </div>
                                        <span class="help-text help-text mt-2 text-danger" v-if="hasError('title')" v-text="getError('title')"></span>
                                    </div>
                                </div>

                                <div class="flex justify-center w-full">
                                     <div class=" px-8 py-6" style="width:150px;">
                                        <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                            Total Marks</label>
                                    </div>
                                    <div class="py-6 px-8 " style="width:150px;">
                                        <div class="flex items-center">
                                            <input v-model="payload.totalMarks" list="title-list" type="text" placeholder="Total Marks" class="text-center w-full form-control form-input form-input-bordered" disabled>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="flex border-b border-40">
                                <div class="flex ">
                                    <div class="py-6 px-8 text-left" style="width:150px;">
                                        <label class="inline-block text-80 pt-2 leading-tight">Program <span class="text-danger text-sm">*</span></label>
                                    </div>
                                    <div class="py-6 px-8 w-1/2 text-left" style="padding-top: 30px;width:450px;">
                                        <select v-model="payload.programId" v-on:change="clearError('programId')" class="w-full form-control form-select" id="programId">
                                             <option value="null" disabled selected>Select Program</option>
                                            <option :value="program.id" v-for="(program,index) in programs" :key="program.id">{{program.title}}</option>
                                        </select>
                                        <span class="help-text help-text mt-2 text-danger" v-if="hasError('programId')" v-text="getError('programId')"></span>
                                    </div>
                                </div>
                                 <div class="flex justify-center w-full">
                                     <div class=" px-8 py-6" style="width:150px;">
                                        <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                            Passing Marks</label>
                                    </div>
                                    <div class="py-6 px-8 " style="width:150px;">
                                        <div class="flex items-center">
                                            <input v-on:change="clearError('passingMarks')" v-model="payload.passingMarks" list="title-list" type="text" placeholder="Passing Marks" class="text-center w-full form-control form-input form-input-bordered">
                                        </div>
                                         <span class="help-text help-text mt-2 text-danger" v-if="hasError('passingMarks')" v-text="getError('passingMarks')"></span>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <br/>
                    <!-- <h1 class="mb-6 text-90 font-normal text-2xl">Questions</h1> -->
                    <div class="card">

                          <div class="row" style=" margin: 20px;">
                        <div class="flex flex-wrap -mb-4 course-content-list" style="padding-right: 20px; padding-left: 20px; margin-top: 20px;">
                            <div class="w-full mb-4" style="float: right; border-bottom: 1px solid #e3e3e3; ">
                                <div class="custom-border-left" >
                                    <h3 class="text-center" style="padding-top: 20px; padding-bottom: 20px;">Questions</h3>
                                    <ul class="list-group drag" style="padding: 0 20px; min-height: 50px;">

                                        <draggable v-model="questions" @end="updateList" >
                                            <li class="list-group-item item" v-for="(question, index) of questions" :key="index" >


                                                    <span class="item-title">{{question.order_number}}. {{ question.question }}</span>
                                                    <button ref="theDeleteBtn" class="btn btn-primary pull-left chapter-content-Delete btn-content-delete" @click.prevent="removeQuestions(index)">Delete</button>
                                                    <button class="btn btn-sm btn-danger pull-left chapter-content-Delete" style="cursor: pointer;" @click.prevent="editQQuestion(question)">Edit</button>

                                            </li>
                                        </draggable>
                                    </ul>

                                    <div class="btn-group text-center" style="margin-top:20px; margin-bottom: 20px;">
                                        <!--<button class="btn btn-primary custom-btn-primary" v-on:click="addContent(chapter, 3)">Add Attachment</button>-->
                                        <!-- <button class="btn btn-primary custom-btn-primary" @click="addNew(chapter.id, 'attachment')">Add Attachment</button>
                                        <button class="btn btn-warning custom-btn-warning" @click="addNew(chapter.id, 'hyperlink')">Add Url</button>
                                        <button class="btn btn-info custom-btn-success" @click="addNew(chapter.id, 'video')">Add Video</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                              <!-- Test Queestions Section -->
                          <div class="flex questions"  v-if="questions.length > 0">
                                <div class="py-6 px-3">
                                    <div class="flex items-center">
                                        <button
                                            @click.prevent="addQuestions(questions.length-1 + 1)"
                                            type="button"
                                            class="btn btn-default btn-primary inline-flex items-center relative"
                                        >
                                            Add Question
                                        </button>
                                    </div>
                                </div>


                            </div>

                        <!-- Question delete & add-->
                        <div class="flex questions"  v-if="questions.length == 0">
                            <div class="py-6 px-3">
                                <div class="flex items-center">
                                    <button
                                        @click.prevent="addQuestions(0)"
                                        type="button"
                                        class="btn btn-default btn-primary inline-flex items-center relative"
                                    >
                                        Add Question
                                    </button>
                                </div>
                            </div>


                        </div>

                    </div>
                    <template v-if="showModal">
                        <div class="modal select-none fixed pin z-50 overflow-x-hidden overflow-y-auto" data-testid="confirm-action-modal" tabindex="-1" role="dialog">
                            <div class="relative mx-auto flex justify-center z-20 py-view">
                                <div>

                                    <form autocomplete="off" class="bg-white rounded-lg shadow-lg overflow-hidden w-action-fields" _lpchecked="1" enctype="multipart/form-data">
                                        <div>
                                                <!-- <p v-if="errors.length">
                                                    <b>Please correct the following error(s):</b>
                                                    <ul>
                                                    <li v-for="error in errors">{{ error }}</li>
                                                    </ul>
                                                </p> -->
                                            <h2 class="border-b border-40 py-8 px-8 text-90 font-normal text-xl">{{ modalTitle }}</h2>

                                            <div class="action">
                                                <!-- Enter Question -->
                                                <div class="flex border-b border-40">
                                                    <div class="w-1/5 py-6 px-8 text-left">
                                                        <label class="inline-block text-80 pt-2 leading-tight">Question <span class="text-danger text-sm">*</span></label>
                                                    </div>
                                                    <div class="py-6 px-8 w-1/2">
                                                        <input v-model="editQuestion.question" type="text" id="title" placeholder="Question" class="w-full form-control form-input form-input-bordered" required>
                                                        <span class="help-text help-text mt-2 text-danger" v-if="hasSubError('question')" v-text="hasSubError('question')"></span>
                                                    </div>
                                                </div>

                                                <!-- Select Question Type -->
                                                 <div class="flex  border-b border-40">
                                                    <div class="w-1/5 px-8 py-6">
                                                        <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                                            Type
                                                        </label>
                                                    </div>
                                                    <div class="py-6 px-8 w-1/2 text-left" style="padding-top: 30px;">
                                                        <select v-model="editQuestion.type"  class="w-full form-control form-select" >
                                                            <option value="MCQ">MCQ</option>
                                                            <!-- <option value="TEXT">Text</option> -->
                                                        </select>
                                                        <span class="help-text help-text mt-2 text-danger" ></span>
                                                    </div>
                                                </div>

                                                <!-- Select Marks -->
                                                <div class="flex  border-b border-40">
                                                    <div class="w-1/5 px-8 py-6">
                                                        <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                                            Marks
                                                        </label>
                                                    </div>
                                                    <div class="py-6 px-8 w-1/2 text-left" style="padding-top: 30px;">
                                                        <select v-model="editQuestion.marks" class="w-full form-control form-select" >
                                                            <option selected="selected" value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="10">10</option>
                                                        </select>
                                                        <span class="help-text help-text mt-2 text-danger" v-if="hasError('programId')" v-text="getError('programId')"></span>
                                                    </div>
                                                </div>

                                                <!-- Question Type MCQ -->
                                                  <div v-if="editQuestion.type == 'MCQ'">
                                                    <draggable v-model="editQuestion.answers" @end="updateAnswersList" >
                                                        <div  v-for="(answer, index2) of editQuestion.answers" v-bind:key="'answer'+index2">
                                                            <div class="flex">
                                                                <div class="w-1/5 px-8 py-6">
                                                                    <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                                                    Answer {{index2+1}}
                                                                    <span class="text-danger text-sm">*</span></label>
                                                                </div>
                                                                <div class="py-6 px-8 w-4/5">
                                                                    <div class="flex items-center">
                                                                            <input v-on:change="clearError('questions.'+index+'.answers.'+index2+'.answer')" v-bind:id="'question_'+index+'_answer_'+index2" dusk="title" v-model="answer.answer" list="title-list" type="text" placeholder="Enter Answer" class="w-full form-control form-input form-input-bordered">

                                                                    </div>

                                                                    <div class="flex items-center justify-end">
                                                                        <button style="height:20px;margin-top:5px;margin-right:10px;" class="btn btn-info custom-btn-success"  @click.prevent="removeAnswers(editQuestion,index2)">Delete Answer</button>
                                                                        <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                                                        Select as Correct
                                                                        </label>
                                                                        <input type="radio" class="radio-button" :value="index2" :name="'row'+index" v-model="editQuestion.correct_answer" @change="changeCorrectAnswer(index)">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </draggable>
                                                     <!-- Anwers delete Add  -->
                                                    <div class="flex answers">
                                                            <div class="btn-group text-center" style="margin-bottom: 20px;">
                                                                <!--<button class="btn btn-primary custom-btn-primary" v-on:click="addContent(chapter, 3)">Add Attachment</button>-->
                                                                <!-- <button class="btn btn-primary custom-btn-primary" @click="addNew(chapter.id, 'attachment')">Add Attachment</button> -->
                                                                <span class="help-text help-text mt-2 text-danger" v-if="hasSubError('answers')" v-text="hasSubError('answers')"></span>
                                                                <button class="btn btn-warning custom-btn-warning"  @click.prevent="addAnswers(editQuestion , editQuestion.answers.length-1 + 1)">Add Answer</button>
                                                                <!-- <button class="btn btn-info custom-btn-success"  v-if="editQuestion.answers.length-1 != 0"  @click.prevent="removeAnswers(editQuestion,index2)">Delete Answer</button> -->
                                                            </div>

                                                    </div>

                                                </div>


                                                <!-- Question Type Text -->
                                                <div v-if="editQuestion.type == 'TEXT'">
                                                    <div class="flex border-b border-40">
                                                        <div class="w-1/5 px-8 py-6">
                                                            <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                                                Answer
                                                                <span class="text-danger text-sm">*</span></label>
                                                        </div>
                                                        <div class="py-6 px-8 w-4/5">
                                                            <div class="flex items-center">
                                                                <textarea v-model="editQuestion.detailedAnswer" rows="5" placeholder="Enter Answer Here..." class="w-full form-control form-input form-input-bordered py-3 h-auto" style="margin-top: 0px; margin-bottom: 0px; height: 107px;"></textarea>
                                                                <span class="help-text help-text mt-2 text-danger" v-if="hasSubError('answers')" v-text="hasSubError('answers')"></span>
                                                            </div>
                                                            <span class="help-text help-text mt-2 text-danger" v-if="hasError('body')" v-text="getError('body')"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="bg-30 px-6 py-3 flex">
                                            <div class="flex items-center ml-auto">
                                                <button @click="closeModal" type="button" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link" style="border-radius: .5rem;background-color: #868686; color: var(--white); margin-right: 10px;">
                                                    Cancel
                                                </button>
                                                <button  @click="addQuestion(editQuestion)"  type="button" id="add-question"  class="btn btn-default btn-primary">
                                                    <span>Add Question</span>
                                                </button>
                                                <img v-if="isSpinner" :src="spinner">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="fixed pin bg-80 z-20 opacity-75"></div>
                    </template>
                    <template v-if="showEditModal">
                        <div class="modal select-none fixed pin z-50 overflow-x-hidden overflow-y-auto" data-testid="confirm-action-modal" tabindex="-1" role="dialog">
                            <div class="relative mx-auto flex justify-center z-20 py-view">
                                <div>
                                    <form autocomplete="off" class="bg-white rounded-lg shadow-lg overflow-hidden w-action-fields" _lpchecked="1" enctype="multipart/form-data">
                                        <div>
                                            <h2 class="border-b border-40 py-8 px-8 text-90 font-normal text-xl">{{ modalTitle }}</h2>

                                            <div class="action">

                                                 <!-- <p v-if="errors.length">
                                                    <b>Please correct the following error(s):</b>
                                                    <ul>
                                                    <li v-for="error in errors">{{ error }}</li>
                                                    </ul>
                                                </p> -->

                                                <div class="flex border-b border-40">
                                                    <div class="w-1/5 py-6 px-8 text-left">
                                                        <label class="inline-block text-80 pt-2 leading-tight">Question <span class="text-danger text-sm">*</span></label>
                                                    </div>
                                                    <div class="py-6 px-8 w-1/2">
                                                        <input type="text" id="e-title" placeholder="Title" v-model="editQuestion.question" class="w-full form-control form-input form-input-bordered">
                                                        <span class="help-text help-text mt-2 text-danger" v-if="hasSubError('question')" v-text="hasSubError('question')"></span>
                                                    </div>
                                                </div>

                                                <!-- Select Question Type -->
                                                 <div class="flex  border-b border-40">
                                                    <div class="w-1/5 px-8 py-6">
                                                        <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                                            Type
                                                        </label>
                                                    </div>
                                                    <div class="py-6 px-8 w-1/2 text-left" style="padding-top: 30px;">
                                                        <select v-model="editQuestion.type" class="w-full form-control form-select" >
                                                            <option value="MCQ">MCQ</option>
                                                            <!-- <option value="TEXT">Text</option> -->
                                                        </select>
                                                        <span class="help-text help-text mt-2 text-danger" ></span>
                                                    </div>
                                                </div>

                                                <!-- Select Marks -->
                                                <div class="flex  border-b border-40">
                                                    <div class="w-1/5 px-8 py-6">
                                                        <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                                            Marks
                                                        </label>
                                                    </div>
                                                    <div class="py-6 px-8 w-1/2 text-left" style="padding-top: 30px;">
                                                        <select v-model="editQuestion.marks" class="w-full form-control form-select" >
                                                            <option selected="selected" value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="10">10</option>
                                                        </select>
                                                        <span class="help-text help-text mt-2 text-danger" v-if="hasError('programId')" v-text="getError('programId')"></span>
                                                    </div>
                                                </div>

                                                <!-- Question Type MCQ -->
                                                <div v-if="editQuestion.type == 'MCQ'">
                                                    <draggable v-model="editQuestion.answers" @end="updateAnswersList" >
                                                        <div  v-for="(answer, index2) of editQuestion.answers" v-bind:key="'answer'+index2">
                                                            <div class="flex">
                                                                <div class="w-1/5 px-8 py-6">
                                                                    <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                                                    Answer {{index2+1}}
                                                                    <span class="text-danger text-sm">*</span></label>
                                                                </div>
                                                                <div class="py-6 px-8 w-4/5">
                                                                    <div class="flex items-center">
                                                                            <input v-on:change="clearError('questions.'+index+'.answers.'+index2+'.answer')" v-bind:id="'question_'+index+'_answer_'+index2" dusk="title" v-model="answer.answer" list="title-list" type="text" placeholder="Enter Answer" class="w-full form-control form-input form-input-bordered">

                                                                    </div>
                                                                    <span class="help-text help-text mt-2 text-danger" v-if="hasError('questions.'+index+'.answers.'+index2+'.answer')" v-text="getError('questions.'+index+'.answers.'+index2+'.answer' , 'answer')"></span>
                                                                    <div class="flex items-center justify-end">
                                                                        <button style="height:20px;margin-top:5px;margin-right:10px;" class="btn btn-info custom-btn-success"  @click.prevent="removeAnswers(editQuestion,index2)">Delete Answer</button>
                                                                        <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                                                        Select as Correct
                                                                        </label>
                                                                        <input type="radio" class="radio-button" :value="index2" :name="'row'+index" v-model="editQuestion.correct_answer" @change="changeCorrectAnswer(index)">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </draggable>
                                                     <!-- Anwers delete Add  -->
                                                        <div class="flex answers">
                                                                <div class="btn-group text-center" style="margin-bottom: 20px;">
                                                                    <!--<button class="btn btn-primary custom-btn-primary" v-on:click="addContent(chapter, 3)">Add Attachment</button>-->
                                                                    <!-- <button class="btn btn-primary custom-btn-primary" @click="addNew(chapter.id, 'attachment')">Add Attachment</button> -->
                                                                    <span class="help-text help-text mt-2 text-danger" v-if="hasSubError('answers')" v-text="hasSubError('answers')"></span>
                                                                    <button class="btn btn-warning custom-btn-warning"  @click.prevent="addAnswers(editQuestion , editQuestion.answers.length-1 + 1)">Add Answer</button>
                                                                    <!-- <button class="btn btn-info custom-btn-success"  v-if="editQuestion.answers.length-1 != 0"  @click.prevent="removeAnswers(editQuestion,index2)">Delete Answer</button> -->
                                                                </div>

                                                        </div>

                                                <!-- Anwers delete Add  -->
                                                <!-- <div class="flex answers" v-if="editQuestion.answers.length == 0">
                                                    <div class="pb-6 px-8">
                                                        <div class="flex items-center">
                                                            <button
                                                                @click.prevent="addAnswers(editQuestion , 0)"
                                                                type="button"
                                                                class="btn btn-default btn-primary inline-flex items-center relative"
                                                            >
                                                                Add Answer 23
                                                            </button>
                                                        </div>
                                                    </div>


                                                </div> -->
                                                </div>


                                                <!-- Question Type Text -->
                                                <div v-if="editQuestion.type == 'TEXT'">
                                                    <div class="flex border-b border-40">
                                                        <div class="w-1/5 px-8 py-6">
                                                            <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                                                Answer <span class="text-danger text-sm">*</span>
                                                                </label>
                                                        </div>
                                                        <div class="py-6 px-8 w-4/5">
                                                            <div class="flex items-center">
                                                                 <!-- <select v-model="editQuestion.detailedAnswer" class="w-full form-control form-select" >
                                                                    <option selected="selected" value="Detailed">Detailed</option>
                                                                    <option value="Short">Short</option>
                                                                </select> -->
                                                                <textarea v-model="editQuestion.detailedAnswer" rows="5" placeholder="Enter Answer Here..." class="w-full form-control form-input form-input-bordered py-3 h-auto" style="margin-top: 0px; margin-bottom: 0px; height: 107px;"></textarea>

                                                            </div>
                                                            <span class="help-text help-text mt-2 text-danger" v-if="hasSubError('answers')" v-text="hasSubError('answers')"></span>
                                                            <!-- <span class="help-text help-text mt-2 text-danger" v-if="hasError('body')" v-text="getError('body')"></span> -->
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>



                                        </div>
                                        <div class="bg-30 px-6 py-3 flex">

                                            <div class="flex items-center ml-auto">
                                                <button @click="closeModal" type="button" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link" style="border-radius: .5rem;background-color: #868686; color: var(--white); margin-right: 10px;">
                                                    Cancel
                                                </button>
                                                <button v-if="!isSpinner" @click.prevent="updateQuestion" id="e-add-record"   class="btn btn-default btn-primary">
                                                    <span>Update Question</span>
                                                </button>
                                                <img v-if="isSpinner" :src="spinner">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="fixed pin bg-80 z-20 opacity-75"></div>
                    </template>

                 <!-- </div> -->

                 <!-- Post Question or cancle -->
                 <div class="flex items-center mt-4">
                    <a
                        tabindex="0"
                        class="btn btn-link dim cursor-pointer text-80 ml-auto mr-6"
                        @click="cancel()"
                    >
                    Cancel
                    </a>
                <button
                    type="submit"
                    class="btn btn-default btn-primary inline-flex items-center relative "
                    dusk="create-button"
                    >
                    <span v-if="!updatetest && !duplicatetest" class="">
                        Create
                    </span>
                    <span v-else-if="!updatetest">
                        Create
                    </span>
                    <span v-else>
                        Update
                    </span>
                </button>
                </div>
        </form>
        <div v-if="noPrograms" class="row" style="display: flex; margin-top: 70px;">
                        <h1 class="mr-3 text-base text-80 font-bold m-8 mt-4" style="margin: 0 auto; font-size: 70px;margin-bottom: 60px;">No Programs Found!</h1>

        </div>

    </div>
</template>

<script>
 window.Laravel = {csrfToken: '{{ csrf_token() }}'};
// import Swal from 'sweetalert2';
import draggable from 'vuedraggable';
import Swal from 'sweetalert2';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

export default {
      components: {
        draggable,
        flatPickr,
        // Edit,
    },
    data() {
            return {
                  updatetest: false,
                  duplicatetest: false,
                  questions: [],
                  programs: [],
                  testId: null,
                  payload: {
                        title : null,
                        programId: null,
                        totalMarks: 0,
                        passingMarks: 0,
                  },
                  formConfig: {headers: {"Content-Type": "application/json"}},
                  errors: {
                  },
                  answerErrors: [],
                  frontEndError : {},
                  showEditModal: false,
                  showModal: false,
                  editQuestion: {},
                   type: 'TEXT',
                  modalTitle: null,
                  noPrograms: false
            }
        },

    methods: {

        updateList( e ) {
            console.log("Fired");
            // console.log(this.questions);
            this.questions.forEach(function(question,index){
                question.order_number = index + 1;
            });
            // console.log(this.questions);
            // Nova.request().post( '/nova-vendor/content-manager/update-drag-drop-detail', {
            //     chapters: this.chapters,
            // } ).then( response => {
            //     if ( response ) {
            //         this.getCourseDetail();
            //         this.$toasted.show('Course content has been updated', { type: 'success' });
            //     }
            // } )
        },

        updateAnswersList(e){
             this.editQuestion.answers.forEach(function(answer,index){
                answer.order_number = index;
            });

            console.log(this.editQuestion.answers);
        },
        addQuestions(index) {

            this.editQuestion = {
                    question: null,
                    type: 'MCQ',
                    correct_answer: 0,
                    order_number: index + 1,
                    marks: 1,
                    answers: [
                        {
                            answer: null,
                            order_number: 0,
                        }
                    ],
                    detailedAnswer: null
                }



            this.showModal = true;
            this.modalTitle = 'Add Question';


        },
        calculateMarks: function(){
            var total = 0;
            this.questions.forEach((x) => {
                total += parseInt(x.marks);
            });
            console.log(total);
            this.payload.totalMarks = total;
        },
        removeQuestions(index) {
            this.questions.splice(index, 1);
            this.questions.forEach((question , index) => {
                question.order_number = index + 1;
            })
            this.calculateMarks();
            //  Swal.fire(
            //                 'Deleted!',
            //                 'Question has been deleted.',
            //                 'success'
            //             )
            //this.calculateFields();
        },

        addAnswers(question , index){
            // console.log(question.answers)
            question.answers.push({
                answer: null,
                order_number: index
            });
            console.log(index)
        },

        removeAnswers(question, index) {
            if(question.answers.length > 1)
            {
                if(question.answers[index].order_number == question.correct_answer && index == 0)
                {
                    question.correct_answer = index + 1;
                }
                else if(question.answers[index].order_number == question.correct_answer)
                {
                    question.correct_answer = index - 1;
                }
            }

            if(index < question.answers.length-1)
            {
                if(question.answers[index].order_number < question.correct_answer)
                {
                    question.correct_answer = question.correct_answer-1;
                }
            }


            question.answers.splice(index, 1);
            question.answers.forEach((answer , index) => {
                answer.order_number = index;
            });
            //this.calculateFields();
            console.log(index);
        },
        addQuestion(question)
        {
            this.errors = {};

            if (question.question == null || question.question == '') {
                this.errors.question = 'The question field is required';

            }

            if(question.type == 'MCQ')
                if(question.answers.length > 0)
                    question.answers.forEach((answer , index) => {
                        if(answer.answer == null || answer.answer == '')
                        {
                            this.errors.answers = 'All Answer fields are required';
                        }
                    });
                else
                    this.errors.answers = 'Answer field is required';
            // else if(question.type == 'TEXT' && (question.detailedAnswer == null || question.detailedAnswer == ''))
            //     this.errors.answers = 'The answer field is required';
            // console.log(this.answerErrors);
            // console.log(this.errors);;

            if(this.errors.question != null || this.errors.answers != null)
            {
                return;
            }

// console.log(question);
            this.questions.push(question);
            console.log(this.questions);
            this.calculateMarks();
            this.closeModal();

        },
        editQQuestion(question){
            // console.log(question);
            this.errors = {};
            this.editQuestion = JSON.parse(JSON.stringify(question));
            // console.log(this.editQuestion);
            this.showEditModal = true;
            this.modalTitle = 'Update Question';
            console.log(this.editQuestion);
        },

        updateQuestion()
        {
            this.errors = {};

            if (this.editQuestion.question == null || this.editQuestion.question == '') {
                this.errors.question = 'The question field is required';

            }

            if(this.editQuestion.type=='MCQ')
                if(this.editQuestion.answers.length > 0)
                    this.editQuestion.answers.forEach((answer , index) => {
                        if(answer.answer == null || answer.answer == '')
                        {
                            this.errors.answers = 'All Answer fields are required';
                        }
                    });
                else
                    this.errors.answers = 'Answer field is required';
            // else if(this.editQuestion.type == 'TEXT' && (this.editQuestion.detailedAnswer == null || this.editQuestion.detailedAnswer == ''))
            //     this.errors.answers = 'The answer field is required';

            if(this.errors.question != null || this.errors.answers != null)
            {
                return;
            }

           //console.log(this.questions[0]);
            this.questions.forEach((question , index) => {
                if(question.order_number == this.editQuestion.order_number)
                {
                     this.questions[index] = this.editQuestion;
                }
            }) ;


            console.log(this.questions)
            this.calculateMarks();
            this.closeModal();
        },

        createTest: function(){

            this.payload.questions = this.questions.map(el=>{

                if(el.type == 'MCQ')
                    return {
                        question: el.question,
                        correct_answer: el.correct_answer,
                        order_number: el.order_number,
                        marks: el.marks,
                        type: el.type,
                        answers: el.answers.map(ans => ({
                            answer: ans.answer,
                            order_number: ans.order_number,
                        })),
                    }
                else if(el.type == 'TEXT')
                        return {
                        question: el.question,
                        correct_answer: 0,
                        order_number: el.order_number,
                        marks: el.marks,
                        type: el.type,
                        detailedAnswer: el.detailedAnswer,
                    }
            });


            if(this.updatetest){
                this.updateTest();
                return;
            }

                Nova.request().post('/nova-vendor/test-creator/create',this.payload,this.formConfig).then(response => {
                    if(response.data.status=='success')
                            window.location.replace('/dashboard/resources/tests/'+response.data.data);
                }).catch(error => {



                if (error.response) {
                    if(error.response.status==422){
                        this.errors = error.response.data.errors;

                        if(Object.keys(this.errors).length == 1)
                        {
                             this.$toasted.show(this.errors.message, {type: 'error'});
                        }
                        else
                            this.$toasted.show('Some Fields are missing', {type: 'error'});
                    }
                    // else if(error.response.status==413){
                    //     this.$toasted.show('Some fields may be invalid or file size may be too large', {type: 'error'});
                    // }
                    else{
                        this.$toasted.show('Server Error', {type: 'error'});
                    }
                }
                else if (error.request) {
                    console.log('request:',error.request);
                    this.$toasted.show('Server not responding', {type: 'error'});
                }
                else {
                    console.log('else Error', error.message);
                    this.$toasted.show(error.message || 'Error in request, please contact to support', {type: 'error'});
                }
                })
        },

        updateTest: function(){
            Nova.request().post('/nova-vendor/test-creator/update/' + this.testId,this.payload,this.formConfig).then(response => {
                    if(response.data.status=='success')
                            window.location.replace('/dashboard/resources/tests/'+response.data.data);
                }).catch(error => {

                    console.log('errors:',error.response);
                if (error.response) {
                    if(error.response.status==422){
                        this.errors = error.response.data.errors;
                        this.$toasted.show(error.response.data.message, {type: 'error'});
                    }
                    else if(error.response.status==413){
                        this.$toasted.show('Some fields may be invalid or file size may be too large', {type: 'error'});
                    }
                    else{
                        this.$toasted.show('Server Error', {type: 'error'});
                    }
                }
                else if (error.request) {
                    console.log('request:',error.request);
                    this.$toasted.show('Server not responding', {type: 'error'});
                }
                else {
                    console.log('else Error', error.message);
                    this.$toasted.show(error.message || 'Error in request, please contact to support', {type: 'error'});
                }
                })
        },

        init: function(){
            this.testId = this.$route.params.id;
            this.duplicatetest = this.$route.query.is_duplicate;
            // var query = this.testId ? `?test_id=${this.testId}` : '';
            Nova.request().post('/nova-vendor/test-creator/init', {
                'test_id': this.testId,
                'is_duplicate': this.duplicatetest,
            }).then(response => {

                if(response.data.test)
                {
                    this.populateForm(response.data.test);
                }

            }).catch(error => {
                    console.log(error);
                    console.log(error.response);
                    if (error.response) {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                            this.$toasted.show('Some fields may be missing or invalid', {type: 'error'});
                        }
                        else if(error.response.status==413){
                            this.$toasted.show('Some fields may be invalid or file size may be too large', {type: 'error'});
                        }
                        else{
                            this.$toasted.show('Server Error', {type: 'error'});
                        }
                    }
                    else if (error.request) {
                        console.log('request:',error.request);
                        this.$toasted.show('Server not responding', {type: 'error'});
                    }
                    else {
                        console.log('else Error', error.message);
                        this.$toasted.show(error.message || 'Error in request, please contact to support', {type: 'error'});
                    }
                });
        },

        populateForm: function(test)
        {
            console.log(test.program);
            if(!this.duplicatetest)
                this.updatetest = true;
            this.payload = {
                'title' : test.title,
                'sub_title' : test.sub_title,
                'body': test.body,
                'programId': test.program != undefined ? test.program.id : null,
                'totalMarks': test.total_marks,
                'passingMarks': test.passing_criteria
            }

            if(test.program != undefined)
                this.programs.unshift({id: test.program.id , title: test.program.title});

            this.questions = test.test_questions.map(el => {
                //     return {
                //     question: el.question,
                //     correct_answer: el.correct_answer,
                //     order_number: el.order_number,
                //     type: el.question_type,
                //     marks: el.marks,
                //     answers: el.question_answers.map(ans => ({
                //             answer: ans.answer,
                //             order_number: ans.order_number,
                //     })),
                // }

                   if(el.question_type == 'MCQ')
                    return {
                        question: el.question,
                        correct_answer: el.correct_answer,
                        order_number: el.order_number,
                        marks: el.marks,
                        type: el.question_type,
                        answers: el.question_answers.map(ans => ({
                            answer: ans.answer,
                            order_number: ans.order_number,
                        })),
                    }
                    else if(el.question_type == 'TEXT')
                        return {
                        question: el.question,
                        correct_answer: 0,
                        order_number: el.order_number,
                        marks: el.marks,
                        type: el.question_type,
                        detailedAnswer: el.question_answers[0].answer,
                    }
            })

            // console.log(this.questions);
        },

        loadPrograms: function(){
            Nova.request().get('/nova-vendor/test-creator/programs').then(response => {

                this.programs = this.programs.concat(response.data.map(el => {
                    return {
                        id: el.id,
                        title: el.title
                    }
                }));
                console.log(this.programs);
                if(this.programs.length == 0 && !(this.testId))
                    this.noPrograms = true;
                else
                    this.noPrograms = false;
            });
        },

        changeCorrectAnswer(index)
        {
            console.log(index);
            this.clearError('questions.'+index+'.correct_answer')
        },

        hasSubError(field ) {
            //console.log(field);
            if(field == 'answers')
            {
                return this.errors[field];
                // return;
            }
            return this.errors[field];
        },



        hasError(field) {
            //console.log(field);
            return this.errors.hasOwnProperty(field);
        },

        getError(field  , translate = null) {
            var error = {
                'question': 'The question field is required',
                'answer': 'The answer field is required',
                'correct_answer': 'The correct answer selection is required'
            }

            if (this.errors[field]) {
                    if(translate)
                    {
                    return error[translate];
                    }
                return this.errors[field][0];
            }
        },

        anyError(){
            return Object.keys(this.errors).length > 0;
        },

        clearError(field) {
            console.log(field,this.errors)
            if (field) {
                this.errors[field] = false;
                delete this.errors[field];
                if (Object.keys(this.errors).length == 0) {
                    this.errors = {};
                }

                // if (this.frontEndError[field]) {
                //     for (let prop in this.frontEndError[field]) {
                //         this.frontEndError[field][prop] = false;
                //     }
                // }
                return;
            }
        },

        closeModal() {
            //this.isSpinner = false;
            this.showModal = false;
            this.showEditModal = false;
        },

        cancel(){
            window.location.replace('/dashboard/resources/tests/');
            // history.back();
        }
    },
    mounted() {
        //this.addQuestions(0);
        this.init();
        this.loadPrograms();

    },
}
</script>

<style>
/* Scoped Styles */
</style>
