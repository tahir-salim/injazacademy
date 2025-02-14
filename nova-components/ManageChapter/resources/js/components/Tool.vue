<template>
<div>
     <div class="py-3 flex items-center" style="padding: 10px; width: 80%; margin: 0 auto;">
            <div class="flex items-center" style="padding-top: 7px;">
                <h1 class="mb-3 text-90 font-normal text-2xl">Sessions Management - {{courseTitle}}</h1>
            </div>
            <div class="flex items-center ml-auto px-3">
                <button class="btn btn-default btn-primary" @click="addNewChapter()">
                    Create Session
                </button>
                <button class="btn btn-default btn-success custom-success-btn" style="margin-left: 10px;" @click="backToContent()">
                    Back
                </button>
            </div>
        </div>
        <div v-if="!chapterNotFound" class="card relative" style="width: 80%; margin: 0 auto; padding-bottom: 20px;">

            <div class="overflow-hidden overflow-x-auto relative">
                <div class="row">

                    <table cellpadding="0" cellspacing="0" data-testid="resource-table" class="table w-full">
                        <thead>
                        <tr>
                            <th class="text-left">
                                <span> Title </span>
                            </th>

                            <th class="text-left">
                                <span> Status </span>
                            </th>

                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <draggable v-model="chapters" @end="updateList" :element="'tbody'">
                            <tr v-for="chapter in chapters">
                                <td>
                                    <span class="whitespace-no-wrap text-left">{{ chapter.title }}</span>
                                </td>
                                <td>
                                     <span class="whitespace-no-wrap text-left" v-text="capitalize(chapter)"></span>
                                </td>
                                <td class="text-left pr-6 text-right">
                                    <button title="Edit"  class="appearance-none cursor-pointer text-70 hover:text-primary mr-3" @click="editChapter(chapter)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current">
                                            <path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path>
                                        </svg>
                                    </button>
                                    <button title="Delete"  class="appearance-none cursor-pointer text-70 hover:text-primary mr-3" @click="deleteChapter(chapter)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" role="presentation" class="fill-current">
                                            <path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </draggable>
                    </table>
                </div>
            </div>
        </div>
         <div v-if="chapterNotFound" class="row" style="display: flex; margin-top: 70px;">
                        <h1 class="mr-3 text-base text-80 font-bold m-8 mt-4" style="margin: 0 auto; font-size: 70px;margin-bottom: 60px;">No Sessions Found!</h1>

        </div>
         <template v-if="showModal">
            <div class="modal select-none fixed pin z-50 overflow-x-hidden overflow-y-auto" data-testid="confirm-action-modal" tabindex="-1" role="dialog">
                <div class="relative mx-auto flex justify-center z-20 py-view">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-action-fields">
                        <div>
                            <h2 class="border-b border-40 py-8 px-8 text-90 font-normal text-xl">{{ modalTitle }}</h2>
                            <div class="action">
                                <div class="flex border-b border-40">
                                    <div class="w-1/5 py-6 px-8 text-left">
                                        <label class="inline-block text-80 pt-2 leading-tight">Title</label>
                                    </div>
                                    <div class="py-6 px-8 w-1/2">
                                        <input type="text" id="title" placeholder="Title" v-model="chapterTitle" class="w-full form-control form-input form-input-bordered">
                                        <span class="help-text help-text mt-2 text-danger" v-if="hasError('title')" v-text="hasError('title')"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="action">
                                <div class="flex border-b border-40">
                                    <div class="w-1/5 py-6 px-8 text-left">
                                            <label class="inline-block text-80 pt-2 leading-tight">Status</label>
                                    </div>
                                    <div class="py-6 px-8 w-1/2 text-left" >
                                            <select v-model="status" class="w-full form-control form-select" >
                                                <option selected="selected" value="PUBLISHED">Published</option>
                                                <option value="DRAFT">Draft</option>
                                            </select>
                                            <!-- <span class="help-text help-text mt-2 text-danger" v-if="hasError('programId')" v-text="getError('programId')"></span> -->
                                    </div>
                                 </div>
                            </div>

                             <div class="action">
                                <div class="flex border-b border-40">
                                    <div class="w-1/5 py-6 px-8 text-left">
                                            <label class="inline-block text-80 pt-2 leading-tight">Order No.</label>
                                    </div>
                                    <div class="py-6 px-8 text-left" style="width: 100px;" >
                                            <input v-model="order_number" list="title-list" type="text"  class="text-center w-full form-control form-input form-input-bordered" disabled>
                                            <!-- <span class="help-text help-text mt-2 text-danger" v-if="hasError('programId')" v-text="getError('programId')"></span> -->
                                    </div>
                                 </div>
                            </div>
                            <!-- <div class="action" v-if="isCourseSchedule">
                                <div class="flex border-b border-40">
                                    <div class="w-1/5 py-6 px-8 text-left">
                                        <label class="inline-block text-80 pt-2 leading-tight">Scheduled Date</label>
                                    </div>
                                    <div class="py-6 px-8 w-1/2">
                                        <flat-pickr
                                                v-model="scheduledDate"
                                                :config="config"
                                                class="w-full form-control form-input-bordered"
                                                placeholder="Select date"
                                                name="date">
                                        </flat-pickr>

                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <div class="bg-30 px-6 py-3 flex">
                            <div class="flex items-center ml-auto">
                                <button @click="closeModal" type="button" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link" style="border-radius: .5rem;background-color: #868686; color: var(--white); margin-right: 10px;">
                                    Cancel
                                </button>
                                <button v-if="!isSpinner" type="submit" @click="storeChapter" class="btn btn-default btn-primary" id="submitBtn" :data-chapterId="chapterId" :data-type="modalType">
                                    <span>{{ modalTitle }}</span>
                                </button>
                                 <div v-if="isSpinner" style="width: 155px;text-align:center;overflow: hidden;">
                                        <img style="width:35px;transform:scale(1.2);" v-if="isSpinner" :src="spinner">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fixed pin bg-80 z-20 opacity-75"></div>
        </template>
</div>
</template>

<script>
window.Laravel = { csrfToken: '{{ csrf_token() }}' };
import draggable from 'vuedraggable';
import Swal from 'sweetalert2';

export default {
    components: {
        draggable,
    },
    methods:{
        getChaptersDetail: function () {
            this.courseId = this.$route.params.courseId;
            this.courseTitle = this.$route.params.courseTitle;
            // alert(this.courseId);
            Nova.request().get('/nova-vendor/manage-chapter/course/' + this.courseId ).then( response => {
                this.chapters = response.data.data.chapters;
                // if(response.data.data.course.type == "SCHEDULE_CONTENT"){
                //     this.isCourseSchedule = true;
                // }
                if(this.chapters.length == 0)
                    this.chapterNotFound = true;
                else
                    this.chapterNotFound = false;
                console.log(response.data.data.chapters);
            })
        },
        addNewChapter(){
            this.modalTitle = "Add Session";
            this.chapterTitle = null;
            this.chapterId = null;
            this.order_number = this.chapters.length + 1 ;
            this.modalType = "new";
            this.showModal = true;
            this.errors = {};
        },
        editChapter(chapter){
            console.log(chapter);
            this.errors = {};
            this.chapterId = chapter.id;
            this.modalTitle = "Update Session";
            this.chapterTitle = chapter.title;
            this.order_number = chapter.order_number;
            this.status = chapter.status;
            this.modalType = "edit";
            this.showModal = true;
        },
        deleteChapter(chapter){
            Swal.fire( {
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#286090',
                confirmButtonText: 'Yes, delete it!'
            } ).then( ( result ) => {
                if ( result.value ) {
                    Nova.request().post( '/nova-vendor/manage-chapter/delete-chapter', {
                        chapterId: chapter.id,
                        program_id: this.courseId
                    } ).then( response => {
                        if ( response ) {

                            console.log( response );
                            this.showModal = false;
                            this.getChaptersDetail();
                            this.$toasted.show( 'Record has been Deleted!', { type: 'success' } );

                        }
                    } )
                }
            } )
        },
        storeChapter(e){
            e.prevent_default;
            this.errors = {};

            if( document.getElementById( "title" ).value == null ||  document.getElementById( "title" ).value == "")
            {
                this.errors.title = 'The title field is required';
            }

            if( Object.keys(this.errors).length != 0)
            {
                console.log(this.errors);
                return;
            }

            let submitBtn = document.getElementById( "submitBtn" ); // submit button for content type

            if(submitBtn.dataset.type == "new" ) {
                this.isSpinner = true;
                Nova.request().post( '/nova-vendor/manage-chapter/add-chapter', {
                    courseId: parseInt(this.courseId),
                    title: document.getElementById( "title" ).value,
                    order_number: this.order_number,
                    status: this.status
                } ).then( response => {
                    if ( response ) {
                        // this.scheduledDate = null;
                        this.isSpinner = false;
                        console.log( response );
                        this.showModal = false;
                        this.getChaptersDetail();
                        this.$toasted.show( 'New Session has been Inserted!', { type: 'success' } );

                    }
                } )
            }else{
                console.log('chapter id'+ parseInt(submitBtn.dataset.chapterid));
                console.log('course id'+ parseInt(this.courseId));
                console.log('title'+ document.getElementById( "title" ).value);
                console.log('Date' + this.scheduledDate);

                Nova.request().post( '/nova-vendor/manage-chapter/update-chapter', {
                    chapterid: parseInt(submitBtn.dataset.chapterid),
                    courseId: parseInt(this.courseId),
                    title: document.getElementById( "title" ).value,
                    status: this.status
                } ).then( response => {
                    if ( response ) {

                        console.log( response );
                        this.showModal = false;
                        this.getChaptersDetail();
                        this.$toasted.show( 'Session has been Updated!', { type: 'success' } );

                    }
                } )
            }
        },


        closeModal(){
            this.showModal = false;
        },
        updateList(){
            console.log(this.chapters);
            Nova.request().post( '/nova-vendor/manage-chapter/update-chapter-series', {
                chapters: this.chapters,
            } ).then( response => {
                if ( response ) {

                    console.log( response );
                    this.showModal = false;
                    this.getChaptersDetail();
                    this.$toasted.show( 'Record has been Updated!', { type: 'success' } );

                }
            } )
        },
        backToContent(){
            let url = '/dashboard/content-manager/'+this.courseId;
            window.location.href = url;
            console.log('Back');
        },
        capitalize(chapter)
        {
            return chapter.status[0] + chapter.status.substring(1).toLowerCase();
        },
        hasError(index)
        {
            return this.errors[index];
        }
    },
    data(){
          return {
            courseId: null,
            courseTitle: null,
            status: 'PUBLISHED',
            order_number: null,
            chapters: [],
            chapterId: null,
            chapterTitle: null,
            scheduledDate: null,
            isCourseSchedule: false,
            showModal: false,
            modalTitle: null,
            modalType: null,
            chapterNotFound: false,
            config:{
                altInput: true,
                enableTime: true,
            },
            spinner: '/images/spinner2.gif',
            isSpinner:false,
            errors: {}
        }
    },
    mounted() {
        this.getChaptersDetail();
    },
}
</script>

<style>
/* Scoped Styles */
</style>
