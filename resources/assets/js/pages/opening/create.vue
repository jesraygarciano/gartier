<template>
  <div style="padding-bottom: 100px;">
    <wizard ref="wizard" :currentPanel="0">
      <template slot="steps">
        <div class="step-container" name="Basic Info">
          <div class="step">
            <iconized-photo size="medium-icon" :photo="public_path+'/images/info-sign.png'"></iconized-photo>
          </div>
        </div>
        <div class="step-container" name="Description">
          <div class="step">
            <iconized-photo size="medium-icon" :photo="public_path+'/images/opening-description.png'"></iconized-photo>
          </div>
        </div>
        <div class="step-container" name="Skills">
          <div class="step">
            <iconized-photo size="medium-icon" :photo="public_path+'/images/code.png'"></iconized-photo>
          </div>
        </div>
        <div class="step-container" name="Complete">
          <div class="step">
            <iconized-photo size="medium-icon" :photo="public_path+'/images/check.png'"></iconized-photo>
          </div>
        </div>
      </template>
      <template slot="panels">
        <form @submit.prevent="validateForm1" @keydown="form1.onKeydown($event)">

          <div class="text-center">
            <img ref="opening-picture" v-on:click="showPhotoEditor" :src="public_path+'/images/photo.png'" class="rounded img-thumbnail" width="200px">
          </div>
          <br>
          <!-- Title -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">Position / Title</label>
            <div class="col-md-7">
              <input v-model="form1.title" :class="{ 'is-invalid': form1.errors.has('title') }" class="form-control" name="title">
              <has-error :form="form1" field="title"/>
            </div>
          </div>

          <!-- Salary Range -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">Salary Range</label>
            <div class="col-md-7">
              <select v-model="form1.salary_range" :class="{ 'is-invalid': form1.errors.has('salary_range') }" class="form-control" name="salary_range">
                <option value="" selected>-select</option>
                <option v-for="(range, index) in salary_ranges" v-bind:key="index" :value="index">
                  {{range}}
                </option>
              </select>
              <has-error :form="form1" field="salary_range"/>
            </div>
          </div>

          <!-- Years of Experience -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">Years of Experience</label>
            <div class="col-md-7">
              <select v-model="form1.professional_years" :class="{ 'is-invalid': form1.errors.has('professional_years') }" class="form-control" name="professional_years">
                <option value="" selected>-select</option>
                <option v-for="(range, index) in work_experiences" v-bind:key="index" :value="index">
                  {{range}}
                </option>
              </select>
              <has-error :form="form1" field="professional_years"/>
            </div>
          </div>

          <!-- Hiring Processes/Procedures -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">Hiring Procedure</label>
            <div class="col-md-7">
              <select v-model="form1.hiring_step_group_id" :class="{ 'is-invalid': form1.errors.has('hiring_step_group_id') }" class="form-control" name="hiring_step_group_id">
                <option value="" selected>-select</option>
                <option v-for="(procedure, index) in hiringProcesses" v-bind:key="index" :value="procedure.id">
                  {{procedure.name}}
                </option>
              </select>
              <has-error :form="form1" field="hiring_step_group_id"/>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="form-group row">
            <div class="col-md-9 ml-md-auto">
              <v-button :loading="form1.busy" type="success">Next</v-button>
            </div>
          </div>
        </form>
        <form @submit.prevent="validateForm2" @keydown="form2.onKeydown($event)">
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">Details</label>
            <div class="col-md-7">
              <textarea rows="5" v-model="form2.details" :class="{ 'is-invalid': form2.errors.has('details') }" class="form-control" name="details">
              </textarea>
              <has-error :form="form2" field="details"/>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="form-group row">
            <div class="col-md-9 ml-md-auto">
              <a href="JavaScript:void(0)" class="btn btn-secondary" v-on:click="left">Back</a>
              <v-button :loading="form2.busy" type="success">Next</v-button>
            </div>
          </div>
        </form>
        <form @submit.prevent="validateForm3" @keydown="form3.onKeydown($event)">
          <skill-selector :form="form3">
            <!-- Submit Button -->
            <div class="col-md-12">
              <div class="form-group" style="margin-top: 30px; border-top: 1px solid #d5d5d5; padding-top: 30px;">
                <a href="JavaScript:void(0)" class="btn btn-secondary" v-on:click="left">Back</a>
                <v-button :loading="form3.busy" type="success">Next</v-button>
              </div>
            </div>
          </skill-selector>
        </form>
        <form @submit.prevent="validateForm4">

          <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Well done!</h4>
            <p>Your new Opening post is ready to be submitted.</p>
            <hr>
            <p class="mb-0">Click Submit button.</p>
          </div>
          
          <!-- Submit Button -->
          <div class="form-group row" style="margin-top: 30px;">
            <div class="col-md-12" style="text-align:center;">
              <a href="JavaScript:void(0)" class="btn btn-secondary" v-on:click="left">Back</a>
              <v-button :loading="form4.busy" type="success">Submit</v-button>
            </div>
          </div>
        </form>
      </template>
    </wizard>
    <profile-picture-modal ref="photo-editor" @update="updatePhoto"/>
  </div>
</template>

<script>
import Form from 'vform'
import axios from 'axios'
import ProfilePictureModal from '~/components/photo-editors/profilePictureModal'
import SkillSelector from './../../components/SkillSelector'
export default {
  middleware: 'auth',
  scrollToTop: false,
  components: {
    SkillSelector,
    ProfilePictureModal
  },

  metaInfo () {
    return { title: 'Create Company' }
  },

  data: () => ({
    form1: new Form({
      photo: null,
      title: '',
      salary_range: '',
      professional_years: '',
      hiring_step_group_id:''
    }),
    form2: new Form({
      details: ''
    }),
    form3: new Form({
      skills : {
        programming_languages : [],
        technologies: []
      }
    }),
    form4: new Form({
      company_id: '',
      title: '',
      salary_range: '',
      professional_years: '',
      details: '',
      skills: '',
      hiring_step_group_id:'',
    }),
    public_path: location.origin,
    salary_ranges: window.config.salary_ranges,
    work_experiences: window.config.work_experiences,
    hiringProcesses:[],
  }),

  methods: {
    updatePhoto(photo_data){
      this.form1.photo = photo_data;
      this.$refs['opening-picture'].src = photo_data;
    },
    showPhotoEditor(){
      this.$refs['photo-editor'].prepUpdate(this.form1.photo ? this.form1.photo : this.public_path+'/images/photo.png');
    },
    async validateForm1 () {
      console.log(this.form1);
      const {data} = await this.form1.post('/api/opening/validate/basicInfo')
      this.$refs.wizard.next();
    },
    async validateForm2 () {
      const {data} = await this.form2.post('/api/opening/validate/description')
      this.$refs.wizard.next();
    },
    async validateForm3 () {
      this.form3.busy = true;
      var $this = this;
      setTimeout(function(){
        $this.form3.busy = false;
        $this.$refs.wizard.next();
      },100);
    },
    async validateForm4 () {
      // 
      this.form4.company_id = this.$route.params.company_id;
      this.form4.picture = this.form1.photo;
      this.form4.title = this.form1.title;
      this.form4.salary_range = this.form1.salary_range;
      this.form4.professional_years = this.form1.professional_years;
      this.form4.hiring_step_group_id = this.form1.hiring_step_group_id;
      // 
      this.form4.details = this.form2.details;
      // 
      this.form4.skills = this.form3.skills;

      console.log(this.form3);
      console.log(this.form4);
      // 
      await this.form4.post('/api/opening/create');

      this.$router.push("/company/profile/"+this.$route.params.company_id);
    },
    right(){
      this.$refs.wizard.next();
    },
    left(){
      this.$refs.wizard.previews();
    }
  },
  created: async function(){
    const { data } = await axios({
          method: 'get',
          url: '/api/company/hiringprocess/fetch/processes',
          params: { company_id: this.$route.params.company_id }
        })
    this.hiringProcesses = data.hiringProcesses;
  }
}
</script>
