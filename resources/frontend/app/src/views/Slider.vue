<template>
    <div>
    <div class="loading" v-if="isLoading">Loading</div>
    <div v-if="currentScreen && currentSlide">
        <div class="bg"></div>
        <div class="bg bg2"></div>
        <div class="bg bg3"></div>
        <div class="mx-auto my-5" id="content-container">
            <div v-if="currentSlide.type === 'tweet'" class="py-5 mx-3 bg-white shadow">
                <b-media>
                    <template v-slot:aside>
                        <b-img :src="currentSlide.tweetInfo.user.profileImage" width="64" alt="placeholder"></b-img>
                    </template>

                    <h5 class="mt-0">{{ currentSlide.title }}</h5>
                    <p>
                        {{ currentSlide.text }}
                    </p>
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <img :src="image" style="max-height: 32vh;" class="img-fluid col-md-6 p-0" v-for="(image, index) in currentSlide.tweetInfo.images" :key="index">
                            </div>
                        </div>
                    </div>
                </b-media>
            </div>
            <div v-if="currentSlide.type === 'employee'">
                <div class="employee">
                    <div class="d-flex justify-content-center">
                        <img :src='currentSlide.image' class="employee-picture">
                    </div>
                <h1 class="display-1 text-center">{{ currentSlide.employeeName }}</h1>
                <h1 class="display-4 text-center">موظف الاسبوع</h1>
                </div>
            </div>
            <div v-if="currentSlide.type === 'quote'">
                <div class="quote-background shadow"
                     :style="
                             'background-image: url('+currentSlide.image+');'">
                    <img class="nhc-logo" src="/images/nhc-1-svg.png" alt="">
                    <div class="quote-container">
                        <h1 id="quote-text" class="display-1">{{ currentSlide.text }}</h1>
                        <h3 class="my-5 text-center text-white" id="q-author">
                            {{  currentSlide.quoteAuthor }}
                        </h3>
                    </div>
                </div>
            </div>
            <div v-if="currentSlide.type === 'image'">
                <img class="nhc-logo" src="/images/nhc-1-svg.png" alt="">
                <div class="quote-background"
                     :style="
                             'background-image: url('+currentSlide.image+');'">
                </div>
            </div>
            <div v-if="currentSlide.type === 'youtube'">
                <youtube class="youtube-player"
                         @ended="nextSlide"
                         :player-vars="{ autoplay: 1, controls: 0 }"
                         player-width="100%"
                         player-height="100%"
                         :mute="true"
                         :video-id="currentSlide.youtube"></youtube>
            </div>
            <div v-if="currentSlide.type === 'weather'" class="py-5 mx-3">
                <div class="container">
                    <div class="card shadow light-white">
                        <div class="card-body">
                            <h1 class="display-2 text-center my-auto py-5">Weather</h1>
                        </div>
                    </div>
                    <div class="row my-3 justify-content-center text-center">
                        <div class="col-md-4" v-for="city in currentSlide.data" :key="city.id">
                            <div class="card shadow">
                                <div class="card-header"><h3 class="display-3">{{ city.name.substring(0,6) }}</h3></div>
                                <div class="card-body">
                                    <img class="weather-icon" :src="'http://openweathermap.org/img/w/'+city.weather[0].icon+'.png'" alt="">
                                </div>
                                <div class="card-footer"><h3 class="display-3">{{ city.main.temp }}</h3></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<!--
	<div id="clock-container">
            <digital-clock :blink="true" class="font-weight-bold font-italic"></digital-clock>
            <small>{{ new Date() | moment("dddd,  MMMM Do") }}</small>
        </div>
        <div id="messages-container">
            <ul>
                <li v-for="message in currentScreen.messages" :key="message.id">{{ message.text }}</li>
            </ul>
        </div>
    	-->
	</div>
    </div>
</template>
<script>
    import {mapGetters, mapActions} from 'vuex';
    import DigitalClock from "vue-digital-clock";
    import axios from 'axios';
    import Pusher from 'pusher-js';


    export default {
        name: "Slider",
        components: {
            DigitalClock,
        },
        data(){
            return {
                currentSlide: null,
                index: 0,
                isLoading: true
            }
        },
        mounted() {
            this.$store.dispatch('fetchScreen',this.$route.params.id)
            .then(()=> {
                this.isLoading = false
                this.listenToNew();
                this.listenToDelete();
            });
            this.nextSlideRecursive();
        },
        computed: {
            ...mapGetters(['currentScreen'])
        },
        methods: {
            nextSlideRecursive: function()
            {
                if(this.currentScreen)
                {
                  if(this.currentSlide != null && this.currentSlide.type !== 'youtube')
                  {
                      this.nextSlide();
                    //   var weatherTime = Math.random() > 0.9;
                    //   if(weatherTime)
                    //   {
                    //       this.currentSlide = this.generateWeatherSlide();
                    //   }else{
                    //       this.nextSlide()
                    //   }
                  }else{
                      this.currentSlide = this.currentScreen.attachments[this.index];
                  }
                }

                setTimeout(this.nextSlideRecursive, 5000);
            },
            nextSlide: function()
            {
                this.index++;
                if(typeof this.currentScreen.attachments[this.index] === 'undefined'){
                    this.index = 0;
                }
                this.currentSlide = this.currentScreen.attachments[this.index];
            },
            generateWeatherSlide: function()
            {
                let urls = [
                    "https://api.openweathermap.org/data/2.5/weather?q=Riyadh,SA&units=metric&APPID=5a3311ede950f7587784e5f3a3104b99",
                    "https://api.openweathermap.org/data/2.5/weather?q=Mecca,SA&units=metric&APPID=5a3311ede950f7587784e5f3a3104b99",
                    "https://api.openweathermap.org/data/2.5/weather?q=Khobar,SA&units=metric&APPID=5a3311ede950f7587784e5f3a3104b99",
                ];
                let weatherData = [];
                for(var i = 0; i < urls.length; i++)
                {
                    axios
                        .get(urls[i])
                        .then(response => {
                            weatherData.push(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                        })
                }

                return {type: 'weather', data: weatherData}
            },
            listenToNew: function () {
                var pusher = new Pusher('598128a1366d8d8fa2e7', {
                    cluster: 'ap2'
                });

                var newChannel = pusher.subscribe('screen-'+this.currentScreen.id+'-new-attachments');
                newChannel.bind('App\\Events\\NewAttachment', (data) => {
                    this.currentScreen.attachments.push(data.attachment);
                }).bind(this);
            },
            listenToDelete: function () {
                var pusher = new Pusher('598128a1366d8d8fa2e7', {
                    cluster: 'ap2'
                });

                var deletedChannel = pusher.subscribe('screen-'+this.currentScreen.id+'-deleted-attachments');
                deletedChannel.bind('App\\Events\\AttachmentDeleted', (data) => {
                    var i = this.currentScreen.attachments.findIndex(s => s.id === data.attachment.id);
                    this.currentScreen.attachments.splice(i, 1)
                }).bind(this);
            },
            listenToMessage: function () {
                var pusher = new Pusher('598128a1366d8d8fa2e7', {
                    cluster: 'ap2'
                });

                var newMessageChannel = pusher.subscribe('screen-'+this.currentScreen.id+'-message-sent');
                newMessageChannel.bind('App\\Events\\MessageCreated', (data) => {
                    alert(data);
                }).bind(this);
            },
            ...mapActions(['fetchScreen'])
        }
    }
</script>
<style scoped>
    html{
        background: #0b5b8c;
        overflow: hidden !important;
    }
    .bg {
        animation:slide 3s ease-in-out infinite alternate;
        background-image: linear-gradient(-60deg, #106eaf 50%, #1fa496 50%);
        bottom:0;
        left:-50%;
        opacity:.5;
        position:fixed;
        right:-50%;
        top:0;
        z-index:-1;
    }

    .bg2 {
        animation-direction:alternate-reverse;
        animation-duration:4s;
    }

    .bg3 {
        animation-duration:5s;
    }
    .youtube-player{
        height: 80vh!important;
        width: 100% !important;
    }
    .quote-background{
        height: 90vh !important;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }
    .light-dark{
        background: rgba(0,0,0,0.4);
    }
    .weather-icon{
        display: block;
        width: 80%;
        margin-right: auto;
        margin-left: auto;
        height: 200px;
        clear: both;
        padding-top: 10%;
        padding-bottom: 10%;
    }
    .bg-white{
        background: whitesmoke;
    }
    #content-container{
        width: 95%;
        height: 80vh;
    }
    #q-author::before{
       content: '';
        width: 20px;
        position: absolute;
        height: 3px;
        background: white;
        margin-top: 2.5%;
        left: 35%;
    }
    .quote-container{
        margin-top: 5%;
        position: absolute;
        width: 40%;
        /* right: 50%; */
        left: 50%;
        transform: translateX(-50%);
        border: 4px solid #fff;
        padding: 5%;
    }
    #quote-text{
        color: #ffffff;
        text-shadow: 0 0 25px #444444;
        text-align: center;
    }
    #quote-mark{
        font-weight: bold;
        font-size: 150px;
    }
    #messages-container{
        width: 100%;
        height: 7vh;
        position: absolute;
        bottom: 0em !important;
        background: rgba(0,0,0,0.3);
        overflow: hidden;
        padding-top: 0.5em;
    }
    #messages-container ul {
        position: absolute;
        width: 100%;
        height: 100%;
        margin: 0;
        line-height: 50px;
        font-size: 30px;
        list-style: none;
        text-align: center;
        -moz-transform: translateX(100%);
        -webkit-transform: translateX(100%);
        transform: translateX(100%);
        -moz-animation: scroll-left 20s linear infinite;
        -webkit-animation: scroll-left 20s linear infinite;
        animation: scroll-left 20s linear infinite;
    }
    #messages-container ul li{
        color: white;
        display: inline-block;
    }
    #messages-container ul li:before{
        content: '|';
        padding-left: 1em;
        padding-right: 1em;
    }
    #messages-container ul li:first-child:before{
        content: '';
        padding-left: 1em;
        padding-right: 1em;
    }
    #clock-container{
        height: 7vh;
        position: absolute;
        bottom: 0;
        right: 0;
        z-index: 100000000000000;
        padding-left: 1em;
        padding-right: 1em;
        background: rgb(0,0,0);
        color: white;
        font-size: 40px;
    }
    #clock-container small{
        font-size: 12px;
        display: block;
        position: relative;
        bottom: 10px;
    }
    .employee{
        width:60%;
        margin-left: auto;
        margin-right: auto;
        padding-top: 1%;
        color: #fff;
    }
    .nhc-logo{
        width: 8%;
        margin-top: 1%;
        float: right;
        margin-right: 1%;
    }
    @keyframes slide {
        0% {
            transform:translateX(-25%);
        }
        100% {
            transform:translateX(25%);
        }
    }
    @-moz-keyframes scroll-left {
        0% {
            -moz-transform: translateX(100%);
        }
        100% {
            -moz-transform: translateX(-100%);
        }
    }

    @-webkit-keyframes scroll-left {
        0% {
            -webkit-transform: translateX(100%);
        }
        100% {
            -webkit-transform: translateX(-100%);
        }
    }

    @keyframes scroll-left {
        0% {
            -moz-transform: translateX(100%);
            -webkit-transform: translateX(100%);
            transform: translateX(100%);
        }
        100% {
            -moz-transform: translateX(-100%);
            -webkit-transform: translateX(-100%);
            transform: translateX(-100%);
        }
    }
</style>
