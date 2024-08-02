<div class="left-side-bar">
	<div class="brand-logo">
		<a href="<?= base_url('admin/home') ?>">
		<img src="/backend/vendors/images/logo.png" alt="" class="dark-logo" />
<img src="/backend/vendors/images/logo.png" alt="" class="light-logo" />

		</a>
		<div class="close-sidebar" data-toggle="left-sidebar-close">
			<i class="ion-close-round"></i>
		</div>
	</div>
	<div class="menu-block customscroll">
		<div class="sidebar-menu">
			<ul id="accordion-menu">
				<li>
					<a href="<?= base_url('admin/home') ?>" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-home"></span><span class="mtext">Home</span>
					</a>
				</li>
				<li>
					<a href="<?= base_url('/admin/tickets/new-ticket') ?>" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-add"></span><span class="mtext">Add Ticket</span>
					</a>
				</li>
				<li>
					<a href="<?= base_url('/admin/tickets/my-tickets') ?>" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-ticket"></span><span class="mtext">My Tickets</span>
					</a>
				</li>
				<?php if (\App\Libraries\CIAuth::role() !== '2' && \App\Libraries\CIAuth::role() !== '4'): ?>
					<li>
						<a href="<?= base_url('/admin/tickets/assign') ?>" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-user"></span><span class="mtext">Asign Developer</span>
						</a>
					</li>
					<li>
						<a href="<?= base_url('/admin/tickets/reports') ?>" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-user"></span><span class="mtext">Ticket Reports</span>
						</a>
					</li>
				<?php endif; ?>
				<?php if ( \App\Libraries\CIAuth::role() !== '4'): ?>
					<li>
						<a href="<?= base_url('/admin/tickets/assigned') ?>" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-user"></span><span class="mtext">Asigned Tickets</span>
						</a>
					</li>
				<?php endif; ?>
				<?php if (\App\Libraries\CIAuth::role() !== '4'): ?>

					<li>
						<a href="<?= base_url('/admin/get-users') ?>" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-user"></span><span class="mtext">Users</span>
						</a>
					</li>

					<li>
						<a href="<?= base_url('admin/roles') ?>" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-list"></span><span class="mtext">Roles</span>
						</a>

					<li>
					    <li>
						<a href="<?= base_url('/admin/categories/get-categories') ?>" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-user"></span><span class="mtext">Categories</span>
						</a>
					</li>
					<li>
						<a href="<?= base_url('admin/logs') ?>" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-list"></span><span class="mtext">System Logs</span>
						</a>

					<li>
					<?php endif; ?>

					<div class="dropdown-divider"></div>
				</li>
				<li>
					<div class="sidebar-small-cap">Settings</div>
				</li>
				<li>
					<a href="<?= base_url('/admin/profile') ?>" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-user"></span>
						<span class="mtext">Profile
						</span>
					</a>
					
				</li>

			</ul>
		</div>
	</div>
</div>