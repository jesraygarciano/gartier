<template>
  <div>
    <div class="sidebar" ref="sidebar" @click="hideUserOption">
      <div class="text-center border-bottom pt-2 pb-2 position-relative">
        <img :src="public_path+'/images/logo_brand.png'">
        <div class="transparent-cover v-small" @click="showSidebar"></div>
      </div>
      <div class="p-2 bg-light"><small>Main Navigation</small></div>
      <div class="list-group list-group-flush">
        <collapsible>
          <template slot="toggle-content">
            Dashboard
          </template>
          <template slot="collapse-content">
            <router-link :to="{ name: 'dashboard.applicant' }" class="sub-link sidebar-nav" active-class="active">
              <i class="fa fa-user-o" aria-hidden="true"></i> Applicant
            </router-link>
            <router-link :to="{ name: 'dashboard.recruiter' }" class="sub-link sidebar-nav" active-class="active">
              <i class="fa fa-building-o" aria-hidden="true"></i> Recruiter
            </router-link>
          </template>
        </collapsible>
        <router-link :to="{ name: 'opening.search' }" class="list-group-item list-group-item-action sidebar-nav" active-class="active">
          Openings
        </router-link>
        <router-link :to="{ name: 'company.search' }" class="list-group-item list-group-item-action sidebar-nav" active-class="active">
          Companies
        </router-link>
      </div>
      <div class="p-2 bg-light"><small>User Business</small></div>
      <div class="list-group list-group-flush">
        <router-link :to="{ name: 'opening.search' }" class="list-group-item list-group-item-action sidebar-nav" active-class="active">
          User Openings
        </router-link>
        <router-link :to="{ name: 'user.companies' }" class="list-group-item list-group-item-action sidebar-nav" active-class="active">
          User Companies
        </router-link>
        <router-link :to="{ name: 'company.search' }" class="list-group-item list-group-item-action sidebar-nav" active-class="active">
          Companies You Followed
        </router-link>
        <router-link :to="{ name: 'company.search' }" class="list-group-item list-group-item-action sidebar-nav" active-class="active">
          Openings You Saved
        </router-link>
      </div>
    </div>
    <div class="sidebar-backdrop" @click="showSidebar"></div>
    <div class="main-layout sidebar-scroll-cancelled" data-addScrollTopBehavior ref="main-layout">
      <navbar @toggle="showSidebar" ref="navigation"/>
      <div class="container mt-4" @click="hideUserOption">
        <child/>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from '~/components/navbar/admin'
import Collapsible from '~/components/Collapsible'
export default {
  name: 'MainLayout',
  data: () => ({
    public_path: location.origin,
  }),
  components: {
    Navbar,
    Collapsible
  },
  methods:{
    showSidebar(){
      if(!jQuery(this.$refs.sidebar).hasClass('visible')){
        jQuery(this.$refs.sidebar).addClass('visible')
        jQuery(this.$refs['main-layout']).addClass('sidebar-visible')
      }
      else{
        jQuery(this.$refs.sidebar).removeClass('visible')
        jQuery(this.$refs['main-layout']).removeClass('sidebar-visible')
      }
      // trigger all events that should be triggered when sidebar is showed or hidden
      // e.g application progress line
      setTimeout(triggerLayoutEvents, 400)
    },
    hideUserOption(){
      this.$refs.navigation.hideUserOption()
    },
  },
  mounted(){
    if(jQuery(document).width() > 768){
      this.showSidebar();
    }
    var $this = this;
    jQuery('.sidebar-nav').click(function(){
      if(jQuery(document).width() <= 768){
        $this.showSidebar();
      }
    })
  }
}

function triggerLayoutEvents(){
  for(var i=0; i < window.layout_events.length; i++){
    try{
      layout_events[i]()
    }catch(e){}
  }
}
</script>
<style lang="scss" scoped>
$sidebar_width:300px;
$transition:300ms ease all;
.sidebar{
  visibility: hidden;
  position: fixed;
  height: 100%;
  background: white;
  z-index: 10;
  width: $sidebar_width;
  left: -$sidebar_width;
  box-shadow:  2px 0px 6px -4px rgba(150,150,150,1);
  transition: $transition;
  overflow: auto;
  &.visible{
    visibility: visible;
    left: 0px;
  }
  .sub-links{
    .sub-link{
      display: block;
      color: #c7ced4;
      padding: 10px 15px;

      &.active{
        color: white;
      }

      &:hover{
        text-decoration: none;
        background: #767f88;
      }
    }
  }
}
.main-layout{
  position: fixed;
  height: 100%;
  overflow-y: scroll;
  right: 0px;
  left: 0px;
  transition: $transition;
  &.sidebar-visible{
    left: $sidebar_width;
  }
}

.transparent-cover{
  position: absolute;
  left: 0px;
  top: 0px;
  height: 100%;
  left: 0px;
  width: 100%;
}
.sidebar-backdrop{
  position: fixed;
  width: 100%;
  height: 100%;
  top: 0px;
  left: 0px;
  background: black;
  z-index: 1;
  opacity: 0;
  visibility: hidden;
  transition: 300ms ease all;
}
.v-small{
  display: none;
}


@media (max-width: 768px) {
  .v-small{
    display: block;
  }
  .sidebar{
    width: 100%;
    right: 100%;
    left: initial!important;
    padding-left: 25px;
  }
  .main-layout{
    width: 100%;
    right: initial!important;
    left: 0px;
  }
  .sidebar.visible{
    right: 25px;
    &+.sidebar-backdrop{
      visibility: visible;
      opacity: 0.75;
    }
  }
  .main-layout.sidebar-visible{
    left: 100%;
    transform: translateX(-25px);
  }
}
</style>

