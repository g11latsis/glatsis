function login(url)
{
	let f = $('#login-form');
	
	$.ajax({
		type: 'POST',
		dataType: "json",
		url: url,
		data: f.serialize(),
		success: function(data) {
			if (data.status == 200)
			{
				window.location.href = 'index.php?c=default&a=screen_main';
			}
			else
			{
				$('#messages').html(data.message);
			}
		},
		error: function(data) {
			$('#messages').html(data.message);	
		}
	});
}

function showMessage(title, message)
{
    $.messager.show({
        title: title,
        msg: message,
        showType:'slide',
        style:{
            right:'',
            top:document.body.scrollTop+document.documentElement.scrollTop,
            bottom:''
        },
		timeout: 5000
    });
}

function errorField(accessor, flag)
{
	$(accessor).each(function(i) {
		$(this).css('background-color', flag ? '#f2dede' : '');
    	$(this).css('border-color', flag ? '#d59392' : '');
	});
}

function alertFormFields(data, flag)
{
	let bgcolor = flag ? '#f2dede' : '';
	let bordercolor = flag ? '#d59392' : '';
	
    $.each(data, function(field, info) {
		var accessor = 'input[name="' + field + '"]';
        if ($(accessor).length > 0) {
            $('#' + field).textbox('textbox').css('background-color', bgcolor);
			$('#' + field).textbox('textbox').css('border-color', bordercolor);
        } else {
            accessor = 'select[name="' + field + '"]';
            if ($(accessor).length > 0) {
                var t = $('#' + field);
				var el = t.data('textbox') ? t.next() : $(t);
				el.css('background-color', bgcolor);
				el.css('border-color', bordercolor);
            } else {
                accessor = 'textarea[name="' + field + '"]';
                if ($(accessor).length > 0) {
                    errorField(accessor, flag);
                }
            }
        }
    });
}

function dealertFormFields(formId)
{
    $('#' + formId).find(':input').each(function(i){
        $(this).css('background-color', '');
        $(this).css('border-color', '');
    });

    $('#' + formId).find('textarea').each(function(i){
        $(this).css('background-color', '');
        $(this).css('border-color', '');
    });

    $('#' + formId).find('select').each(function(i){
        $(this).css('background-color', '');
        $(this).css('border-color', '');
    });
}

function postDataForm(url, closeDialog)
{
	g_postSuccess = false;
	$.ajax({
		type: 'POST',
		dataType: "json",
		url: url,
		data: $('#df').serialize(),
		beforeSend: function(jqXHR, settings) 
        {
            dealertFormFields('df');
        },
		success: function(data) {
			if (data.status == 200)
			{
				if (data.data.newid) {console.log(data.data);
					$('#id').val(data.data.newid);
					$('#dataform').dialog('setTitle', data.data.title);
				}
				showMessage('', data.message);
				$('#databrowser').datagrid('reload');
				
				if (closeDialog) {
					$('#dataform').dialog('close');
				}
			}
			else if (data.status == 406 || data.status == 409)
			{
				let html = '<ul>';
				$.each(data.data, function(field, info) {
					html += '<li>' + info.caption + '</li>';
				});
				html += '</ul>';
				
				showMessage('', data.message + html);
			}
			else
			{
				showMessage('', data.message);
			}
		},
		error: function(data) {
			$('#messages').html(data.message);	
		}
	});
}

function showDataformDialog(url, title, postUrl)
{
	$('#dataform').dialog({
	    title: title,
	    width: '80%',
	    height: 500,
	    closed: false,
		resizable: true,
	    cache: false,
	    href: url,
	    modal: true,
		buttons: [
			{
				text: '',
				iconCls:'icon-save',
				handler: function(){
					postDataForm(postUrl, false);
				}
			},
			{
				text: '',
				iconCls:'icon-ok',
				handler: function(){
					postDataForm(postUrl, true);
				}
			}
		],
		tools:[{
	        iconCls:'icon-reload',
	        handler:function(){
				$('#dataform').dialog('refresh', url);
			}
	    }]
	});
}

function deleteRecord(url, title, message)
{
	$.messager.confirm(title, message, function(r){
        if (r) {
            $.ajax({
                type: 'GET',
				dataType: "json",
                url: url,
                async: true,
                success: function(data) 
                {
                    showMessage('', data.message);
					if (data.status == 200) {
						$('#databrowser').datagrid('reload');
					}
                },
                error: function() 
                {
                    showMessage('', 'Could not complete the request.');
                }
            });
        }
    });
}