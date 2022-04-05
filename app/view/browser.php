<?php $filters = $data['datagrid']->getFilters(); ?>

<div class="easyui-layout" style="width:100%; height:760px">

    <div data-options="region:'west',split:true,hideCollapsedContent:false,collapsed:true" 
    	 title="<?= _t('browser.filters') ?>"
    	 style="width: 200px; height: 450px; padding: 5px;">
    
    	<?php if (!empty($filters)): ?>
    	
    	<a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'" onclick="browser_search()"><?= _t('search') ?></a>
    	<?php foreach ($filters as $f): ?>
    	<div style="margin-bottom:5px">
    	<?php if ($f->isText()): ?>
    	
            <input class="easyui-textbox" 
            	   name="<?= $f->name ?>"
            	   id="<?= $f->name ?>"
            	   label="<?= $f->caption ?>" 
            	   labelPosition="top" 
            	   data-options="prompt:'',validType:''" 
            	   style="width:100%;">
        
    	
    	<?php elseif ($f->isLookup()): ?>
    	
    		<select class="easyui-combobox" 
    				name="<?= $f->name ?>"
    				id="<?= $f->name ?>"
    				label="<?= $f->caption ?>"
    				labelPosition="top" 
    				style="width:100%;">
    			<option value="0"></option>
    			<?php foreach ($f->loadValues() as $o): ?>
                <option value="<?= $o->id ?>"><?= safestr($o->value); ?></option>
                <?php endforeach; ?>
            </select>
    	
    	<?php endif; ?>
    	</div>
    	<?php endforeach; ?>
    	<?php endif; ?>
    
    </div> <!-- End Filters section -->
    
    <div data-options="region:'center'">
    	<table class="easyui-datagrid" 
    		id="databrowser"
    		title="<?= $data['datagrid']->getTitle() ?>" 
    		style="width:100%;height:750px"
    		url="<?= url('browser', 'browser_data', ['eid' => $data['eid']]) ?>"
    		rownumbers="true" 
    		pagination="true"
    		pageSize="<?= $data['datagrid']->getPageSize() ?>"
    		pageList="[<?= $data['datagrid']->getPageSize() ?>]"
    		multisort="true"
            data-options="singleSelect:true,
            			  collapsible:true,
            			  method:'get',
            			  toolbar: browser_toolbar"
            >
            <thead>
                <tr>
                	<?php foreach ($data['datagrid']->getFields() as $f): ?>
                	<?php if ($f->visible): ?>
                    <th data-options="field:'<?= $f->fieldName ?>',
                    				  width:<?= $f->width ?>,
                    				  align:'<?= $f->alignment ?>',
                    				  sortable:<?= ($f->sortable ? 'true' : 'false') ?>"><?= $f->caption ?></th>
                    <?php endif; ?>
    				<?php endforeach; ?>
                </tr>
            </thead>
        </table>
    </div>
    <div id="dataform"></div>
</div>

<script>

function browser_search(url)
{
	let filters = [];

    <?php foreach ($filters as $f): ?>
    <?php if ($f->isText()): ?>
	filters.push({
		name: '<?= $f->fullname ?>',
		value: $('#<?= $f->name ?>').textbox('getValue'),
		operator: 'like'
	});
    <?php elseif ($f->isLookup()): ?>
    filters.push({
		name: '<?= $f->fullname ?>',
		value: $('#<?= $f->name ?>').combobox('getValue'),
		operator: 'equal'
	});
    <?php endif; ?>
    <?php endforeach; ?>

    $('#databrowser').datagrid('load', {filters: JSON.stringify(filters)});
}

var browser_toolbar = [{
        text:'<?= _t('new') ?>',
        iconCls:'icon-add',
        handler:function(){
        	showDataformDialog(
                	'<?= url('dataform', 'edit', ['eid' => $data['eid']]) ?>', 
                	'<?= $data['datagrid']->getNewTitle() ?>',
                	'<?= url('dataform', 'save', ['eid' => $data['eid']]) ?>');
        }
    },{
        text:'<?= _t('update') ?>',
        iconCls:'icon-pencil',
        handler:function(){

            let row = $('#databrowser').datagrid('getSelected');
            if (row) {
                let url = '<?= url('dataform', 'edit', ['eid' => $data['eid']]) ?>' + '&id=' + row.id;
                let postUrl = '<?= url('dataform', 'save', ['eid' => $data['eid']]) ?>' + '&id=' + row.id;
        		showDataformDialog(url, '<?= _t('dialog.edit') ?>', postUrl);
            }
        }
    },{
        text:'<?= _t('delete') ?>',
        iconCls:'icon-cancel',
        handler:function(){

        	let row = $('#databrowser').datagrid('getSelected');
        	if (row) {
                let url = '<?= url('dataform', 'delete', ['eid' => $data['eid']]) ?>' + '&id=' + row.id;
                let title = '<?= _t('delete.title') ?>';
                let message = '<?= _t('delete.message') ?>';
        		deleteRecord(url, title, message);
            }
       	}
}];

</script>

