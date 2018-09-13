<template>
  <div class="modal fade" ref="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"> <span v-html="icon"></span> {{title}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
              <table v-if="loaded" class="table table-hover">
                <thead>
                  <tr>
                    <th style="border:none" scope="col">Application Count</th>
                    <th style="border:none" scope="col">Company</th>
                  </tr>
                </thead>
                <tbody>
                  <tr @click="redirectToApplications(company.id)" v-for="company in companies" v-bind:key="company.id">
                    <th>{{company.count}}</th>
                    <td>
                      {{company.name}}
                    </td>
                  </tr>
                </tbody>
              </table>
              <center v-else>
                <unick-loader class="loader" />
              </center>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Vue from 'vue'
export default {
  name: 'ApplicationModal',
  data : () =>({
    companies: [],
    status: 0,
    title: '',
    loaded: false,
    icon: ''
  }),
  methods: {
    async fetch(status){
      this.loaded = false
      jQuery(this.$refs.modal).modal('show')
      this.setTitle(status)
      const {data} = await axios({
        url: '/api/reporting/fetch/application/count/per/company',
        method: 'get',
        params: {status:status}
      })

      this.loaded = true
      this.companies = data.companies
    },
    setTitle(status){
      this.status = status
      switch(status){
        case 0:
        this.title = 'Pending Applications'
        this.icon = '<i data-v-bc5483a4="" aria-hidden="true" class="fa fa-envelope-o fa-2x" style="color:#f39c12;"></i>'
        break;
        case 1:
        this.title = 'In-progress Applications'
        this.icon = '<i data-v-bc5483a4="" aria-hidden="true" class="fa fa-exchange fa-2x" style="color:#00c0ef;"></i>'
        break
        case 2:
        this.title = 'Finished Applications'
        this.icon = '<i data-v-bc5483a4="" aria-hidden="true" class="fa fa-check-circle-o fa-2x" style="color:#00a65a;"></i>'
        break
        case 3:
        this.title = 'Inactive Applications'
        this.icon = '<i data-v-bc5483a4="" aria-hidden="true" class="fa fa-times-circle-o fa-2x" style="color:#DD4A39;"></i>'
        break
      }
    },
    redirectToApplications(company_id){
      jQuery(this.$refs.modal).modal('hide')
      switch(this.status){
        case 0:
        this.$router.push({ name: 'company.applicants.submitted', params: { id: company_id} })
        break
        case 1:
        this.$router.push({ name: 'company.applicants.in_progress', params: { id: company_id} })
        break
        case 2:
        this.$router.push({ name: 'company.applicants.finished', params: { id: company_id} })
        break
        case 3:
        this.$router.push({ name: 'company.applicants.inactive', params: { id: company_id} })
        break
      }
    }
  },
}
</script>
<style lang="scss" scoped>
</style>