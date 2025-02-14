<template>
    <div>
        <heading class="mb-6">Injaz Notifier</heading>

        <card
            class=" flex flex-col items-center justify-center"
            style="min-height: 300px"
        >
                    <div class="flex border-40">
                          <div class=" px-8 py-6 width-100 text-center">
                                <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                    To
                                </label>
                           </div>
                            <div class="py-6 px-8 width-350" >
                                <select v-model="payload.to" class="w-full form-control form-select" id="programId">
                                        <option value="All" selected>All Users</option>
                                        <option value="AllStudents" >All Students</option>
                                        <option value="AllMentors" >All Mentors</option>
                                        <!-- <option :value="program.id" v-for="(program,index) in programs">{{program.title}}</option> -->
                                </select>
                            </div>
                    </div>
                   <div class="flex border-40">
                                     <div class="width-100 text-center px-8 py-6" >
                                        <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                            Title
                                        </label>
                                    </div>
                                    <div class="py-6 px-8 width-350" >
                                        <div class="flex items-center">
                                            <input v-model="payload.title" name="title" id="title"  dusk="title" list="title-list" type="text" placeholder="Title" class="w-full form-control form-input form-input-bordered">
                                        </div>
                                        <span class="help-text help-text mt-2 text-danger" ></span>
                                    </div>
                    </div>
                   <div class="flex border-40 ">
                                <div class="flex ">
                                     <div class="width-100 text-center px-8 py-6" >
                                        <label for="user_id" class="inline-block text-80 pt-2 leading-tight">
                                            Body
                                        </label>
                                    </div>
                                    <div class="py-6 px-8 width-350" >
                                        <div class="flex items-center">
                                            <textarea v-model="payload.body" name="title" id="title"  dusk="title" list="title-list" type="text" placeholder="Enter Body Here..." class="w-full form-control form-input form-input-bordered"></textarea>
                                        </div>
                                        <span class="help-text help-text mt-2 text-danger" ></span>
                                    </div>
                                </div> 
                    </div>
                    <div class="flex border-b border-40 items-center">
                            <div class="py-6 px-3 ">
                                <!-- <div class="flex items-center"> -->
                                    <button
                                        @click="sendNotification()"
                                        type="button"
                                        class="btn btn-default btn-primary  width-200"
                                    >
                                       <p class="text-center">Send Notification</p>
                                    </button>
                                <!-- </div> -->
                            </div>

                        
                    </div>

        </card>
    </div>
</template>

<script>
 window.Laravel = {csrfToken: '{{ csrf_token() }}'};
export default {
    data(){
        return {
             payload: {
                to: 'All',
                title: null,
                body: null
            },
             formConfig: {headers: {"Content-Type": "application/json"}},
        }
    },
    methods: {
        sendNotification(){
            Nova.request().post('/nova-vendor/notifier/send',this.payload,this.formConfig).then(response => {
                    if(response.data.status=='success')
                    {
                        this.$toasted.show('Notification Sent', {type: 'success'});
                    }
                }).catch(error => {
                    
                    console.log(error);
                
                // if (error.response) {
                //     if(error.response.status==422){
                //         this.errors = error.response.data.errors;
                //         if(Object.keys(this.errors).length == 1)
                //         {
                //              this.$toasted.show('Please add question to submit the quiz', {type: 'error'});
                //         }
                //         else
                //             this.$toasted.show('Some Fields are missing', {type: 'error'});
                //     }
                //     // else if(error.response.status==413){
                //     //     this.$toasted.show('Some fields may be invalid or file size may be too large', {type: 'error'});
                //     // }
                //     else{
                //         this.$toasted.show('Server Error', {type: 'error'});
                //     }
                // }
                // else if (error.request) {
                //     console.log('request:',error.request);
                //     this.$toasted.show('Server not responding', {type: 'error'});
                // }
                // else {
                //     console.log('else Error', error.message);
                //     this.$toasted.show(error.message || 'Error in request, please contact to support', {type: 'error'});
                // }
                })
        }
    },  
    mounted() {
        //
    },
}
</script>

<style>
/* Scoped Styles */
</style>
