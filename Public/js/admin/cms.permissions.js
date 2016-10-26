var permissions = {
    'init':     function (selector) {

        $(selector + ' .parent').each(function () {

            permissions.traverseChildrens($(this).data('id'), $(this).find('span').attr('class'), false);

        });
    },
    'traverseChildrens': function (id, state, redefine) {
        $('.child-of-node-' + id).each(function () {

            var changeNextState = false;
            var checkbox = $(this).find('span');
            
            if(redefine == true)
            {
                $(this).find('input').val('');
                changeNextState = true;
            }
            else
            {
                if($('#' + checkbox.data('input')).val() == '')
                {
                    changeNextState = true;
                }
            }
            
            if(changeNextState == true)
            {
                switch (state)
                {
                    case 'checkbox-allow':
                        checkbox.data('nextstate', 'checkbox-deny');
                    break;
                    case 'checkbox-deny':
                        checkbox.data('nextstate', 'checkbox');
                    break;
                    default:
                        checkbox.data('nextstate', 'checkbox-allow');
                    break;
                }             
                checkbox.removeClass().addClass(state);
            }
            
            

            if($(this).hasClass('parent'))
            {
                permissions.traverseChildrens(checkbox.data('id'), state, redefine);
            }

        });
    },
    'traverseParents': function (id, state) {

        var parent = $('#node-' + id);
        var parentInput = $('#permissions-' + id);

        if(parentInput.val() == state)
        {
            return true;
        }

        if(parent.data('parentid'))
        {
            return permissions.traverseParents(parent.data('parentid'), state);
        }
        else
        {
            return false;
        }
    },
    'click': function (checkbox) {

        if(permissions.traverseParents(checkbox.data('parentid'), '0'))
        {
            return;
        }   

        switch (checkbox.data('nextstate'))
        {
            case 'checkbox-allow':
                state = { value: '1', next: 'checkbox-deny', current: 'checkbox-allow' };
            break;
            case 'checkbox-deny':
                state = { value: '0', next: 'checkbox', current: 'checkbox-deny' };
            break;
            default:                        
                state = { value: '', next: 'checkbox-allow', current: 'checkbox' };

                if(permissions.traverseParents(checkbox.data('parentid'), '1'))
                {
                    state.current = 'checkbox-allow';
                    state.next = 'checkbox-deny';
                }
            break;
        }

        $('#' + checkbox.data('input')).val(state.value);
        checkbox.data('nextstate', state.next);

        checkbox.removeClass().addClass(state.current);

        if($('#node-' + checkbox.data('id')).hasClass('parent'))
        {
            permissions.traverseChildrens(checkbox.data('id'), state.current, true);
        }
    }
};
