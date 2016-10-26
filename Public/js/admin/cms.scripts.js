    $(document).ready(function(){

        $('#top > ul > li').hover(
            function() {$('ul', this).css('display', 'block');},
            function() {$('ul', this).css('display', 'none');
        });
        
        
        $('.form .element .field').hover(function() { 
                if(!$('.form .element .field input').is(':focus'))
                {
                    $(this).parent('.element').children('.description').fadeIn();
                }
            },function() { 
                if(!$('.form .element .field input').is(':focus'))
                {
                    $(this).parent('.element').children('.description').fadeOut();
                }
        });
            
        $('.form .element .field input').focus(function() { 
            
            $(this).parent('.element').children('.description').fadeIn();
            
        });
        $('.form .element .field input').blur(function() { 
            
            $(this).parent('.element').children('.description').fadeOut();
            
        });
        
        
        $('#notification').ajaxStart(function () {
            var throbber = $('#throbber');
            throbber.html('<img src="/images/admin/throbber.gif" alt="Proszę czekać..." /> Proszę czekać...');
            throbber.show();
        }).ajaxStop(function () {
            var throbber = $('#throbber');
            throbber.promise().done(function () {
               throbber.fadeOut(200); 
            });
            
            $('#listView li.item').each(function(index, value) {
                $(this).removeClass('odd');
                if(index % 2)
                {
                    $(this).addClass('odd'); 
                }
            });
        });

});

function setNotification (message, type)
{    
    var notification = $('#notification');
    
    notification.removeClass().addClass(type).html(message).css('margin-left', (-notification.width() / 2));
    notification.fadeIn(500);
    
    notification.promise().done(function () {
       notification.delay(4000).fadeOut(500); 
    });
}

var cms = {
    'toggleSelect': function (items)
    {
        $(items).each( function() {
            if($(this).is(':checked'))
            {
                $(this).removeAttr("checked");
            }
            else
            {
                $(this).attr("checked", "checked");
            }
        });
    },
    'delete': function (actionUrl, params, list, callback) 
    {
        var results = false;

        $.ajax({
            url: actionUrl,
            type: "POST",
            data: params,
            dataType: "json",
            success: function (data) {
                if(data != null)
                {     
                    setNotification(data.message, data.type);

                    if(data.id)
                    {
                        $.each(data.id, function (key, val) {
                            $(list.item + val).remove();
                        });

                        if($(list.items + ' li.item').size() == 0)
                        {
                            $(list.items).append('<li class="empty"><div>Brak rekordów</div></li>');
                        }
                    }
                }

                $(list.items).children('li').each(function(index, value) {
                    $(this).removeClass('odd');

                    if(index % 2)
                    {
                        $(this).addClass('odd'); 
                    }
                }); 
                
                if(callback)
                {
                    callback();
                }
            }
        });
    },
    'sortable': function (actionUrl, list, callback) 
    {
        $(list.items).sortable({ 
            axis: 'y', 
            cursor: 'move',
            cancel: '.empty',
            stop: function(event, ui) { 

                $(list.items).children('li').each(function(index, value) {
                    $(this).removeClass('odd');

                    if(index % 2)
                    {
                        $(this).addClass('odd'); 
                    }
                    else
                    {
                        $(this).removeClass('odd');
                    }
                });

                $.ajax({
                    url: actionUrl,
                    data: { id: $(list.sortable).serializeArray() },
                    type: "POST",
                    dataType: "json",
                    success: function (data) {
                        if(data != null)
                        {     
                            setNotification(data.message, data.type);
                        }

                        if(callback)
                        {
                            callback();
                        }
                    }
                });

            }
        });

        $(list.items).disableSelection();
    }
};