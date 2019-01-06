function Chatbox() {

    // because this is overwritten on jquery events
    var self = this;

    this.isTabActive = true;
    this.currentUser;
    this.totalUnread = 0;
    this.totalMsg = 0;
    this.threads = {};
    this.activeThread;
    this.lastTime = 0;
    this.activeRequest = [];
    this.readRequest;
    this.findRequest;
    this.threadRequest;
    this.receiverID;

    /**
     * Initialize events
     */
    this._init = function() 
    {

        self.set_events();
        self.set_configs();
        self.getThreads();
        self.userFinder("#findUser");

        self.currentUser = $('#chat_current_user').val();
    }

    /**
    * events delaration
    */
    this.set_events = function()
    {

        window.onfocus = function () { 
          self.isTabActive = true; 
        }; 

        window.onblur = function () { 
          self.isTabActive = false; 
        }; 

        $(window).focus(function() {
            self.isTabActive = true; 
        });

        $(window).blur(function() {
            self.isTabActive = false; 
        });


        $('.chatbubble .unexpanded').click(function(){
            self.openChatWindow();
        });

        $('.chatbubble .close-chat').click(function(){
            $('.chatbubble').removeClass('opened');
            $(".chatbubble .list-friends").hide();
            $(".chatbubble .messages").hide();

            self.abortRequest();
            if (self.readRequest) {
                self.readRequest.abort();
            }
            if (self.findRequest) {
                self.findRequest.abort();
            }
        });

        // select thread
        $('.chatbubble .recent-threads').on('click', 'li', function(){
            self.selectThread(this);
        });

        // new or find thread
        $('.chatbubble .new-message').click(function(){
            self.clearThread();
            self.userFinder("#findUser");
        });

        // send message via enter
        $("#findUser").keypress(function(e) {
          if (e.keyCode === 13) {
            self.findThreadByID($("#findUser").val());
            return false;
          }
        });

        // send message via enter
        $("#text_message").keypress(function(e) {
            if ($(".chatbubble .write-form").hasClass('sending')) {
                // disable write when sending
                return false;
            }
            if (e.keyCode === 13) {
                self.sendMessage();
                return false;
            }
        });

        // send message via click
        $(".chatbubble .send").click(function() {
            self.sendMessage();
        });

    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {

        $(".chatbubble .list-friends").niceScroll({
            cursorcolor: "#696c75",
            cursorwidth: "4px",
            cursorborder: "none"
        });
        $(".chatbubble .messages").niceScroll({
            cursorcolor: "#cdd2d6",
            cursorwidth: "4px",
            cursorborder: "none"
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            setTimeout(function() {
                if ($(".chatbubble").height() > 500) {
                    $(".chatbubble .list-friends").height($(".chatbubble").height() - 200);
                } else {
                    $(".chatbubble .list-friends").height($(".chatbubble").height() - 150);
                }
            }, 10);
        });
        $('a[data-toggle="tab"]:last').tab('show');

        $(window).resize(function(){
            if ($(".chatbubble").height() > 500) {
                $(".chatbubble .list-friends").height($(".chatbubble").height() - 200);
            } else {
                $(".chatbubble .list-friends").height($(".chatbubble").height() - 150);
            }
        });

        setInterval(function(){
            if ($('.chatbubble').hasClass('opened') && self.activeThread && self.isTabActive) {
                var thread_data = self.get_thread_data(self.activeThread);
                if (thread_data.unread > 0) {
                    self.setRead(self.activeThread);
                }
            }
        }, 5000);

    }

    this.openChatWindow = function(tab = false)
    {
        $('.chatbubble').addClass('opened');
        $(".chatbubble .list-friends").show();
        $(".chatbubble .messages").show();

        if (tab != false) {
            $('.nav-tabs a[href="#' + tab + '"]').tab('show');
        }
    }

    this.claerResizeScroll = function() {
        $(".chatbubble .messages").getNiceScroll(0).resize();
        $(".chatbubble .messages").getNiceScroll(0).doScrollTop(999999, 999);
    };

    /**
    * get thread from fetched data
    */
    this.get_thread_data = function(id)
    {
        var match = false;
        $.each(self.threads, function(i,e){
            if (e.id == id) {
                match = e;
                return false;
            }
        });
        return match;
    }

    /**
    * poll threads
    */
    this.getThreads = function()
    {
        if (self.threadRequest) {
            self.threadRequest.abort();
        }
        self.threadRequest = $.ajax({
            url  : window.base_url('message/threads'),
            type : 'post',
            data : {'unread': self.totalUnread, 'count': self.totalMsg},
            success : function(response) {
                if (response.status) {
                    self.totalUnread = response.unread;
                    self.totalMsg = response.count;
                    self.threads = response.data;
                    self.showThreads(response.data);

                    if (self.totalUnread > 0) {
                        $('span.bubble-unread').text(self.totalUnread).removeClass('hide');
                    } else {
                        $('span.bubble-unread').addClass('hide');
                    }
                }
            },
            complete: function(a, b) {
                if (b != 'abort') {
                    setTimeout(function(){
                        self.getThreads();
                    }, 1000);
                }
            }
        });
    }

    /**
    * poll messages
    */
    this.getMessages = function(read = 0, callback = false)
    {   

        // cancel previous request first
        self.abortRequest();

        $request = $.ajax({
            url  : window.base_url('message/messages'),
            type : 'post',
            data : {
                'thread_id': self.activeThread,
                'timestamp': self.lastTime,
                'read': read
            },
            success : function(response) {
                if (response.status) {
                    self.lastTime = response.timestamp;
                    self.showMessages(response.data);
                }
            },
            complete: function(a, b) {
                if (b != 'abort') {
                    setTimeout(function(){
                        self.getMessages();
                    }, 1000);
                }

                if (callback) {
                    callback();
                }
                $(".chatbubble .messages").LoadingOverlay("hide");
            }
        });

        self.activeRequest.push($request);
    }

    /**
    * send message
    */
    this.sendMessage = function()
    {
        var message = $("#text_message").val();
        if (message) {

            $(".chatbubble .write-form").LoadingOverlay("show", {zIndex: 9999999});
            if (!$(".chatbubble .write-form").hasClass('sending')) {
                $(".chatbubble .write-form").addClass('sending');
                $("#text_message").blur();

                var vData = {
                        'message': message
                    };
                if (self.activeThread) {
                    vData.thread_id = self.activeThread;
                } else if (self.receiverID) {
                    vData.receiver = self.receiverID;
                }

                $.ajax({
                    url  : window.base_url('message/send'),
                    type : 'post',
                    data : vData,
                    success : function(response) {
                        if (response.status) {
                            if (response.type == 2) {

                                // set new message as active thread
                                self.activeThread = response.data;
                                self.lastTime = 0

                                // refresh thread
                                self.getThreads();
                                
                            } else {
                                self.lastTime = response.timestamp;

                                messageData = {
                                    cdate: response.datetime,
                                    sender_id: self.currentUser,
                                    user_name: 'Me',
                                    body: message
                                }

                                self.showMessages([messageData]);
                            }

                            // abort previous message request, request new with latest timestamp
                            self.getMessages();

                            // self.getMessages(1, function() {
                            //     $(".chatbubble .write-form").removeClass('sending');
                            //     $(".chatbubble .write-form").LoadingOverlay("hide");
                            //     $("#text_message").focus();
                            // });

                            $(".chatbubble .write-form .textMessageHelp").text('');

                        } else {
                            $(".chatbubble .write-form .textMessageHelp").text(response.message);
                        }
                    }, complete: function() {
                        $(".chatbubble .write-form").removeClass('sending');
                        $(".chatbubble .write-form").LoadingOverlay("hide");
                        $("#text_message").focus();
                    }
                });
            }
        }

        $("#text_message").val('');
    }

    /**
    * mark as read
    */
    this.setRead = function(thread_id) {

        if (!self.readRequest) {
            self.readRequest = $.ajax({
                url  : window.base_url('message/read'),
                type : 'post',
                data : {'thread_id' : thread_id}
            });
            self.readRequest = false;
        }
    }

    /**
    * abort active request
    */
    this.abortRequest = function()
    {
        $.each(self.activeRequest, function(i,r){
            r.abort();
            self.activeRequest.splice(i, 1);
        });
    }

    /**
    * clear messages, show user finder
    */
    this.clearThread = function()
    {
        self.abortRequest();
        $('.chatbubble .top .info .name').text('');
        $('.chatbubble .top .info .count').text('');
        $('.chatbubble .top content').addClass('hide');
        $('.chatbubble .write-form').addClass('hide');
        $('.chatbubble .messages').html('').addClass('hide');
        $('.chatbubble .recent-threads li').removeClass('active');
        $('#findUser').val('');
        $('.findUserGroup').removeClass('has-error');
        $('.chatbubble .findUserHelp').text('Press enter to start conversation.');
        $('.chatbubble .finder').removeClass('hide');
        self.activeThread = false;
        self.receiverID = false;
    }

    /**
    * send message request from outside
    * called from other pages, not within chatbox
    */
    this.openChatbox = function(mabuhayID)
    {
        self.abortRequest();
        self.activeThread = false;
        self.receiverID = false;
        $('.chatbubble').addClass('opened');
        $(".chatbubble .list-friends").show();
        $(".chatbubble .messages").show();
        $('.chatbubble .recent-threads li').removeClass('active');
        self.findThreadByID(mabuhayID);
    }


    /**
    * find conversation by mabuhay id
    */
    this.findThreadByID = function(mabuhayID)
    {
        if (self.findRequest) {
            self.findRequest.abort();
            $('.chatbubble .finder').LoadingOverlay('hide');
        }

        $('.chatbubble .finder').LoadingOverlay('show', {zIndex: 999999});
        self.findRequest = $.ajax({
                url  : window.base_url('message/find'),
                type : 'post',
                data : {'mabuhay_id' : mabuhayID},
                success: function(response) {
                    if (response.status) {
                        if (response.code == 2 && $('#thread_' + response.thread_id).length) {
                            $('#thread_' + response.thread_id).click();
                        } else {
                            // set info
                            $('.chatbubble .top .avatar img').prop('src', window.public_url('assets/profile/' + response.receiver.photo));
                            $('.chatbubble .top .info .name').text(response.receiver.name);
                            $('.chatbubble .top .info .count').text('new conversation');
                            $('.chatbubble .top content').removeClass('hide');
                            $('.chatbubble .write-form').removeClass('hide');
                            $('.chatbubble .messages').html('').removeClass('hide');

                            self.receiverID = response.receiver.id;
                        }

                        $('.chatbubble .finder').addClass('hide');
                        $('.findUserGroup').removeClass('has-error');
                    } else {
                        $('.chatbubble .findUserHelp').text(response.message);
                        $('.findUserGroup').addClass('has-error');
                    }
                },
                complete: function() {
                    self.findRequest = false;
                    $('.chatbubble .finder').LoadingOverlay('hide');
                }
            });
    }

    /**
    * generate recent list items
    */
    this.showThreads = function(data)
    {
        var recent_holder = $('menu.recent-threads');
        recent_holder.html('');
        $.each(data, function (i,e){
            var recentImg = window.public_url('assets/profile/' + e.participants[0].photo);
            var recentName = e.participants[0].user_name;
            if (e.participants.length > 2) {
                recentName += ', +' + (e.participants.length - 1) + ' Others';
            } else if (e.participants.length == 2) {
                // recentName += ', ' + e.participants[1].user_name;
                recentName += ', +' + (e.participants.length - 1) + ' Other';
            }
            var recentNote = e.msg_count + ' messages';
            var badge = '';
            if (e.unread > 0) {
                recentNote = '<span class="label label-danger">'+e.unread + '</span> new messages';
                badge = '<span class="notif_badge label label-danger">'+e.unread + '</span>';
            }
            var isactive = '';
            if (e.id == self.activeThread) {
                isactive = 'active';
                $('.chatbubble .top .info .count').text(e.msg_count + ' messages found');
            }
            recent_holder.append(
                '<li id="thread_'+e.id+'" class="'+isactive+'" data-thread_id="'+e.id+'"> \
                    <img width="40" height="40" src="'+recentImg+'" title="'+recentName+'"> \
                    <span class="xs-only">'+badge+'</span> \
                    <div class="info"> \
                        <div class="user">'+recentName+'</div> \
                        <div class="status">'+recentNote+'</div> \
                    </div> \
                </li>'
            );
        });
    }

    /**
    * select thread
    */
    this.selectThread = function(elem)
    {
        var $this = $(elem);
        if (!$this.hasClass('active')) {
            var data = self.get_thread_data($this.data('thread_id'));
            $('.chatbubble .recent-threads li').removeClass('active');
            $this.addClass('active');

            // set info
            $('.chatbubble .top .avatar img').prop('src', window.public_url('assets/profile/' + data.participants[0].photo));
            var participants = [];
            $.each(data.participants, function(i, e) {
                participants.push(e.user_name);
            });
            $('.chatbubble .top .info .name').text(participants.join(', '));
            // $('.chatbubble .top .info .name').text($this.find('.user').text());
            $('.chatbubble .top .info .count').text(data.msg_count + ' messages found');
            $('.chatbubble .top content').removeClass('hide');
            $('.chatbubble .write-form').removeClass('hide');
            
            $(".chatbubble .write-form .textMessageHelp").text('');
            $(".chatbubble .finder").addClass('hide');
            $(".chatbubble .messages").html('').removeClass('hide').LoadingOverlay("show", {zIndex: 9999999});

            self.receiverID = false;
            self.activeThread  = data.id;
            self.lastTime = 0;
            self.getMessages(1);
        }
    }


    /**
    * show messages
    */
    this.showMessages = function(data)
    {
        $.each(data, function(i, e){
            
            var ctime = moment(e.cdate).fromNow();
            if (e.sender_id == self.currentUser) {
                $(".chatbubble .messages").append(
                    `<li class="i">
                        <div class="head">
                            <span class="time">${ctime}</span>
                            <span class="name">Me</span>
                        </div>
                        <div class="message">${e.body}</div>
                    </li>`
                );
            } else {
                $(".chatbubble .messages").append(
                    `<li class="friend">
                        <div class="head">
                            <span class="name">${e.user_name}</span>
                            <span class="time">${ctime}</span>
                        </div>
                        <div class="message">${e.body}</div>
                    </li>`
                );
            }

            self.claerResizeScroll();
        })
    }

    this.userFinder = function(elem)
    {   
        try { $(elem).typeahead('destroy'); } catch(e) {}
        $(elem).typeahead({
            hint: false,
            minLength: 5,
        },
        {
            templates: {
                empty: [
                    '<div class="padding-left-10 empty-message">',
                      'No match found.',
                    '</div>'
                ].join('\n'),
                suggestion: function (item) {
                    item.address.pop();
                    return '<div class="row gutter-0">' +
                                '<div class="col-xs-2">' +
                                    '<span>' +
                                    '<img style="width:45px;height:45px;margin: 0 auto;" src="' + window.public_url() + "assets/profile/"+item.photo+'">' +
                                    '</span></div>' +
                                '<div class="col-xs-10 small" style="padding-left: 10px;">' +
                                    '<div>'+ item.mabuhayID +'<small> - '+ item.fullname + '</small></div>' +
                                    '<div><small>'+ item.aclevel +'</small></div>' +
                                    '<div><small>'+ item.address.join(', ') +'</small></div>' +
                                '</div>' +
                            '</div>';
                }
            },
            name: 'user',
            display: 'mabuhayID',
            source: function(query, syncResults, asyncResults) {
                try {clearTimeout(typeaheadTimeout);} catch (e) {}
                typeaheadTimeout = setTimeout(function (){
                    $.get(window.base_url('message/find_user') + "?q=" + query, function(responseData) {
                        asyncResults(responseData);
                    });
                }, 500);
            }
        }).bind('typeahead:select', function(ev, item) {
            var id = $(ev.target).prop('id');
        });
    }

}


var Chatbox = new Chatbox();
$(document).ready(function(){
    Chatbox._init();
});