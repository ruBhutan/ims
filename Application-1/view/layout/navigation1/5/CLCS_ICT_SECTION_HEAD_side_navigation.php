<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
<div class="menu_section">
<br><br><br>
<ul class="nav side-menu">
	<li><a><i class="fa fa-home"></i>Home<span class="fa fa-chevron-down"></span></a>
	</li>
	<li><a><i class="fa fa-home"></i>Administration<span class="fa fa-chevron-down"></span></a>
		<ul class="nav child_menu">
			<li><a href="<?php echo $this->url('addroles') ?>">Add Role</a></li>
			<li><a href="<?php echo $this->url('adduser') ?>">Add User</a></li>
			<li><a href="<?php echo $this->url('configureuserroutes') ?>">Configure User Routes</a></li>
			<li><a href="<?php echo $this->url('adduserworkflow') ?>">Add User Work Flow</a></li>
			<li><a href="<?php echo $this->url('changeuserpassword') ?>">Change User Password</a></li>
			<li><a href="<?php echo $this->url('changepassword') ?>">Change Password</a></li>
		</ul>
	</li>
	<li><a><i class="fa fa-users"></i>Human Resource<span class="fa fa-chevron-down"></span></a>
	</li>
	<li><a><i class="fa fa-database"></i>Property and Inventory<span class="fa fa-chevron-down"></span></a>
		<ul class="nav child_menu">
			<li><a><i class=""></i>Goods Requisition<span class="fa fa-chevron-down"></span></a>
			<ul class="nav child_menu">
				<li><a href="<?php echo $this->url('all-goods-requisition-list') ?>">All Requisition List</a></li>
			</ul>
			</li>
			<li><a><i class=""></i>Goods Transaction<span class="fa fa-chevron-down"></span></a>
			<ul class="nav child_menu">
				<li><a href="<?php echo $this->url('view-item-details') ?>">Add Item Details</a></li>
			</ul>
			</li>
		</ul>
	</li>
	<li><a><i class="fa fa-graduation-cap"></i>Research Management<span class="fa fa-chevron-down"></span></a>
		<ul class="nav child_menu">
			<li><a><i class=""></i>Research Grants<span class="fa fa-chevron-down"></span></a>
			<ul class="nav child_menu">
				<li><a href="<?php echo $this->url('applycollegegrant') ?>">Apply for College Grants</a></li>
				<li><a href="<?php echo $this->url('listcarggrants') ?>">List of College Grants</a></li>
			</ul>
			</li>
		</ul>
	</li>
</ul>
</div>
</div>
