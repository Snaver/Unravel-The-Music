<?php $ci_uri = trim($this->uri->uri_string(), '/'); $att = ' id="active"';?>
    <div id="navlist">
		<ul id="navlist">
			<li<?= (preg_match('|^admin$|', $ci_uri) > 0)? $att: ''?>><?=anchor('admin/', 'home')?></li>	
			<li<?= (preg_match('|^admin/admins|', $ci_uri) > 0)? $att: ''?>><?=anchor('admin/admins', 'administrators')?></li>
			<li<?= (preg_match('|^admin/users|', $ci_uri) > 0)? $att: ''?>><?=anchor('admin/users', 'users')?></li>
			<li<?= (preg_match('|^admin/verify|', $ci_uri) > 0)? $att: ''?>><?=anchor('admin/verify', 'verify new artists')?></li>
			<li<?= (preg_match('|^admin/albums|', $ci_uri) > 0)? $att: ''?>><?=anchor('admin/albums', 'Manage Album Reports')?></li>
			<li<?= (preg_match('|^admin/songs|', $ci_uri) > 0)? $att: ''?>><?=anchor('admin/songs', 'Manage Song Reports')?></li>
			<li<?= (preg_match('|^admin/meanings|', $ci_uri) > 0)? $att: ''?>><?=anchor('admin/meanings', 'Manage Meaning Reports')?></li>
			<li<?= (preg_match('|^admin/replies|', $ci_uri) > 0)? $att: ''?>><?=anchor('admin/replies', 'Manage Reply Reports')?></li>						
							
		</ul>
</div>
