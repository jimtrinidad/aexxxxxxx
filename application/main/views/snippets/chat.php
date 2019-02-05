<style type="text/css">
	.noscroll {
		position: fixed;
		overflow-y: scroll;
		width: 100%;
	}
	.chatbubble {
	    position: fixed;
	    bottom: 0;
	    right: 10px;
	    z-index: 1049;
	    transform: translateY(555px);
	    transition: transform .1s ease-in-out;
	    -webkit-transition: transform .1s ease-in-out;
	}
	.chatbubble.opened {
	    transform: translateY(0)
	}
	.chatbubble .unexpanded {
	    display: block;
		background: #089dc2;
	    text-align: center;
	    width: 105px;
	    height: 50px;
	    line-height: 50px;
	    right: 0;
	    bottom: 0;
	    color: #fff;
	    border-radius: 10px 10px 0 0;
	    cursor: pointer;
	}
	.chatbubble .expanded {
		-webkit-transition: width .1s ease-in-out;
	    -moz-transition: width .1s ease-in-out;
	    -o-transition: width .1s ease-in-out;
	    transition: width .1s ease-in-out;
	    height: 555px;
	    width: 105px;
	    padding: 0;
	}

	.chatbubble.opened .unexpanded {
		display: none;
	}
	.chatbubble.opened .expanded {
		-webkit-transition: width .1s ease-in-out;
	    -moz-transition: width .1s ease-in-out;
	    -o-transition: width .1s ease-in-out;
	    transition: width .1s ease-in-out;
		width: 745px;
	}

	.chatbubble .unexpanded .title {
		font-weight: bold;
		font-size: 13px;
	}

	.chatbubble .chat-window {
	  overflow: auto;
	}

	.chatbubble .close-chat {
	    cursor: pointer;
	    font-weight: bold;
	    color: #564e4e;
	    float: right;
	    margin-right: 5px;
	    margin-top: 5px;
	}

	.chatbubble .bubble-unread{
		position: absolute;
	    top: -5px;
	    left: -10px;
	    border-radius: 50%;
	    font-weight: bold;
	    font-size: 14px;
	}

	.ui {
	  margin: 0 auto;
	  width: 745px;
	  height: 555px;
	  background-color: #fff;
	  border-radius: 5px 5px 0 0;
	  box-shadow: 0 0 25px #3a9fc4;
	  flex-direction: row;
	  display: flex;
	  overflow: hidden;
	}

	.ui .search input {
	  outline: none;
	  border: none;
	  background: none;
	}
	.ui .search {
	  position: relative;
	}
	.ui .search input[type=submit] {
	  font-family: 'FontAwesome';
	  position: absolute;
	  right: 25px;
	  top: 27px;
	  color: white;
	}
	.ui .search input[type=search] {
	  background-color: #9598a2;
	  border-radius: 3px;
	  padding: 10px;
	  width: 90%;
	  box-sizing: border-box;
	  margin: 15px 10px;
	  color: #fff;
	}
	.ui .left-menu {
	  width: 30%;
	  box-sizing: content-box;
	  /*padding-right: 1%;*/
	  height: 100%;
	  background: #0f3155;
	}
	.ui .chat {
	  width: 70%;
	  height: 100%;
	  background: #f1f5f8;
	}
	.ui .chat .info {
	  display: inline-flex;
	  flex-direction: column;
	  vertical-align: 40px;
	  width: calc(100% - 65px - 65px);
	}
	.ui .chat .info .name {
	  font-weight: 600;
	  color: #434753;
	  height: 50%;
	}
	.ui .chat .info .count {
	  color: #6d738d;
	}

	.ui .avatar > img,
	.ui .list-friends img {
	  border-radius: 50%;
	  border: 2px solid #72c0d8;
	}
	.ui .list-friends {
	  list-style: none;
	  font-size: 13px;
	  margin-top: 5px;
	  height: calc(100% - 70px - 85px);
	}
	.ui .list-friends img {
	  margin: 5px;
	}
	.ui .list-friends > li {
	  cursor: pointer;
	  display: flex;
	  margin: 0;
	  -webkit-user-select: none;
	     -moz-user-select: none;
	      -ms-user-select: none;
	          user-select: none;
	}
	.ui .list-friends > li.active {
		background: #134684;
	}

	.ui .support-thread {
		background: #1985a0;
	}

	.ui li.support-thread.active {
		background: #089dc2;
	}

	.ui .list-friends .info {
	  flex: 1;
	  padding: 5px 0;
	}
	.ui .list-friends .user {
	  color: #fff;
	  margin-top: 5px;
	}
	.ui .list-friends .status {
	  position: relative;
	  /*margin-left: 14px;*/
	  color: #a8adb3;
	}
	.ui .list-friends.support-list .user {
	  color: #fff;
	  margin-top: 10px;
	}
	/*.ui .list-friends .off:after,
	.ui .list-friends .on:after {
	  content: '';
	  left: -12px;
	  top: 8px;
	  position: absolute;
	  height: 7px;
	  width: 7px;
	  border-radius: 50%;
	}*/
	.ui .list-friends .off:after {
	  background: #fd8064;
	}
	.ui .list-friends .on:after {
	  background: #62bf6e;
	}
	.ui .top {
	  height: 70px;
	}
	.ui .messages {
	  height: calc(100% - 70px - 85px);
	  list-style: none;
	  border: 2px solid #fff;
	  border-left: none;
	  border-right: none;
	}
	.ui .messages li {
	  margin: 10px;
	  transition: all 0.5s;
	}
	.ui .messages li:after {
	  content: '';
	  clear: both;
	  display: block;
	}
	.ui .messages li .head {
	  font-size: 13px;
	}
	.ui .messages li .name {
	  font-weight: 600;
	  position: relative;
	}
	.ui .messages li .name:after {
	  content: '';
	  position: absolute;
	  height: 8px;
	  width: 8px;
	  border-radius: 50%;
	  top: 6px;
	}
	.ui .messages li .time {
	  color: #b7bccf;
	}
	.ui .messages li .message {
	  margin-top: 10px;
	  color: #fff;
	  font-size: 14px;
	  padding: 2px 8px;
	  line-height: 25px;
	  max-width: 500px;
	  word-wrap: break-word;
	  position: relative;
	}
	.ui .messages li .message:before {
	  content: '';
	  position: absolute;
	  width: 0px;
	  height: 0px;
	  top: -8px;
	  border-bottom: 8px solid #62bf6e;
	  border-left: 5px solid transparent;
	  border-right: 5px solid transparent;
	}
	.ui .messages li.friend .name {
	  margin-left: 20px;
	}
	.ui .messages li.friend .name:after {
	  background-color: #f45e3b;
	  left: -20px;
	  top: 6px;
	}
	.ui .messages li.friend .message {
	  background-color: #f45e3b;
	  float: left;
	  border-radius: 0 3px 3px 3px;
	}
	.ui .messages li.friend .message:before {
	  left: 0px;
	  border-bottom-color: #f45e3b;
	}
	.ui .messages li.i .head {
	  text-align: right;
	}
	.ui .messages li.i .name {
	  margin-right: 20px;
	}
	.ui .messages li.i .name:after {
	  background-color: #089dc2;
	  right: -20px;
	  top: 6px;
	}
	.ui .messages li.i .message {
	  background-color: #089dc2;
	  float: right;
	  border-radius: 3px 0 3px 3px;
	}
	.ui .messages li.i .message:before {
	  right: 0;
	  border-bottom-color: #089dc2;
	}
	.ui .write-form {
	  height: 85px;
	}
	.ui .write-form textarea {
	  height: 50px;
	  margin: 17px 1% 0 4%;
	  width: 80%;
	  outline: none;
	  padding: 10px;
	  border: none;
	  border-radius: 3px;
	  resize: none;
	}
	.ui .write-form textarea:before {
	  content: '';
	  clear: both;
	}
	.ui .avatar > img {
	  border-color: #f45e3b;
	  margin: 10px;
	  margin-right: 5px;
	}
	.ui .avatar {
	  display: inline-block;
	}
	.ui .send {
	  color: #089dc2;
	  text-transform: uppercase;
	  font-weight: 700;
	  float: right;
	  margin-right: 3%;
	  margin-top: 35px;
	  cursor: pointer;
	  -webkit-user-select: none;
	     -moz-user-select: none;
	      -ms-user-select: none;
	          user-select: none;
	}
	.ui i.fa-file-o {
	  margin-left: 15px;
	}
	.ui i.fa-picture-o {
	  margin-left: 5%;
	}

	.ui .xs-only {
		display: none;
		position: relative;
	}

	.ui .notif_badge {
		position: absolute;
    	left: -30px;
    	top: 2px;
	}

	.ui .nav-tabs>li>a {
		border-radius: 0;
		text-align: center;
	}

	.ui .new-message {
		margin-top: 5px;
		line-height: 2.5;
		font-weight: bold;
		color: #ffffff;
		background: #f45e3b;
		cursor: pointer;
	}

	.ui .textMessageHelp {
		margin-left: 4%;
    	margin-top: 1px;
    	color: #a94442;
	}

	.ui .nav>li>a {
		padding: 9px 10px;
		border-right: 0;
		margin-right: 0;
	}

	.ui .not-xs {
		display: inline;
	}

	.chatbubble .tt-menu {
	  min-width: 300px !important;
	}

	.chatbubble .small, .chatbubble small {
	    font-size: 85%;
	}

	@media (max-width: 768px) {
		.chatbubble.opened .expanded {
			width: 400px;
		}
		.ui {
			width: 400px;
		}

		.ui .left-menu {
		  	width: 18.5%;
		}
		.ui .chat {
			width: 81.5%;
		}

		.ui .search {
			display: none;
		}

		.ui .list-friends {
			height: 95%;
		}

		.ui .list-friends .info {
			display: none;
		}

		.ui .write-form textarea {
			width: 73%;
		}

		.ui .nav-tabs>li>a {
			width: 74px;
		}

		.ui .nav>li>a {
			padding: 9px 7px;
		}

		.ui .xs-only {
			display: inline;
		}

		.ui .not-xs {
			display: none;
		}

		.ui .list-friends img {
			margin: 5px auto;
		}
	}

	@media screen and ( max-height: 500px ){
		.chatbubble {
			transform: translateY(320px);
		}
		.chatbubble .expanded,
		.chatbubble .ui {
			height: 320px;
		}
	}

</style>
<div class="chatbubble">
    <div class="unexpanded">
        <div class="title"><span class="bubble-unread hide label label-danger"></span> <i class="fa fa-comments-o" aria-hidden="true"></i> Live Support</div>
    </div>
    <div class="expanded chat-window">
		<div class="ui">
			<div class="left-menu">

				<!-- Nav tabs -->
				<ul class="nav nav-tabs small" role="tablist">
				    <li role="presentation"><a href="#friends" aria-controls="friends" role="tab" data-toggle="tab"><span class="not-xs">&nbsp;</span>Community</a></li>
				    <li role="presentation"><a href="#support" aria-controls="support" role="tab" data-toggle="tab">Support</a></li>
				    <li role="presentation"><a href="#recent" aria-controls="recent" role="tab" data-toggle="tab">Messages</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
				    <div role="tabpanel" class="tab-pane" id="friends"></div>
				    <div role="tabpanel" class="tab-pane" id="support">
				    	<menu class="list-friends support-list">
						</menu>
				    </div>
				    <div role="tabpanel" class="tab-pane" id="recent">
						<div class="text-center new-message">
							<span>Start<span class="hidden-xs"> Conversation</span></span>
						</div>
						<menu class="list-friends recent-threads">
						</menu>
				    </div>
				</div>

			</div>
			<div class="chat">
				<div class="top">
					<content class="hide">
						<div class="avatar">
							<img width="50" height="50" src="">
						</div>
						<div class="info">
							<div class="name">Juan Dela Cruz</div>
							<div class="count">already 1 902 messages</div>
						</div>
					</content>
					<div class="close-chat"><i class="fa fa-close"></i> Close</div>
				</div>
				<div class="finder">
					<div class="row">
						<div class="col-xs-10 col-xs-offset-1">
							<div class="form-group findUserGroup">
								<label class="text-bold">Mabuhay ID or Search by Name</label>
								<input class="form-control" type="text" id="findUser" placeholder="Mabuhay ID">
								<span class="help-block findUserHelp small pull-right">Press enter to start conversation.</span>
							</div>
						</div>
					</div>
				</div>
				<ul class="messages hide">
					<!-- <li class="i">
						<div class="head">
							<span class="time">10:13 AM, Today</span>
							<span class="name">Jim</span>
						</div>
						<div class="message">Hi</div>
					</li>
					<li class="friend">
						<div class="head">
							<span class="name">Juan</span>
							<span class="time">10:15 AM, Today</span>
						</div>
						<div class="message">Yes?</div>
					</li> -->
				</ul>
				<div class="write-form hide">
					<textarea placeholder="Type your message" name="e" id="text_message"  rows="2"></textarea>
					<span class="send"><i class="fa fa-send"></i> Send</span>
					<span class="help-block textMessageHelp small"></span>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="chat_current_user" value="<?php echo current_user() ?>">
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/typeahead.jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/nicescroll/3.5.4/jquery.nicescroll.js"></script>
<script type="text/javascript" src="<?php echo public_url(); ?>resources/js/modules/chatbox.js?<?php echo time()?>"></script>