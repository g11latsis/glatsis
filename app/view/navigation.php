		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
            	
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                
                <a class="navbar-brand" href="/admin/index.php">CMS Admin</a>
            </div>

            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?= session_get_user()->fullname() ?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-fw fa-user"></i> Προφίλ</a>
                        </li>
                        
                        <li class="divider"></li>
                        <li>
                            <a href="../includes/logout.php"><i class="fa fa-fw fa-power-off"></i> Έξοδος</a>
                        </li>
                    </ul>
                </li>
                <li>
                	<a href="<?= url('user', 'logout') ?>"><i class="fa fa-fw fa-power-off"></i><?= _t('global.exit') ?></a>
                </li>
            </ul>
            
            
            <!-- /.navbar-collapse -->
        </nav>
        <a href="#" class="easyui-menubutton" data-options="menu:'#mm1',iconCls:'icon-edit'"></a>
        <div id="mm1" style="width:150px;">
        	<?php foreach ($data['menu'] as $menu): ?>
        	<div data-options="iconCls:'icon-undo'" onclick="window.location.href='<?= $menu->getUrl() ?>'"><?= $menu->title ?></div>
        	<?php endforeach; ?>
        </div>

        </div>
            
            