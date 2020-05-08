<template>
<div>
   <div id="side-bar">
       <div class="d-flex justify-content-center">
           <h1>Vue Screens</h1>
       </div>
       <hr>
       <ul id="side-bar-nav">
           <li>
               <router-link :to="{name: 'home'}">Screen</router-link>
           </li>
           <li>
               <router-link :to="{name: 'messages'}">Messages</router-link>
           </li>
           <li>
               <b-button variant="link" @click="handleLogout">Logout</b-button>
           </li>
       </ul>
   </div>
    <div id="content">
        <router-view></router-view>
    </div>
</div>
</template>
<script>
    import {mapActions} from 'vuex';
    export default {
        methods: {
            handleLogout: function()
            {
                this.$store.dispatch('logout')
                .then(() => {
                    this.$router.push({name: 'login'});
                }).catch((error) => {
                    console.log(error);
                })
            },
            ...mapActions(['logout'])
        }
    }
</script>
<style scoped>
#side-bar{
    height: 100vh;
    width: 14%;
    position: fixed;
    z-index: 1;
    top: 0;
    left: 0;
    background-color: #89f5ff;
    overflow-x: hidden;
    padding-top: 20px;
}
    #side-bar-nav{
        text-align: center;
        list-style: none;
        margin: 0;
        padding:0;
    }
    #side-bar-nav li{
        margin-top: 1em;
    }
    #side-bar-nav li:first-child{
        margin-top: 0;
    }
    #side-bar-nav li a{
        color: #444444;
    }
    #content{
        padding-top: 2em;
        margin-left: 14%;
    }
</style>
