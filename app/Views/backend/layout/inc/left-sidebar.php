<div class="left-side-bar">
	<div class="brand-logo">
		<a href="index.html">
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
					<a href="<?= route_to('admin.home') ?>" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-home"></span><span class="mtext">Home</span>
					</a>
				</li>
				<li>
					<a href="<?= base_url('/admin/tickets/new-ticket') ?>" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-home"></span><span class="mtext">Add Ticket</span>
					</a>
				</li>
				<li>
					<a href="<?= base_url('/admin/tickets/my-tickets') ?>" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-home"></span><span class="mtext">My Ticket</span>
					</a>
				</li>
				<?php if (\App\Libraries\CIAuth::role() !== 'default'): ?>

				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon dw dw-newspaper"></span><span class="mtext">Tickets</span>
					</a>
					<ul class="submenu">

						<li><a href="<?= base_url('admin/my-tickets') ?>">My Tickets</a></li>
						<li><a href="<?= base_url('/admin/all-ticket') ?>">All Tickets</a></li>
						<li><a href="<?= base_url('/admin/tickets/new-ticket') ?>">Add Ticket</a></li>
							<li class="dropdown">
								<a href="javascript:;" class="dropdown-toggle">
									<span class="micon dw dw-newspaper"></span><span class="mtext">Tickets Status</span>
								</a>
								<ul class="submenu">
									<li><a href="<?= route_to('products') ?>">Open</a></li>
									<li><a href="<?= route_to('add-product') ?>">Closed</a></li>
									<li><a href="<?= route_to('add-product') ?>">Reopened</a></li>

								</ul>
							</li>
							<?php endif; ?>

						</ul>
						<?php if (\App\Libraries\CIAuth::role() !== 'default'): ?>


					<li>
						<a href="<?= base_url('/admin/get-users') ?>" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-user"></span><span class="mtext">Users</span>
						</a>

					<li>
					<li>
						<a href="<?= route_to('admin.roles.index') ?>" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-list"></span><span class="mtext">Roles</span>
						</a>

					<li>
					<?php endif; ?>



					<div class="dropdown-divider"></div>
				</li>
				<li>
					<div class="sidebar-small-cap">Settings</div>
				</li>
				<li>
					<a href="" target="_blank" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-user"></span>
						<span class="mtext">Profile
						</span>
					</a>
				</li>
				<li>
					<a href="" target="_blank" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-settings"></span>
						<span class="mtext">General
						</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>