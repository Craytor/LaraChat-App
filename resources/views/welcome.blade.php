<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.6/material.indigo-pink.min.css">
        <link href="http://c3js.org/css/c3-b03125fa.css" media="screen" rel="stylesheet" type="text/css" />

            <!-- Javscript -->
        <script src="https://storage.googleapis.com/code.getmdl.io/1.0.6/material.min.js"></script>
        
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <style>
            html {
                background-color: rgb(229, 227, 223);
            }

            main {
                height: 100%;
            }

            .inner {
                /*overflow: scroll;
                height: calc(50% - 160px);
                -ms-overflow-style: none;
                overflow: -moz-scrollbars-none;*/
                max-height: calc(100% - 64px);
                overflow-y: scroll;
            }
    /*
            .inner::-webkit-scrollbar {
                width: 2 !important;
            
            }*/

            .bottom {
                position: fixed;
                bottom: 0;
                width: 100%;
                height: 64px;
                background-color: #ffffff;
                /* box-shadow: 0px -3px 3px 0px rgba(50, 50, 50, 0.1); */
            }

            .bottom .input {
                height: 64px;
                background: #ffffff;
                border: none;
                width: calc(100% - 64px);
                position: absolute;
                left: 0;
                top: 0;
                padding: 0 5%;
                resize: none;
                overflow: scroll;
                padding-top: 24px;
                font-weight: 300;
                -ms-overflow-style: none;
                overflow: -moz-scrollbars-none;
            }

            .bottom .input:focus {
                outline: none;
            }

            .bottom .input::-webkit-scrollbar {
                width: 0 !important;
            }

            .bottom .send {
                position: fixed;
                height: 42.66667px;
                width: 42.66667px;
                border-radius: 50%;
                border: 0;
                background: #F44336;
                color: #ffffff;
                bottom: 10.66667px;
                right: 10.66667px;
            }

            .bottom .send:before {
                content: '';
                background: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/104946/ic_send_white_48dp.png) no-repeat center center;
                background-size: 25.6px;
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
            }

            .content {
                padding-left: 40px;
                padding-right: 40px;
            }

            .bottom .send:focus {
                outline: none;
            }

            .bottom .send:hover {
                cursor: pointer;
            }

            .message-wrapper {
                position: relative;
                overflow: hidden;
                width: 100%;
                margin: 10.66667px 0;
                padding: 10.66667px 0;
            }

            .message-wrapper .circle-wrapper {
                height: 42.66667px;
                width: 42.66667px;
                border-radius: 50%;
            }

            .message-wrapper .text-wrapper {
                padding: 10.66667px;
                min-height: 14px;
                width: auto;
                margin: 0 10.66667px;
                box-shadow: 0px 1px 0px 0px rgba(50, 50, 50, 0.3);
                border-radius: 2px;
                font-weight: 300;
                position: relative;
                /* word-break: break-all; */
                opacity: 1;
            }
            .message-wrapper .text-wrapper:before {
                content: '';
                width: 0;
                height: 0;
                border-style: solid;
            }

            .message-wrapper.them .circle-wrapper, .message-wrapper.them .text-wrapper {
                background-color: #F44336;
                float: left;
                color: #fff;
            }
            .message-wrapper.them .text-wrapper:before {
                border-width: 0 10px 10px 0;
                border-color: transparent #F44336 transparent transparent;
                position: absolute;
                top: 0;
                left: -9px;
            }
            .message-wrapper.me .circle-wrapper, .message-wrapper.me .text-wrapper {
                background: #FF5722;
                float: right;
                color: #333333;
            }
            .message-wrapper.me .text-wrapper {
                background: #ffffff;
            }
            .message-wrapper.me .text-wrapper:before {
                border-width: 10px 10px 0 0;
                border-color: #ffffff transparent transparent transparent;
                position: absolute;
                top: 0;
                right: -9px;
            }

            .chat_message {
                margin-left: 20px;
                width: calc(100% - 100px);
            }

            .bottom textarea {
                resize: none;
                border-bottom: 0px;
            }
        </style>
    </head>
    <body id="chat">    
        <div id="live_chat">
            <header class="mdl-layout__header mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
                <div class="mdl-layout__header-row" style="padding-left: 40px; padding-right: 40px;">
                    <span class="mdl-layout-title">Live Chat</span>

                    <div class="mdl-layout-spacer"></div>

                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
                        <i class="material-icons">more_vert</i>
                    </button>
                    <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
                        <li class="mdl-menu__item">Pop Out</li>
                    </ul>
                </div>
            </header>
            <main>
                <div id="inner" class="inner">
                    <div id="content" class="content">
                        <div class="message-wrapper them" v-repeat="message: messages">
                            <div class="circle-wrapper"></div>
                            <div class="text-wrapper">@{{ message }}</div>
                        </div>
                    </div>
                </div>
                <form id="bottom" class="bottom" v-on="submit: send">
                    <div class="chat_message mdl-textfield mdl-js-textfield">
                        <input type="text" class="mdl-textfield__input" id="message" placeholder="message..." rows="1" v-model="message">
                    </div>
                    <button id="send" class="send"></button>
                </form>
            </main>
        </div>



        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.3.7/socket.io.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/0.12.15/vue.min.js"></script>

        <script>
            var socket = io('http://larachat.dev:3000');

            new Vue({
                el: '#chat',

                data: {
                    messages: [],
                    message: ''
                },

                ready: function() {

                    // recieves payload from socket.io
                    socket.on('some_channel', function(payload) {

                        // push the current message onto the chat
                        this.messages.push(payload[2]);

                    }.bind(this))
                },

                methods: {

                    // sends message to socket.io
                    send: function(e) {

                        // prepare a payload
                        var payload = ['some_channel', 'Craytor', this.message];

                        // send the payload to socket.io
                        socket.emit('chat', payload);

                        e.preventDefault();
                    }
                }

            });
        </script>
    </body>
</html>
