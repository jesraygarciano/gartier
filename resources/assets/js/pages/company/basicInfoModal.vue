<template>
  <div class="modal fade" ref="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <form @submit.prevent="update" @keydown="form.onKeydown($event)">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Basic Info</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              </div>
              <div class="modal-body">
                <div class="form-group row">
                  <label class="col-md-3 col-form-label text-md-right">Name</label>
                  <div class="col-md-7">
                    <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control" name="name" rows="10">
                    <has-error :form="form" field="name"/>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label text-md-right">Email</label>
                  <div class="col-md-7">
                    <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" name="email" rows="10">
                    <has-error :form="form" field="email"/>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label text-md-right">Address</label>
                  <div class="col-md-7">
                    <input v-model="form.address" :class="{ 'is-invalid': form.errors.has('address') }" class="form-control" name="address" rows="10">
                    <has-error :form="form" field="address"/>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <v-button :loading="form.busy" type="success">Save</v-button>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Form from 'vform'
export default {
  name: 'BasicInfoModal',

  props: {
    company: { type: Object, required: true },
    size: { type: String, default: null },
  },

  data: () => ({
    public_path: location.origin,
    form: new Form({
      company_id: null,
      name: '',
      email: '',
      address:''
    }),
  }),
  methods: {
    async update(){
      const {data} = await this.form.patch('/api/company/update/basic/info');
      
      this.$emit('update', data)
      jQuery(this.$refs.modal).modal('hide');
    },
    prepUpdate(data){
      jQuery(this.$refs.modal).modal('show');
      this.form.company_id = data.id;
      this.form.name = data.name;
      this.form.email = data.email;
      this.form.address = data.address;
    }
  },
}
</script>
