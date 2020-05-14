{extends file="page.tpl"}
{block name="content"}

<div class="ui grid">
    <div class="right aligned sixteen wide column">
            <a href="{path_for name="pdf_groups"}" class="ui button tooltip" title="{_T string="Export all groups and their members as PDF"}">
                <i class="icon file pdf"></i> {_T string="All groups PDF"}
            </a>

    </div>
    <div class="four wide column treemenu boxed">
        <div class="ui styled accordion">
{foreach item=g from=$groups_root}
    {include file="group_tree_item.tpl" item=$g current=$group}
{/foreach}
{if $login->isAdmin() or $login->isStaff()}
            <div class="title">
                <a href="{path_for name="add_group" data=["name" => NAME]}" id="newgroup" class="ui button">
                    <i class="icon plus" aria-hiddent="true"></i>
                    {_T string="New group"}
                </a>
            </div>
            <div class="content"></div>
{/if}
        </div>{* /accordion *}
    </div>{* /treemenu boxed column *}
    <div class="twelve wide column">
        {include file="group.tpl" group=$group groups=$groups}
    </div>
</div>{* /grid *}

{/block}

{block name="javascripts"}
<script type="text/javascript">
    $(function() {
        var _mode;

        {* New group *}
        $('#newgroup').click(function(){
        var _href = $(this).attr('href');
            var _el = $('<div id="add_group" class="center" title="{_T string="Add a new group"}"><label for="new_group_name">{_T string="Name:"}</label><input type="text" name="new_group_name" id="new_group_name" required/></div>');
            _el.appendTo('body').dialog({
                modal: true,
                hide: 'fold',
                buttons: {
                    "{_T string="Create" escape="js"}": function() {
                        var _name = $('#new_group_name').val();
                        if ( _name != '' ) {
                            //check uniqueness
                            $.ajax({
                                url: '{path_for name="ajax_groupname_unique"}',
                                type: "POST",
                                data: {
                                    ajax: true,
                                    gname: _name
                                },
                                {include file="js_loader.tpl"},
                                success: function(res){
                                    var _res = jQuery.parseJSON(res);
                                    if ( _res.success == false ) {
                                        if (_res.message) {
                                            alert(_res.message)
                                        } else {
                                            alert('{_T string="The group name you have requested already exits in the database."}');
                                            }
                                    } else {
                                        $(location).attr('href', _href.replace('NAME', _name));
                                    }
                                },
                                error: function() {
                                    alert("{_T string="An error occurred checking name uniqueness :(" escape="js"}");
                                }
                            });
                        } else {
                            alert('{_T string="Pleade provide a group name" escape="js"}');
                        }
                    }
                },
                close: function(event, ui){
                    _el.remove();
                }
            });
            return false;
        });

        {* Members popup *}
        var _btnuser_mapping = function(){
            $('#btnusers_small, #btnmanagers_small').click(function(){
                _mode = ($(this).attr('id') == 'btnusers_small') ? 'members' : 'managers';
                var _persons = $('input[name="' + _mode + '[]"]').map(function() {
                    return $(this).val();
                }).get();
                $.ajax({
                    url: '{path_for name="ajaxMembers"}',
                    type: "POST",
                    data: {
                        multiple: true,
                        from: 'groups',
                        gid: $('#id_group').val(),
                        mode: _mode,
                        members: _persons
                    },
                    {include file="js_loader.tpl"},
                    success: function(res){
                        _members_dialog(res, _mode);
                    },
                    error: function() {
                        alert("{_T string="An error occurred displaying members interface :(" escape="js"}");
                    }
                });
                return false;
            });
        }
        _btnuser_mapping();

        var _members_dialog = function(res, mode){
            var _title = '{_T string="Group members selection" escape="js"}';
            if ( mode == 'managers' ) {
                _title = '{_T string="Group managers selection" escape="js"}';
            }
            var _el = $('<div id="members_list" title="' + _title  + '"> </div>');
            _el.appendTo('body').dialog({
                modal: true,
                hide: 'fold',
                width: '80%',
                height: 550,
                close: function(event, ui){
                    _el.remove();
                }
            });
            _members_ajax_mapper(res, $('#group_id').val(), mode);

        }

        var _members_ajax_mapper = function(res, gid, mode){
            $('#members_list').append(res);
            $('#selected_members ul').css(
                'max-height',
                $('#members_list').innerHeight() - $('#btnvalid').outerHeight() - $('#selected_members header').outerHeight() - 65 // -65 to fix display; do not know why
            );
            $('#btnvalid').button().click(function(){
                //store entities in the original page so they can be saved
                var _container;
                if ( mode == 'managers' ) {
                    _container = $('#group_managers');
                } else {
                    _container = $('#group_members');
                }
                var _persons = new Array();
                $('li[id^="member_"]').each(function(){
                    _persons[_persons.length] = this.id.substring(7, this.id.length);
                });
                $('#members_list').dialog("close");

                $.ajax({
                    url: '{path_for name="ajaxGroupMembers"}',
                    type: "POST",
                    data: {
                        persons: _persons,
                        person_mode: mode
                    },
                    {include file="js_loader.tpl"},
                    success: function(res){
                        _container.find('table.listing').remove();
                        _container.children('div').append(res);
                    },
                    error: function() {
                        alert("{_T string="An error occurred displaying members interface :(" escape="js"}");
                    }
                });
            });
            //Remap links
            var _none = $('#none_selected').clone();
            $('li[id^="member_"]').click(function(){
                $(this).remove();
                if ( $('#selected_members ul li').length == 0 ) {
                    $('#selected_members ul').append(_none);
                }
            });
            $('#members_list #listing tbody a').click(function(e){
                e.preventDefault();
                var _mid = this.href.match(/.*\/(\d+)$/)[1];
                var _mname = $(this).text();
                $('#none_selected').remove()
                if ( $('#member_' + _mid).length == 0 ) {
                    var _li = '<li id="member_' + _mid + '"><i class="fas fa-user-minus"></i> ' + _mname + '</li>';
                    $('#selected_members ul').append(_li);
                    $('#member_' + _mid).click(function(){
                        $(this).remove();
                        if ( $('#selected_members ul li').length == 0 ) {
                            $('#selected_members ul').append(_none);
                        }
                    });
                }
                return false;
            });

            $('#members_list .pages a').click(function(){
                var gid = $('#the_id').val();
                var _members = new Array();
                $('li[id^="member_"]').each(function(){
                    _members[_members.length] = this.id.substring(7, this.id.length);
                });

                $.ajax({
                    url: this.href,
                    type: "POST",
                    data: {
                        from: 'groups',
                        gid: gid,
                        members: _members,
                        mode: _mode,
                        multiple: true
                    },
                    {include file="js_loader.tpl"},
                    success: function(res){
                        $('#members_list').empty();
                        _members_ajax_mapper(res, gid, _mode);
                    },
                    error: function() {
                        alert("{_T string="An error occurred displaying members interface :(" escape="js"}");
                    }
                });
                return false;
            });
        }
    });
</script>
{/block}
