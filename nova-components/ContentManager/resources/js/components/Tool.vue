<template>
  <div>
        <card class="flex flex-col">
            <div class="row course-detail-section">
                <div class="mb-12">
                    <div class="row" style="display: flex;flex-direction:column;align-items:center;align-items: center; margin-top: 10px;">
                        <h3 class="mr-3 text-80 font-bold"><i class="fa fa-video"></i> Manage Program - {{ courseTitle }}</h3>
                        <div>
                            <a :href="'/dashboard/resources/programs/'+course_id+'/edit'" class="cursor-pointer btn btn-default btn-primary" style="margin-right: 10px; margin-top: 5px;">Edit Program Detail</a>
                        <a :href="'/dashboard/manage-chapter/' + course_id + '/' + courseTitle" class="cursor-pointer btn btn-default btn-primary" style="margin-right: 10px; margin-top: 5px;">Manage Sessions</a>
                        </div>

                    </div>
                    <div v-if="chapterNotFound" class="row" style="display: flex; margin-top: 70px;">
                        <h1 class="mr-3 text-base text-80 font-bold m-8 mt-4" style="margin: 0 auto; font-size: 70px;margin-bottom: 60px;">No record Found!</h1>

                    </div>
                    <div class="row" style="margin: 20px;">
                        <div class="flex flex-wrap -mb-4 course-content-list" style="padding-right: 20px; padding-left: 20px; margin-top: 20px;">
                            <div class="w-1/3 mb-4" v-for="(chapter,index) in chapters" style="float: right; border-bottom: 1px solid #e3e3e3; " :key="index">
                                <div class="custom-border-left" style="border-right: 1px solid #e3e3e3">
                                    <h3 class="text-center" style="padding-top: 10px; padding-bottom: 10px;">{{ chapter.title }}</h3>
                                    <ul class="list-group drag" style="padding: 0 20px; min-height: 20px;">
                                        <draggable v-model="chapters[index].contents" :options="{group:'chapter.contents'}" @end="updateList" >
                                            <li class="list-group-item item" v-for="(content,j) in chapter.contents" :key="j" >
                                                <template > <!-- v-if="content.file"  -->
                                                    <i class="fa fa-paperclip" data-type="attachment"></i>&#160;
                                                    <span class="item-title">{{ content.title }}</span>
                                                    <button ref="theDeleteBtn" class="btn btn-primary pull-left chapter-content-Delete btn-content-delete" v-on:click="deleteCourseContent(content)">Delete</button>
                                                    <button class="btn btn-sm btn-danger pull-left chapter-content-Delete" @click="editContent(content, 'attachment')" style="cursor: pointer;">Edit</button>
                                                </template>
                                                <!-- <template v-else-if="content.link">
                                                    <i class="fa fa-share" data-type="hyperlink"></i>&#160;
                                                    <span class="item-title">{{ content.title }}</span>
                                                    <button ref="theDeleteBtn" class="btn btn-primary pull-left chapter-content-Delete btn-content-delete" v-on:click="deleteCourseContent(content, 2)">Delete</button>
                                                    <button class="btn btn-sm btn-danger pull-left chapter-content-Delete" @click="editContent(content, 'hyperlink')" style="cursor: pointer;">Edit</button>
                                                </template>
                                                <template v-else-if="content.vimeo_id">
                                                    <i class="fa fa-video" data-type="video"></i>&#160;
                                                    <span class="item-title">{{ content.title }}</span>
                                                    <button ref="theDeleteBtn" class="btn btn-primary pull-left chapter-content-Delete btn-content-delete" v-on:click="deleteCourseContent(content, 1)">Delete</button>
                                                    <button class="btn btn-sm btn-danger pull-left chapter-content-Delete" @click="editContent(content, 'video')" style="cursor: pointer;">Edit</button>
                                                </template> -->
                                            </li>
                                        </draggable>
                                        <h5 v-if="chapters[index].contents == 0" class="text-center" style="padding-top: 10px;">No Contents yet</h5>
                                    </ul>
                                    <div class="btn-group text-center" style="margin-top:20px; margin-bottom: 20px;">
                                        <!--<button class="btn btn-primary custom-btn-primary" v-on:click="addContent(chapter, 3)">Add Attachment</button>-->
                                        <!-- <button class="btn btn-primary custom-btn-primary" @click="addNew(chapter.id, 'attachment')">Add Attachment</button> -->
                                        <button class="btn btn-warning custom-btn-warning" @click="addNew(chapter , 'Add Content')">Add Content</button>
                                        <!-- <button class="btn btn-info custom-btn-success" @click="addNew(chapter.id, 'video')">Add Video</button> -->
                                    </div>
                                </div>
                            </div>
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
                                    <h2 class="border-b border-40 py-8 px-8 text-90 font-normal text-xl">{{ modalTitle }}</h2>
                                    <!--<input type="hidden" id="content-type" :value="modalType">-->
                                    <div class="action">
                                        <div class="flex border-b border-40">
                                            <div class="w-1/5 py-6 px-8 text-left">
                                                <label class="inline-block text-80 pt-2 leading-tight">Session</label>
                                            </div>
                                            <div class="py-6 px-8 w-1/2 text-left" style="padding-top: 30px;">
                                                <select v-model="editChapterContent.chapter_id" class="w-full form-control form-select" id="session" disabled>
                                                    <option v-for="(chapter,i) in chapters" :value="chapter.id" :key="i" >{{chapter.title}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="action">
                                        <div class="flex border-b border-40">
                                            <div class="w-1/5 py-6 px-8 text-left">
                                                <label class="inline-block text-80 pt-2 leading-tight">Title</label>
                                            </div>
                                            <div class="py-6 px-8 w-1/2">
                                                <input v-model="editChapterContent.title" type="text" id="title" placeholder="Title" class="w-full form-control form-input form-input-bordered" required>
                                                <span class="help-text help-text mt-2 text-danger" v-if="hasError('title')" v-text="hasError('title')"></span>
                                            </div>
                                        </div>
                                    </div>

                                       <div class="action">
                                            <div class="flex border-b border-40">
                                            <div class="w-1/5 py-6 px-8 text-left">
                                                <label class="inline-block text-80 pt-2 leading-tight">Duration</label>
                                            </div>
                                            <div class="py-6 px-8 w-1/2 text-left" style="padding-top: 30px;">
                                                <!-- <flat-pickr
                                                        v-model="scheduledDate"
                                                        :config="config"
                                                        class="w-full form-control form-input-bordered"
                                                        placeholder="Select date"
                                                        name="date">
                                                </flat-pickr> -->
                                                <div class="w-full">
                                                    <vue-timepicker v-model="editChapterContent.duration"></vue-timepicker>
                                                </div>
                                                <span class="help-text help-text mt-2 text-danger" v-if="hasError('duration')" v-text="hasError('duration')"></span>
                                            </div>
                                        </div>
                                    </div>

                                     <div class="action">
                                            <div class="flex border-b border-40">
                                                <div class="w-1/5 py-6 px-8 text-left">
                                                    <label class="inline-block text-80 pt-2 leading-tight">Type</label>
                                                </div>
                                                <div class="py-6 px-8 w-1/2 text-left" style="padding-top: 30px;">
                                                    <select v-model="editChapterContent.type" class="w-full form-control form-select" id="e-attachment_type">
                                                        <option value="FILE" selected="selected">File</option>
                                                        <option value="VIDEO">Video</option>

                                                    </select>
                                                </div>
                                            </div>
                                    </div>

                                    <template v-if="editChapterContent.type === 'VIDEO'">
                                        <div class="action">
                                            <div class="flex border-b border-40">
                                                <div class="w-1/5 py-6 px-8 text-left">
                                                    <label class="inline-block text-80 pt-2 leading-tight">Vimeo Id</label>
                                                </div>
                                                <div class="py-6 px-8 w-1/2">
                                                    <input v-model="editChapterContent.url" type="text" id="e-vimeo-id" placeholder="Vimeo Id" class="w-full form-control form-input form-input-bordered">
                                                    <span class="help-text help-text mt-2 text-danger" v-if="hasError('url')" v-text="hasError('url')"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <template v-if="editChapterContent.type === 'FILE'">
                                        <div class="action">
                                            <div class="flex border-b border-40">
                                                <div class="w-1/5 py-6 px-8 text-left">
                                                    <label class="inline-block text-80 pt-2 leading-tight">File</label>
                                                </div>
                                                <div class="py-6 px-8 w-1/2">
                                                    <span class="form-file mr-4" style="float: left;">
                                                        <input @change="checkImage" type="file" id="e-file_image" name="name">
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <span class="help-text help-text mt-2 text-danger" v-if="hasError('file')" v-text="hasError('file')"></span>
                                                <span class="help-text help-text mt-2 text-danger" v-if="hasError('ext')" v-text="hasError('ext')"></span>
                                            </div>
                                        </div>

                                    </template>

                                </div>
                                <div class="bg-30 px-6 py-3 flex">
                                    <div class="flex items-center ml-auto">
                                        <button @click="closeModal" type="button" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link" style="border-radius: .5rem;background-color: #868686; color: var(--white); margin-right: 10px;">
                                            Cancel
                                        </button>
                                        <button v-if="!isSpinner" type="submit"  @click="storeContent" id="add-record" :data-type="modalType" :data-chapter="contentChapterId" class="btn btn-default btn-primary">
                                            <span>Add Record</span>
                                        </button>
                                          <div v-if="isSpinner" style="width: 155px;text-align:center;overflow: hidden;">
                                            <img style="width:35px;transform:scale(1.2);" v-if="isSpinner" :src="spinner">
                                        </div>
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
                                        <div class="flex border-b border-40">
                                            <div class="w-1/5 py-6 px-8 text-left">
                                                <label class="inline-block text-80 pt-2 leading-tight">Sessions</label>
                                            </div>
                                            <div class="py-6 px-8 w-1/2 text-left" style="padding-top: 30px;">
                                                <select v-model="editChapterContent.chapter_id" class="w-full form-control form-select" id="e-chapter" disabled>
                                                    <option v-for="(chapter,i) in chapters" :value="chapter.id" :selected="chapter.id == editChapterContent.chapter_id ? 'selected' : ''" :key="i">{{chapter.title}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="action">
                                        <div class="flex border-b border-40">
                                            <div class="w-1/5 py-6 px-8 text-left">
                                                <label class="inline-block text-80 pt-2 leading-tight">Title</label>
                                            </div>
                                            <div class="py-6 px-8 w-1/2">
                                                <input type="text" id="e-title" placeholder="Title" v-model="editChapterContent.title" class="w-full form-control form-input form-input-bordered">
                                                 <span class="help-text help-text mt-2 text-danger" v-if="hasError('title')" v-text="hasError('title')"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="action">
                                        <div class="flex border-b border-40">
                                            <div class="w-1/5 py-6 px-8 text-left">
                                                <label class="inline-block text-80 pt-2 leading-tight">Is Free</label>
                                            </div>
                                            <div class="py-6 px-8 w-1/2 text-left" style="padding-top: 30px;">
                                                <input type="checkbox" id="e-isFree" name="is_free" style="width: 20px; height: 20px;" :checked="editChapterContent.is_free == 1 ? 'checked' : ''">
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="action">
                                            <div class="flex border-b border-40">
                                            <div class="w-1/5 py-6 px-8 text-left">
                                                <label class="inline-block text-80 pt-2 leading-tight">Duration</label>
                                            </div>
                                            <div class="py-6 px-8 w-1/2 text-left" style="padding-top: 30px;">
                                                <!-- <flat-pickr
                                                        v-model="scheduledDate"
                                                        :config="config"
                                                        class="w-full form-control form-input-bordered"
                                                        placeholder="Select date"
                                                        name="date">
                                                </flat-pickr> -->
                                                <vue-timepicker v-model="editChapterContent.duration"></vue-timepicker>
                                                <span class="help-text help-text mt-2 text-danger" v-if="hasError('duration')" v-text="hasError('duration')"></span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="action">
                                            <div class="flex border-b border-40">
                                                <div class="w-1/5 py-6 px-8 text-left">
                                                    <label class="inline-block text-80 pt-2 leading-tight">Type</label>
                                                </div>
                                                <div class="py-6 px-8 w-1/2 text-left" style="padding-top: 30px;">
                                                    <select v-model="editChapterContent.type" class="w-full form-control form-select" id="e-attachment_type">
                                                        <option value="FILE" selected="selected">File</option>
                                                        <option value="VIDEO">Video</option>

                                                    </select>
                                                </div>
                                            </div>
                                    </div>

                                    <template v-if="editChapterContent.type === 'VIDEO'">
                                        <div class="action">
                                            <div class="flex border-b border-40">
                                                <div class="w-1/5 py-6 px-8 text-left">
                                                    <label class="inline-block text-80 pt-2 leading-tight">Vimeo Id</label>
                                                </div>
                                                <div class="py-6 px-8 w-1/2">
                                                    <input v-model="editChapterContent.url" type="text" id="e-vimeo-id" placeholder="Vimeo Id" class="w-full form-control form-input form-input-bordered">
                                                     <span class="help-text help-text mt-2 text-danger" v-if="hasError('url')" v-text="hasError('url')"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <template v-if="editChapterContent.type === 'FILE'">
                                        <div class="action">
                                            <div class="flex border-b border-40">
                                                <div class="w-1/5 py-6 px-8 text-left">
                                                    <label class="inline-block text-80 pt-2 leading-tight">File</label>
                                                </div>
                                                <div class="py-6 px-8 w-1/2">
                                                    <span v-if="editChapterContent.data || editChapterContent.data !=''" class="form-file mr-4" style="float: left;">
                                                        <h6 style="cursor: pointer" @click="openFile(editChapterContent)">{{editChapterContent.file_name}}</h6>
                                                        <button @click="deleteFile(editChapterContent.id)" type="button" class="btn btn-warning custom-btn-warning" >Delete</button>
                                                    </span>
                                                      <span v-else class="form-file mr-4" style="float: left;">
                                                        <input @change="checkImage" type="file" id="e-file_image" name="name">
                                                    </span>
                                                </div>

                                            </div>
                                              <div class="text-center">
                                                <span class="help-text help-text mt-2 text-danger" v-if="hasError('file')" v-text="hasError('file')"></span>
                                                <span class="help-text help-text mt-2 text-danger" v-if="hasError('ext')" v-text="hasError('ext')"></span>
                                            </div>
                                        </div>

                                    </template>

                                </div>
                                <div class="bg-30 px-6 py-3 flex">
                                    <div class="flex items-center ml-auto">
                                        <button @click="closeModal" type="button" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link" style="border-radius: .5rem;background-color: #868686; color: var(--white); margin-right: 10px;">
                                            Cancel
                                        </button>
                                        <button v-if="!isSpinner" @click="updateContent" type="submit"  id="e-add-record" :data-type="modalType" :data-contentId="editChapterContent.id" :data-chapter="editChapterContent.chapter_id" class="btn btn-default btn-primary">
                                            <span>Update Record</span>
                                        </button>
                                        <div v-if="isSpinner" style="width: 155px;text-align:center;overflow: hidden;">
                                            <img style="width:35px;transform:scale(1.2);" v-if="isSpinner" :src="spinner">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="fixed pin bg-80 z-20 opacity-75"></div>
            </template>
        </card>
    </div>
</template>

<script>
window.Laravel = { csrfToken: '{{ csrf_token() }}' };
import Swal from 'sweetalert2';
import draggable from 'vuedraggable';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import VueTimepicker from 'vue2-timepicker/src/vue-timepicker.vue'

export default {
    components: {
        draggable,
        flatPickr,
        VueTimepicker
        // Edit,
    },
    data() {
        return {
            course_id: null,
            courseTitle: null,
            chapters: null,
            chapterNotFound:false,
            errors: {},
            // isScheduleContent: false,

            // For Modals

            showModal: false,
            // scheduledDate: null,
            config:{
                "disable": [
                    function(date) {
                        // return true to disable
                        return true;

                    }
                ],
                enableTime: true
            },
            showEditModal: false,
            contentChapterId: null,
            editChapterContent: {
                 attachmentFile: [],
            },
            chapterId: null,
            modalTitle: null,
            modalType: null,
            dragDropData: null,
            spinner: '/images/spinner2.gif',
            isSpinner:false,


        };
    },
    methods: {

        checkImage( e ) {
            let files = e.target.files || e.dataTransfer.files;
            this.editChapterContent.attachmentFile = files;
        },

        getCourseDetail: function () {
            let programId = this.$route.params.programId;
            this.course_id = programId;
            Nova.request().get( '/nova-vendor/content-manager/program/' + this.course_id).then( response => {
                console.log(response);
                this.courseTitle = response.data.detail.title;
                this.chapters = response.data.detail.chapter;

                // // if(response.data.detail.type === "SCHEDULE_CONTENT"){
                // //     this.isScheduleContent = true;
                // // }
                console.log('rendering...');
                console.log(response);
                if(this.chapters.length < 1)
                    this.chapterNotFound = true;
            } )
        },

        // open edit content modal
        editContent( content, type ) {

            console.log( content );
            this.errors = {};

            this.editChapterContent = content;
            this.editChapterContent.duration = {
                 HH: content.hours,
                 mm: content.mins,
            }
            this.editChapterContent.attachmentFile = [];
            // this.scheduledDate = content.scheduled_date;
            this.modalType = type;

            // check type and validate form field
            // if ( type === "attachment" ) {

            this.modalTitle = "Edit " + content.title;
            this.showEditModal = true;

            // }
        },

        deleteCourseContent: function ( data ) {
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
                    let contentType = data.type;

                    Nova.request().get( '/nova-vendor/content-manager/delete-content/' + data.id + '/type/' + contentType ).then( response => {
                        if ( response.data.result ) {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            this.getCourseDetail();
                        }
                    } )
                }
            } )
        },

        closeModal() {
            this.isSpinner = false;
            this.showModal = false;
            this.showEditModal = false;
            this.editChapterContent.attachmentFile = [];
        },

         // open add new content modal
        addNew( chapter, type ) {

            this.contentChapterId = chapter.id;
            this.modalType = 'Add';
            this.modalTitle = type;
            this.showModal = true;
            this.errors = {};
            this.editChapterContent = {
                title: null,
                type: 'FILE',
                url: null,
                duration: {
                    HH: '00',
                    mm: '00'
                },
                chapter_id: chapter.id,
                order_number: chapter.contents.length,
                attachmentFile: []
            }
            console.log(this.editChapterContent);
            // check type and validate form field




        },

        updateList( e ) {
            console.log("Fired");
            console.log(this.chapters);
            Nova.request().post( '/nova-vendor/content-manager/update-drag-drop-detail', {
                chapters: this.chapters,
            } ).then( response => {
                if ( response ) {
                    this.getCourseDetail();
                    this.$toasted.show('Program contents has been updated', { type: 'success' });
                }
            } )
        },

         // form store method
        storeContent( evt ) {

            evt.preventDefault();
            this.errors = {};


            if (this.editChapterContent.title == null || this.editChapterContent.title == '') {
                 this.errors.title = 'The title field is required';
            }

            if (this.editChapterContent.duration.HH == '00' && this.editChapterContent.duration.mm  == '00') {
                 this.errors.duration = 'Please specify a duration for this content';
            }


            // let submitBtn = document.getElementById( "add-record" ); // submit button for content type


            // validation on form type and get specific data
            if ( this.editChapterContent.type === "FILE" ) {
                 this.isSpinner = true;
                if(this.editChapterContent.data == null)
                {
                    if (this.editChapterContent.attachmentFile.length == 0) {
                        this.errors.file = 'The file is required';
                    }

                    if (typeof this.editChapterContent.attachmentFile[0] !== 'undefined' && !(this.editChapterContent.attachmentFile[0].type == 'application/pdf' || this.editChapterContent.attachmentFile[0].type == 'image/png' || this.editChapterContent.attachmentFile[0].type == 'image/jpeg' || this.editChapterContent.attachmentFile[0].type == 'application/msword' || this.editChapterContent.attachmentFile[0].type == 'text/csv' || this.editChapterContent.attachmentFile[0].type == 'text/csv' )) {
                        this.errors.ext = 'Supported file formats are JPEG, PNG, PDF, CSV, DOC.';
                    }
                }

                if( Object.keys(this.errors).length != 0)
                {
                    this.isSpinner  = false;
                    console.log(this.errors);
                    return;
                }

                let data = new FormData();


                data.append( "course_id", this.course_id );
                data.append( "chapterId", this.editChapterContent.chapter_id );
                data.append( "title", this.editChapterContent.title );
                // data.append("file", document.getElementById('file_image').files[0].value);
                data.append( "file", this.editChapterContent.attachmentFile[ 0 ] );
                data.append( "type", this.editChapterContent.type );
                data.append( "duration", this.editChapterContent.duration.HH + 'h ' + this.editChapterContent.duration.mm + 'm'  );
                data.append( "hours", this.editChapterContent.duration.HH );
                data.append( "mins", this.editChapterContent.duration.mm );
                data.append( "order_number", this.editChapterContent.order_number );

                // this.isSpinner = true;
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                axios.post( '/nova-vendor/content-manager/add-attachment', data, config ).then( response => {
                    if (response) {
                        // this.isSpinner = false;
                        this.showModal = false;
                        this.$toasted.show( 'Program content has been updated', { type: 'success' } );
                        this.getCourseDetail();
                    }
                }).catch(error => {
                    this.isSpinner = false;
                    if (error.response) {
                        // console.log(error.response.data);
                        // console.log(error.response.status);
                        // console.log(error.response.headers);
                        if(error.response.status===422){
                            this.errors = error.response.data.errors;
                            this.$toasted.show('Some Fields are missing', {type: 'error'});
                        }
                        else if(error.response.status===413){
                            this.$toasted.show('Some fields may be invalid or file size may be too large', {type: 'error'});
                        }
                        else{
                            this.$toasted.show('Server Error', {type: 'error'});
                        }
                    }
                    else if (error.request) {
                        // The request was made but no response was received
                        // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                        // http.ClientRequest in node.js
                        console.log('request:',error.request);
                        this.$toasted.show('Server not responding', {type: 'error'});
                    }
                    else {
                        // Something happened in setting up the request that triggered an Error
                        console.log('else Error', error.message);
                        this.$toasted.show(error.message || 'Error in request, please contact to support', {type: 'error'});
                    }
                })}

            else if ( this.editChapterContent.type === "VIDEO" ) {
                this.isSpinner = true;

                if (this.editChapterContent.url == null || this.editChapterContent.url == '') {
                    this.errors.url =  'The vimeo Id is requried';
                }

                if( Object.keys(this.errors).length != 0)
                {
                    this.isSpinner = false;
                    return;
                }
                // Update Record
                Nova.request().post( '/nova-vendor/content-manager/add-video', {
                    courseId: this.course_id,
                    chapterId: this.editChapterContent.chapter_id,
                    title: this.editChapterContent.title,
                    vimeoId: this.editChapterContent.url,
                    type: this.editChapterContent.type,
                    order_number: this.editChapterContent.order_number,
                    // is_free: is_free,
                    duration:  this.editChapterContent.duration.HH + 'h ' + this.editChapterContent.duration.mm + 'm' ,
                    hours: this.editChapterContent.duration.HH,
                    mins: this.editChapterContent.duration.mm
                    // scheduledDate: this.scheduledDate,
                } ).then( response => {
                    if ( response ) {
                        console.log( response );
                        this.isSpinner = false;
                        this.showModal = false;
                        this.$toasted.show( 'Record has been Inserted!', { type: 'success' } );
                        this.getCourseDetail();
                    }
                } ).catch(error => {
                    this.isSpinner = false;
                    if (error.response) {
                        // console.log(error.response.data);
                        // console.log(error.response.status);
                        // console.log(error.response.headers);
                        if(error.response.status===422){
                            this.errors = error.response.data.errors;
                            this.$toasted.show('Some Fields are missing', {type: 'error'});
                        }
                        else if(error.response.status===413){
                            this.$toasted.show('Some fields may be invalid or file size may be too large', {type: 'error'});
                        }
                        else{
                            this.$toasted.show('Server Error', {type: 'error'});
                        }
                    }
                    else if (error.request) {
                        // The request was made but no response was received
                        // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                        // http.ClientRequest in node.js
                        console.log('request:',error.request);
                        this.$toasted.show('Server not responding', {type: 'error'});
                    }
                    else {
                        // Something happened in setting up the request that triggered an Error
                        console.log('else Error', error.message);
                        this.$toasted.show(error.message || 'Error in request, please contact to support', {type: 'error'});
                    }
                })
            }
        },

        updateContent: function(e) {

            e.preventDefault();
            this.isSpinner = true;
             this.errors = {};

            if (this.editChapterContent.title == null || this.editChapterContent.title == '') {
                 this.errors.title = 'The title field is required';
            }

            if (this.editChapterContent.duration.HH == '00' && this.editChapterContent.duration.mm  == '00') {
                 this.errors.duration = 'Please specify a duration for this content';
            }

            // let content_id = submitBtn.dataset.contentid;
            // console.log( document.getElementById( "e-chapter" ).value );

            // validation on form type and get specific data
            if ( this.editChapterContent.type === "FILE" ) {

                if(this.editChapterContent.data == null || this.editChapterContent.data == "")
                {
                    if (this.editChapterContent.attachmentFile.length == 0) {
                        this.errors.file = 'The file is required';
                    }

                    if (typeof this.editChapterContent.attachmentFile[0] !== 'undefined' && !(this.editChapterContent.attachmentFile[0].type == 'application/pdf' || this.editChapterContent.attachmentFile[0].type == 'image/png' || this.editChapterContent.attachmentFile[0].type == 'application/msword' || this.editChapterContent.attachmentFile[0].type == 'text/csv' || this.editChapterContent.attachmentFile[0].type == 'text/csv' )) {
                        this.errors.ext = 'Supported file formats are JPEG, PNG, PDF, DOC, CSV.';
                    }
                }

                if( Object.keys(this.errors).length != 0)
                {
                    console.log(this.errors);
                    this.isSpinner = false;
                    return;
                }

                let data = new FormData();
                data.append( "chapterId", this.editChapterContent.chapter_id );
                data.append( "contentId", this.editChapterContent.id );
                data.append( "title", this.editChapterContent.title );
                // data.append("file", document.getElementById('file_image').files[0].value);
                data.append( "file", this.editChapterContent.attachmentFile[ 0 ] );
                // data.append( "is_free", is_free );
                data.append( "type", this.editChapterContent.type);
                data.append( "duration", this.editChapterContent.duration.HH + 'h ' + this.editChapterContent.duration.mm + 'm'  );
                data.append( "hours", this.editChapterContent.duration.HH );
                data.append( "mins", this.editChapterContent.duration.mm );
                data.append( "order_number", this.editChapterContent.order_number );
                // this.isSpinner = true;
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                axios.post( '/nova-vendor/content-manager/update-attachment', data, config ).then( response => {
                    if ( response ) {
                        console.log( response );
                        this.isSpinner = false;
                        this.showEditModal = false;
                        this.$toasted.show( 'Program content has been updated', { type: 'success' } );
                        this.getCourseDetail();
                    }
                } ).catch(error => {
                    this.isSpinner = false;
                    if (error.response) {
                        // console.log(error.response.data);
                        // console.log(error.response.status);
                        // console.log(error.response.headers);
                        if(error.response.status===422){
                            this.errors = error.response.data.errors;
                            this.$toasted.show('Some Fields are missing', {type: 'error'});
                        }
                        else if(error.response.status===413){
                            this.$toasted.show('Some fields may be invalid or file size may be too large', {type: 'error'});
                        }
                        else{
                            this.$toasted.show('Server Error', {type: 'error'});
                        }
                    }
                    else if (error.request) {
                        // The request was made but no response was received
                        // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                        // http.ClientRequest in node.js
                        console.log('request:',error.request);
                        this.$toasted.show('Server not responding', {type: 'error'});
                    }
                    else {
                        // Something happened in setting up the request that triggered an Error
                        console.log('else Error', error.message);
                        this.$toasted.show(error.message || 'Error in request, please contact to support', {type: 'error'});
                    }
                })

            }
             else if ( this.editChapterContent.type === "VIDEO" ) {


                if (this.editChapterContent.url == null || this.editChapterContent.url == '') {
                    this.errors.url =  'The vimeo Id is requried';
                }

                if(Object.keys(this.errors).length != 0)
                {
                    return;
                }

                Nova.request().post( '/nova-vendor/content-manager/update-video', {

                    // chapterId: this.editChapterContent.chapter_id,
                    // title: this.editChapterContent.title,
                    // vimeoId: this.editChapterContent.url,
                    // // is_free: is_free,
                    // videoLength: this.editChapterContent.duratio,
                    // scheduledDate: this.scheduledDate,
                    chapterId: this.editChapterContent.chapter_id,
                    contentId: this.editChapterContent.id,
                    title: this.editChapterContent.title,
                    vimeoId: this.editChapterContent.url,
                    type: this.editChapterContent.type,
                    order_number: this.editChapterContent.order_number,
                    // is_free: is_free,
                    duration:  this.editChapterContent.duration.HH + 'h ' + this.editChapterContent.duration.mm + 'm' ,
                    hours: this.editChapterContent.duration.HH,
                    mins: this.editChapterContent.duration.mm
                } ).then( response => {
                    if ( response ) {
                        console.log( response );
                        this.isSpinner = false;
                        this.showEditModal = false;
                        this.$toasted.show( 'Record has been Inserted!', { type: 'success' } );
                        this.getCourseDetail();
                    }
                } ).catch(error => {
                    this.isSpinner = false;
                    if (error.response) {
                        // console.log(error.response.data);
                        // console.log(error.response.status);
                        // console.log(error.response.headers);
                        if(error.response.status===422){
                            this.errors = error.response.data.errors;
                            this.$toasted.show('Some Fields are missing', {type: 'error'});
                        }
                        else if(error.response.status===413){
                            this.$toasted.show('Some fields may be invalid or file size may be too large', {type: 'error'});
                        }
                        else{
                            this.$toasted.show('Server Error', {type: 'error'});
                        }
                    }
                    else if (error.request) {
                        // The request was made but no response was received
                        // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                        // http.ClientRequest in node.js
                        console.log('request:',error.request);
                        this.$toasted.show('Server not responding', {type: 'error'});
                    }
                    else {
                        // Something happened in setting up the request that triggered an Error
                        console.log('else Error', error.message);
                        this.$toasted.show(error.message || 'Error in request, please contact to support', {type: 'error'});
                    }
                })

            }
        },

        openFile(content){
            window.open('https://injaz-academy.fra1.digitaloceanspaces.com/' + content.data, '_blank', 'fullscreen=yes');
            return false;
        },

        deleteFile(id)
        {
             Nova.request().get( '/nova-vendor/content-manager/delete/' + id).then( response => {
                console.log(response);
                this.editChapterContent.data = "";
                this.editChapterContent.file_name = null;
                console.log(this.editChapterContent)
               // this.getCourseDetail();
            } )
        },

        hasError(index)
        {
            return this.errors[index];
        }



    },

    mounted() {
        this.getCourseDetail();
    },
}
</script>

<style>
.add-content-error {
    color: red;
    position: relative;
    top: 5px;
}
</style>
