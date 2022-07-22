<?php 

    /**
     * 
     * @var DataForm $form
     */
    $form = $data['form'];
?>
<form id="df" class="easyui-form" method="post" iframe="false"  ajax="true"  style="width:100%;height:100%;">
<div id="form-tabstrip"  class="easyui-tabs" style="width:100%;height:100%;">
<?php foreach ($form->getTabs() as $tab): ?>

	<div title="<?= $tab->caption ?>" style="padding: 10px;"> <!-- tab div -->
	<?php $tabColCount = $tab->getColumnCount(); ?>
	<?php for ($col = 1; $col <= $tabColCount; $col++): ?>
	
    	<div style="float:left;width:300px;height:100%;"> <!-- Column -->
    	<?php foreach ($tab->getFields() as $f): ?>
    	<?php if ($f->colindex == $col): ?>
    	
    	<?php if ($f->isHidden()): ?>
    		<input type="hidden" name="<?= $f->name ?>" id="<?= $f->name ?>" value="<?= $f->value ?>">
    	<?php elseif ($f->isTextbox()): ?>
    		<input class="easyui-textbox"  
    				name="<?= $f->name ?>" 
    				id="<?= $f->name ?>" 
    				label="<?= addslashes($f->caption) ?>"
    				labelPosition="top"
    				value="<?= addslashes($f->value) ?>"
    				style="width: 250px">
    	<?php elseif ($f->isCombobox()): ?>
    		<select class="easyui-combobox"
    				name="<?= $f->name ?>" 
    				id="<?= $f->name ?>" 
    				label="<?= addslashes($f->caption) ?>"
    				labelPosition="top"
    				style="width: 250px">
    		<?php foreach ($f->loadValues() as $o): ?>
    			<option value="<?= $o->id ?>" <?= ($o->id == $f->value) ? 'selected="selected"' : '' ?>><?= safestr($o->value) ?></option>
    		<?php endforeach; ?>
    		</select>
    	<?php elseif ($f->isCheckbox()): ?>
    		<div style="margin-top: 10px">
    		<input class="easyui-checkbox"
    				name="<?= $f->name ?>" 
    				id="<?= $f->name ?>" 
    				label="<?= addslashes($f->caption) ?>"
    				data-options="checked: <?= ($f->value > 0) ? 'true' : 'false' ?>"
    				>
    				</div>
    	<?php endif; ?>
    	
    	<?php endif; ?>
    	<?php endforeach; ?>
    	</div> <!-- End Column -->
	
	<?php endfor; ?>
	</div> <!-- end tab div -->

<?php endforeach; ?>
</div>
</form>
