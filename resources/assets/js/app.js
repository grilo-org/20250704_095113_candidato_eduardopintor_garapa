
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 require('./bootstrap');

 window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const app = new Vue({
//     el: '#app'
// });
//

//Initialize Firebase

var firebase = require('firebase/app');
require('firebase/auth');
require('firebase/database');
require('firebase/storage');

var p = 1;

firebase.initializeApp(firebase_config);

//function to save file
if(document.getElementById('files')) {
    document.getElementById('files').addEventListener('change', function(e){
        var storage = firebase.storage();
        var storageRef = storage.ref();
        var totalImages = 1;
        var imagePath = 'images';
        var imageUrlRelative = (this.getAttribute('data-url-relative') == 'true') ? true : false;

        if(this.getAttribute('data-num-files')) {
            var totalImages = this.getAttribute('data-num-files');
        }

        if(this.getAttribute('data-image-path')) {
            var imagePath = this.getAttribute('data-image-path');
        }

        var file = document.getElementById('files').files[0];

        //dynamically set reference to the file name
        var thisRef = storageRef.child(imagePath +'/' + file.name);

        var progressBar = document.getElementsByClassName('progress-bar progress-bar-striped')[0];
        var uploadTask = thisRef.put(file);
        var imageField = document.getElementById('image_preview');
        var imagePreview = document.getElementById('image_preview');
        progressBar.parentElement.style.display = 'flex';

        uploadTask.on('state_changed', function(snapshot){
            var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
            progressBar.style.width = Math.round(progress) +'%';
            progressBar.innerHTML = Math.round(progress) +'%';

            console.log('Upload is ' + Math.round(progress) + '% done');
            switch (snapshot.state) {
                case firebase.storage.TaskState.PAUSED:
                console.log('Upload is paused');
                break;
                case firebase.storage.TaskState.RUNNING:
                console.log('Upload is running');
                break;
            }
        }, function(error) {

        }, function() {

          uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {

            var filePath = (imageUrlRelative) ? file.name : downloadURL;

            if(totalImages == 1) {
                document.getElementsByClassName('image_data')[0].innerHTML = '<input type="hidden" name="image" id="image" value="'+ filePath +'">';
                imagePreview.innerHTML = '<img src="'+ downloadURL +'" class="img-thumbnail">';
            }else{
                if(p ==1) {
                    document.getElementsByClassName('image_data')[0].innerHTML = '';
                    imagePreview.innerHTML = '';
                }
                document.getElementsByClassName('image_data')[0].innerHTML += '<input type="hidden" name="image[]" id="image_'+ p +'" value="'+ filePath +'">';
                imagePreview.innerHTML += '<img src="'+ downloadURL +'" class="img-thumbnail">';
            }

            setTimeout(function() {
                progressBar.style.width = 0;
                progressBar.parentElement.style.display  = 'none';
            }, 1000);

            if(p == totalImages && totalImages != 1) {
                document.getElementsByClassName('btn')[0].setAttribute('disabled', 'disabled');
                document.getElementById('files').setAttribute('disabled', 'disabled');
            }
            p++;

        });
      });
    });
}

