require('./bootstrap');

window.Vue = require('vue');

Vue.component('example-component', require('./components/ExampleComponent.vue'));


const app = new Vue({
    el: '#app'
});

// var kenuo = require('./common');

require('./main');

// title, text, type, callback
// kenuo.default.message.alert(`标题信息`, `文字信息`, `success`,'退出','取消', function () {
//     kenuo.default.message.alert(`你点击了退出`, `文字信息`, `success`,'退出','取消',function () {
//         alert('阿星是傻逼');
//     })
// })
