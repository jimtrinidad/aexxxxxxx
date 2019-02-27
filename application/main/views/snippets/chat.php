<link rel="stylesheet" href="<?php echo public_url(); ?>resources/css/chatbox.css?<?php echo time()?>" type="text/css"> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojione/2.2.7/assets/css/emojione.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.1/emojionearea.css" />

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
					<div class="close-chat"><i class="fa fa-close"></i></div>
				</div>
				<div class="finder">
					<div class="row">
						<div class="col-xs-10 col-xs-offset-1 text-center padding-bottom-10">
							<label class="text-bold">SEND PRIVATE MESSAGE</label>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-10 col-xs-offset-1">
							<div class="form-group findUserGroup">
								<!-- <label class="text-bold">Mabuhay ID or Search by Name</label> -->
								<input class="form-control" type="text" id="findUser" placeholder="Mabuhay ID or Search by Name">
								<span class="help-block findUserHelp small pull-right">Press enter to start conversation.</span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-10 col-xs-offset-1 text-center padding-top-20 padding-bottom-10">
							<label class="text-bold">OR START A GROUP CONVERSATION</label>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-10 col-xs-offset-1">
							<div class="form-group findManyUserGroup offset-bottom-5">
								<span class="help-block findUserHelp small pull-right offset-bottom-5">Press enter to add to group.</span>
								<input class="form-control offset-bottom-5" type="text" id="findManyUser" placeholder="Mabuhay ID or Search by Name">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-10 col-xs-offset-1 text-center">
							<div class="text-danger offset-bottom-5 no-selected-user">No selected recipient.</div>
							<button class="btn btn-sm btn-success" id="startGroupChat">Start group conversion</button>
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
					<div class="textarea-cont">
						<textarea placeholder="Type your message" name="e" id="text_message"  rows="2"></textarea>
					</div>
					<span class="send"><i class="fa fa-send"></i> Send</span>
					<div class="clearfix"></div>
					<span class="help-block textMessageHelp small"></span>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="chat_current_user" value="<?php echo current_user() ?>">
</div>

<div class="modal fade" id="manageGroupModal" tabindex="-1" role="dialog" aria-labelledby="manageGroupModal" style="background: rgba(50,50,50, 0.5);">
	<div class="modal-dialog">
		<div class="modal-content" id="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" style="font-size:18px;"><b class="text-bold">Manage Group</b></h4>
			</div>
			<div class="modal-body" id="modal-body">

				<div class="row gutter-5">
					<div class="col-xs-9">
						<div class="form-group offset-bottom-5">
							<label class="text-bold">Group Alias</label>
							<input class="form-control offset-bottom-5" type="text" id="group_name" name="group_name" placeholder="Group alias" maxlength="100">
							<span class="help-block small pull-right offset-top-5 offset-bottom-5">Press enter to save alias.</span>
						</div>
					</div>
					<div class="col-xs-3 text-center" style="margin-top: -15px;">
						<form id="groupImageForm" name="groupImageForm" action="<?php echo site_url('message/savegrouplogo') ?>" enctype="multipart/form-data">
							<div class="image-upload-container small padding-top-10">
				                <img class="image-preview" src="<?php echo public_url(); ?>assets/profile/avatar_default.jpg" alt="...">
				                <span class="hiddenFileInput hide">
				                  <input type="file" accept="image/*" class="image-upload-input" id="groupAvatar" name="avatarFile"/>
				                </span>
				            </div>
				            <input type="hidden" class="modal_thread_id" name="thread_id" value="">
							<button type="submit" class="btn btn-primary btn-xs text-white small" style="margin-top: 11px;">Save Image</button>
						</form>
					</div>
				</div>

				<hr class="offset-top-10 offset-bottom-10">

				<div class="row">
					<div class="col-xs-12">
						<label class="text-bold offset-top-5 offset-bottom-5">Participants</label>	
					</div>
					<!-- <div class="col-xs-4 text-right">
						<button class="btn btn-xs btn-success"><i class="fa fa-plus"></i>Add</button>
					</div> -->
				</div>
				<div class="row gutter-5">
					<div class="col-xs-10">
						<input class="form-control" type="text" id="findUserForGroup" placeholder="Mabuhay ID or Search by Name">
						<span class="help-block findUserHelp small pull-right">Find user then click the add button.</span>
					</div>
					<div class="col-xs-2">
						<button class="btn btn-sm btn-success" id="addFoundUserForGroup"><i class="fa fa-plus"></i> Add</button>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<ul class="list-group participant-list">
							<li class="ttmulti-selection list-group-item padding-5">
								<img width="30" height="30" style="vertical-align: middle;" src="http://localhost/Projects/Aexponents/mgovphV2/public/assets/profile/avatar_default.jpg">
								<span>Some Name</span>
								<i class="js-remove pull-right" style="cursor:pointer">✖</i>
							</li>
						</ul>
					</div>
				</div>

				<input type="hidden" id="modal_thread_id" class="modal_thread_id" name="modal_thread_id" value="">

				<div class="pull-left">
					<button type="button" class="btn btn-danger btn-xs text-white leave_group">Leave group</button>
				</div>
				<div class="pull-right">
					<button type="button" class="btn bg-cyan btn-xs text-white" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				</div>

				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/typeahead.jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/nicescroll/3.5.4/jquery.nicescroll.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/emojione/2.2.7/lib/js/emojione.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.1/emojionearea.min.js"></script>

<script type="text/javascript" src="<?php echo public_url(); ?>resources/js/typeahead-multiselect.js"></script>

<script type="text/javascript" src="<?php echo public_url(); ?>resources/js/modules/chatbox.js?<?php echo time()?>"></script>